@extends('layouts.app')

@section('title', 'Ügyfél dashboard')

@section('content')
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Üdvözlünk, {{ auth()->user()->name }}!</h2>
            </div>
            <div class="col-md-4 text-end">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservations" type="button" role="tab">
                    <i class="bi bi-calendar-check"></i> Foglalások
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
                    <i class="bi bi-credit-card"></i> Fizetések
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="reservations" role="tabpanel">
                <h4 class="mb-3">Foglalásaim</h4>
                @if ($reservations && $reservations->isEmpty())
                    <div class="alert alert-info">Még nincsenek foglalásaid.</div>
                    <a href="/cars" class="btn btn-primary">Autó foglalása</a>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foglalás</th>
                                    <th>Autó</th>
                                    <th>Kezdés</th>
                                    <th>Befejezés</th>
                                    <th>Felvétel</th>
                                    <th>Leadás</th>
                                    <th>Végösszeg</th>
                                    <th>Státusz</th>
                                    <th>Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reservations as $reservation)
                                    <tr>
                                        <td>#{{ $reservation->id }}</td>
                                        <td>{{ $reservation->car?->brand ?? 'N/A' }} {{ $reservation->car?->model ?? '' }}</td>
                                        <td>{{ $reservation->start_date?->format('Y-m-d') ?? 'N/A' }}</td>
                                        <td>{{ $reservation->end_date?->format('Y-m-d') ?? 'N/A' }}</td>
                                        <td>{{ $reservation->pickup_location ?? '-' }}</td>
                                        <td>{{ $reservation->dropoff_location ?? '-' }}</td>
                                        <td>{{ number_format($reservation->total_price, 0, ',', ' ') }} Ft</td>
                                        <td>
                                            @php
                                                $badge = match ($reservation->status) {
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'active' => 'success',
                                                    'cancelled' => 'danger',
                                                    'completed' => 'secondary',
                                                    default => 'light',
                                                };

                                                $statusLabel = match ($reservation->status) {
                                                    'pending' => 'Függőben',
                                                    'confirmed' => 'Megerősítve',
                                                    'active' => 'Megerősítve',
                                                    'cancelled' => 'Lemondva',
                                                    'completed' => 'Teljesítve',
                                                    default => $reservation->status,
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            <a href="/client/reservations/{{ $reservation->id }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Nincs foglalás.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="tab-pane fade" id="payments" role="tabpanel">
                <h4 class="mb-3">Fizetéseim</h4>
                @if ($payments && $payments->isEmpty())
                    <div class="alert alert-info">Még nincsenek fizetéseid.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foglalás</th>
                                    <th>Autó</th>
                                    <th>Összeg</th>
                                    <th>Módszer</th>
                                    <th>Státusz</th>
                                    <th>Dátum</th>
                                    <th>Művelet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    @php
                                        $methodLabel = match ($payment->method) {
                                            'cash' => 'Készpénz',
                                            'card' => 'Bankkártya',
                                            'bank_transfer' => 'Átutalás',
                                            'demo' => 'Bankkártya',
                                            default => $payment->method,
                                        };
                                    @endphp
                                    <tr>
                                        <td>Foglalás #{{ $payment->reservation_id }}</td>
                                        <td>{{ $payment->reservation?->car?->brand ?? 'N/A' }} {{ $payment->reservation?->car?->model ?? '' }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} Ft</td>
                                        <td>{{ $methodLabel }}</td>
                                        <td>
                                            @php
                                                $badge = match ($payment->status) {
                                                    'paid' => 'success',
                                                    'pending' => 'warning',
                                                    'failed' => 'danger',
                                                    'refunded' => 'info',
                                                    default => 'light',
                                                };

                                                $paymentStatusLabel = match ($payment->status) {
                                                    'paid' => 'Teljesítve',
                                                    'pending' => 'Függőben',
                                                    'failed' => 'Sikertelen',
                                                    'refunded' => 'Visszatérítve',
                                                    default => $payment->status,
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ $paymentStatusLabel }}</span>
                                        </td>
                                        <td>{{ $payment->paid_at?->format('Y-m-d') ?? $payment->created_at?->format('Y-m-d') }}</td>
                                        <td>
                                            @if ($payment->status === 'pending')
                                                <form action="/payments/{{ $payment->id }}/pay" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">Fizetés</button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Nincs fizetés.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection