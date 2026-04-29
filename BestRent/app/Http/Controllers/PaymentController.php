<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private const METHODS = ['cash', 'card', 'bank_transfer'];

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $payments = Payment::with(['reservation.car', 'user'])
            ->when(! $user->is_admin, fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->get();

        return response()->json(['payments' => $payments]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_id' => ['required', 'exists:reservations,id'],
            'method' => ['required', 'in:' . implode(',', self::METHODS)],
            'amount' => ['nullable', 'numeric', 'min:1'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);
        $user = $request->user();

        if (! $user->is_admin && $reservation->user_id !== $user->id) {
            return response()->json(['message' => 'Nincs jogosultság.'], 403);
        }

        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'amount' => $validated['amount'] ?? $reservation->total_price,
            'method' => $validated['method'],
            'status' => 'paid',
            'transaction_id' => $validated['transaction_id'] ?? null,
            'paid_at' => now(),
        ]);

        return response()->json([
            'message' => 'Fizetés rögzítve.',
            'payment' => $payment->load(['reservation.car', 'user']),
        ], 201);
    }

    public function show(Request $request, Payment $payment): JsonResponse
    {
        $user = $request->user();

        if (! $user->is_admin && $payment->user_id !== $user->id) {
            return response()->json(['message' => 'Nincs jogosultság.'], 403);
        }

        return response()->json([
            'payment' => $payment->load(['reservation.car', 'user']),
        ]);
    }

    public function update(Request $request, Payment $payment): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'required', 'in:pending,paid,failed,refunded'],
            'method' => ['sometimes', 'required', 'in:' . implode(',', self::METHODS)],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['nullable', 'date'],
        ]);

        $payment->update($validated);

        return response()->json([
            'message' => 'Fizetés frissítve.',
            'payment' => $payment->fresh()->load(['reservation.car', 'user']),
        ]);
    }

    public function pay(Request $request, Payment $payment)
    {
        $user = $request->user();

        if ($user->is_admin || $payment->user_id !== $user->id) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            $message = 'Ez a fizetés már teljesítve lett.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : back()->with('error', $message);
        }

        $payment->update([
            'status' => 'paid',
            'method' => 'card',
            'transaction_id' => 'DEMO-' . $payment->id,
            'paid_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Fizetés teljesítve.',
                'payment' => $payment->fresh()->load(['reservation.car', 'user']),
            ]);
        }

        return redirect('/client/dashboard')->with('success', 'A fizetés sikeresen teljesítve lett.');
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();

        return response()->json([
            'message' => 'Fizetés törölve.',
        ]);
    }
}
