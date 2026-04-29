<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationApiController extends Controller
{
    private const EDITABLE_STATUSES = ['pending', 'confirmed', 'active', 'cancelled', 'completed'];

    public function index(Request $request): JsonResponse
    {
        $reservations = Reservation::with(['car', 'user', 'payments'])
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->get();

        return response()->json(['reservations' => $reservations]);
    }

    public function locations(): JsonResponse
    {
        return response()->json([
            'locations' => config('reservation.locations', []),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $locations = config('reservation.locations', []);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'car_id' => ['required', 'exists:cars,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_location' => ['required', 'string', 'max:255', Rule::in($locations)],
            'dropoff_location' => ['required', 'string', 'max:255', Rule::in($locations)],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(self::EDITABLE_STATUSES)],
        ]);

        $car = Car::findOrFail($validated['car_id']);

        if ($car->status !== 'available') {
            return response()->json([
                'message' => 'Ez az autó jelenleg nem foglalható.',
            ], 422);
        }

        if ($this->hasDateConflict($car->id, $validated['start_date'], $validated['end_date'])) {
            return response()->json([
                'message' => 'Erre az időszakra az autó már foglalt.',
            ], 422);
        }

        $days = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        $reservation = Reservation::create([
            'user_id' => $validated['user_id'],
            'car_id' => $validated['car_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'pickup_location' => $validated['pickup_location'],
            'dropoff_location' => $validated['dropoff_location'],
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'pending',
            'total_price' => $days * (float) $car->daily_price,
        ]);

        if ($reservation->status === 'completed' && ! $reservation->payments()->exists()) {
            Payment::create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'amount' => $reservation->total_price,
                'method' => 'card',
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Foglalás létrehozva.',
            'reservation' => $reservation->load(['car', 'user', 'payments']),
        ], 201);
    }

    public function show(Reservation $reservation): JsonResponse
    {
        return response()->json([
            'reservation' => $reservation->load(['car', 'user', 'payments']),
        ]);
    }

    public function update(Request $request, Reservation $reservation): JsonResponse
    {
        $locations = config('reservation.locations', []);

        $validated = $request->validate([
            'user_id' => ['sometimes', 'required', 'exists:users,id'],
            'car_id' => ['sometimes', 'required', 'exists:cars,id'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date'],
            'pickup_location' => ['sometimes', 'required', 'string', 'max:255', Rule::in($locations)],
            'dropoff_location' => ['sometimes', 'required', 'string', 'max:255', Rule::in($locations)],
            'notes' => ['nullable', 'string'],
            'status' => ['sometimes', Rule::in(self::EDITABLE_STATUSES)],
        ]);

        $carId = $validated['car_id'] ?? $reservation->car_id;
        $startDate = $validated['start_date'] ?? $reservation->start_date?->format('Y-m-d');
        $endDate = $validated['end_date'] ?? $reservation->end_date?->format('Y-m-d');

        if (! $startDate || ! $endDate || Carbon::parse($endDate)->lt(Carbon::parse($startDate))) {
            return response()->json([
                'message' => 'A befejezés dátuma nem lehet korábbi a kezdésnél.',
            ], 422);
        }

        $car = Car::findOrFail($carId);

        if ($car->status !== 'available') {
            return response()->json([
                'message' => 'Ez az autó jelenleg nem foglalható.',
            ], 422);
        }

        if ($this->hasDateConflict($carId, $startDate, $endDate, $reservation->id)) {
            return response()->json([
                'message' => 'Erre az időszakra az autó már foglalt.',
            ], 422);
        }

        $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

        $reservation->update([
            ...$validated,
            'car_id' => $carId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $days * (float) $car->daily_price,
        ]);

        if (($validated['status'] ?? null) === 'completed' && ! $reservation->payments()->exists()) {
            Payment::create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'amount' => $reservation->total_price,
                'method' => 'card',
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Foglalás frissítve.',
            'reservation' => $reservation->fresh()->load(['car', 'user', 'payments']),
        ]);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json([
            'message' => 'Foglalás törölve.',
        ]);
    }

    private function hasDateConflict(int $carId, string $startDate, string $endDate, ?int $ignoreReservationId = null): bool
    {
        return Reservation::query()
            ->where('car_id', $carId)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->when($ignoreReservationId, fn ($query) => $query->where('id', '!=', $ignoreReservationId))
            ->where(function ($query) use ($startDate, $endDate) {
                $query
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}
