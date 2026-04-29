@extends('layouts.app')

@section('title', 'Foglalás részletei')

@section('content')
    <div class="container my-5">
        @if (session('success'))
            <div class="reservation-success-banner">
                <div class="reservation-success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="reservation-success-text">
                    <h4>{{ session('success') }}</h4>
                    <p>A foglalás részleteit alul megtekintheti.</p>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2>Foglalás #{{ $reservation->id }}</h2>
            <a href="/client/dashboard" class="btn btn-outline-secondary">Vissza a foglalásokhoz</a>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Foglalási adatok</h5>
                        <p><strong>Autó:</strong> {{ $reservation->car?->brand }} {{ $reservation->car?->model }}</p>
                        <p><strong>Kezdés:</strong> {{ $reservation->start_date?->format('Y-m-d') }}</p>
                        <p><strong>Befejezés:</strong> {{ $reservation->end_date?->format('Y-m-d') }}</p>
                        <p><strong>Felvétel helye:</strong> {{ $reservation->pickup_location }}</p>
                        <p><strong>Leadás helye:</strong> {{ $reservation->dropoff_location }}</p>
                        <p><strong>Végösszeg:</strong> {{ number_format($reservation->total_price, 0, ',', ' ') }} Ft</p>
                        @php
                            $statusLabel = match ($reservation->status) {
                                'pending' => 'Függőben',
                                'confirmed' => 'Megerősítve',
                                'active' => 'Megerősítve',
                                'cancelled' => 'Lemondva',
                                'completed' => 'Teljesítve',
                                default => $reservation->status,
                            };
                        @endphp
                        <p><strong>Státusz:</strong> {{ $statusLabel }}</p>
                        @if ($reservation->notes)
                            <p class="mb-0"><strong>Megjegyzés:</strong> {{ $reservation->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Autó adatai</h5>
                        @if ($reservation->car?->image_url)
                            <img src="{{ $reservation->car->image_url }}" alt="{{ $reservation->car->brand }} {{ $reservation->car->model }}" class="img-fluid rounded mb-3 image-cover-240">
                        @endif
                        <p><strong>Kategória:</strong> {{ $reservation->car?->category }}</p>
                        <p><strong>Napidíj:</strong> {{ number_format($reservation->car?->daily_price ?? 0, 0, ',', ' ') }} Ft</p>
                        <p><strong>Üzemanyag:</strong> {{ $reservation->car?->fuel_type ?: '-' }}</p>
                        <p><strong>Váltó:</strong> {{ $reservation->car?->transmission ?: '-' }}</p>
                        <p class="mb-0"><strong>Rendszám:</strong> {{ $reservation->car?->plate_number }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($reservation->payments->isNotEmpty())
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Fizetés</h5>

                    @foreach ($reservation->payments as $payment)
                        @php
                            $methodLabel = match ($payment->method) {
                                'cash' => 'Készpénz',
                                'card' => 'Bankkártya',
                                'bank_transfer' => 'Átutalás',
                                'demo' => 'Bankkártya',
                                default => $payment->method,
                            };

                            $paymentStatusLabel = match ($payment->status) {
                                'paid' => 'Teljesítve',
                                'pending' => 'Függőben',
                                'failed' => 'Sikertelen',
                                'refunded' => 'Visszatérítve',
                                default => $payment->status,
                            };
                        @endphp

                        <div class="border rounded p-3 mb-3">
                            <p><strong>Összeg:</strong> {{ number_format($payment->amount, 0, ',', ' ') }} Ft</p>
                            <p><strong>Módszer:</strong> {{ $methodLabel }}</p>
                            <p><strong>Státusz:</strong> {{ $paymentStatusLabel }}</p>

                            @if ($payment->status === 'pending')
                                <div class="alert alert-warning mb-3">Fizetési kérelem érkezett ehhez a foglaláshoz.</div>

                                <form action="/payments/{{ $payment->id }}/pay" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Fizetés</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (in_array($reservation->status, ['pending', 'confirmed']))
            <div class="mt-4">
                <form action="/reservations/{{ $reservation->id }}" method="POST" onsubmit="return confirm('Biztosan le szeretnéd mondani a foglalást?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn btn-danger">Foglalás lemondása</button>
                </form>
            </div>
        @endif
    </div>
@endsection
