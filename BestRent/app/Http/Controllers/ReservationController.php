<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $reservations = Reservation::with(['car', 'user', 'payments'])
            ->when(! $user->is_admin, fn ($query) => $query->where('user_id', $user->id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->get();

        return response()->json(['reservations' => $reservations]);
    }

    public function store(Request $request)
    {
        $locations = config('reservation.locations', []);

        if ($request->user()->is_admin) {
            $message = 'Admin fiókkal nem lehet autót foglalni.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 403)
                : redirect('/admin/dashboard')->with('error', $message);
        }

        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_location' => ['required', 'string', 'max:255', Rule::in($locations)],
            'dropoff_location' => ['required', 'string', 'max:255', Rule::in($locations)],
            'notes' => ['nullable', 'string'],
        ]);

        $car = Car::findOrFail($validated['car_id']);

        if ($car->status !== 'available') {
            $message = 'Ez az autó jelenleg nem foglalható.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : back()->withErrors(['car_id' => $message])->withInput();
        }

        $isBooked = Reservation::where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($validated) {
                $query
                    ->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($isBooked) {
            $message = 'Erre az időszakra az autó már foglalt.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : back()->withErrors(['start_date' => $message])->withInput();
        }

        $days = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;
        $totalPrice = $days * (float) $car->daily_price;

        $reservation = Reservation::create([
            'user_id' => $request->user()->id,
            'car_id' => $validated['car_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'pickup_location' => $validated['pickup_location'],
            'dropoff_location' => $validated['dropoff_location'],
            'notes' => $validated['notes'] ?? null,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Foglalás létrehozva.',
                'reservation' => $reservation->load(['car', 'user']),
            ], 201);
        }

        return redirect('/client/dashboard')->with('success', 'A foglalás sikeresen létrejött.');
    }

    public function show(Request $request, Reservation $reservation): JsonResponse
    {
        $user = $request->user();

        if (! $user->is_admin && $reservation->user_id !== $user->id) {
            return response()->json(['message' => 'Nincs jogosultság.'], 403);
        }

        return response()->json([
            'reservation' => $reservation->load(['car', 'user', 'payments']),
        ]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $user = $request->user();

        if ($user->is_admin) {
            $validated = $request->validate([
                'status' => ['sometimes', 'required', 'in:pending,confirmed,active,cancelled,completed'],
                'notes' => ['nullable', 'string'],
                'pickup_location' => ['nullable', 'string'],
                'dropoff_location' => ['nullable', 'string'],
            ]);

            $reservation->update($validated);

            if (($validated['status'] ?? null) === 'completed' && ! $reservation->payments()->exists()) {
                Payment::create([
                    'reservation_id' => $reservation->id,
                    'user_id' => $reservation->user_id,
                    'amount' => $reservation->total_price,
                    'method' => 'card',
                    'status' => 'pending',
                ]);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Foglalás frissítve.',
                    'reservation' => $reservation->fresh()->load(['car', 'user', 'payments']),
                ]);
            }

            return redirect('/admin/dashboard')->with('success', 'Foglalás sikeresen módosítva!');
        }

        if ($reservation->user_id !== $user->id) {
            return response()->json(['message' => 'Nincs jogosultság.'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:cancelled'],
        ]);

        if (! in_array($reservation->status, ['pending', 'confirmed'], true)) {
            return response()->json([
                'message' => 'Ez a foglalás már nem törölhető ügyfélként.',
            ], 422);
        }

        $reservation->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Foglalás lemondva.',
                'reservation' => $reservation->fresh()->load(['car', 'user', 'payments']),
            ]);
        }

        return redirect('/client/dashboard')->with('success', 'Foglalás sikeresen lemondva!');
    }

    public function edit(Reservation $reservation)
    {
        return view('admin.reservations.edit', ['reservation' => $reservation->load(['car', 'user', 'payments'])]);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json([
            'message' => 'Foglalás törölve.',
        ]);
    }
}