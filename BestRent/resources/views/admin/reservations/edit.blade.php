@extends('layouts.app')

@section('title', 'Foglalás szerkesztése')

@section('content')
    <div class="container my-5">
        <h2>Foglalás szerkesztése: #{{ $reservation->id }}</h2>
        <hr>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Foglalás adatok</h6>
                        <p><strong>Felhasználó:</strong> {{ $reservation->user?->name ?? 'N/A' }} ({{ $reservation->user?->email }})</p>
                        <p><strong>Autó:</strong> {{ $reservation->car?->brand ?? 'N/A' }} {{ $reservation->car?->model }}</p>
                        <p><strong>Rendszám:</strong> {{ $reservation->car?->plate_number ?? 'N/A' }}</p>
                        <p><strong>Kezdés:</strong> {{ $reservation->start_date }}</p>
                        <p><strong>Vég:</strong> {{ $reservation->end_date }}</p>
                        <p><strong>Ár:</strong> {{ number_format($reservation->total_price, 0, ',', ' ') }} Ft</p>
                    </div>
                </div>
            </div>

            @if ($reservation->payments->count() > 0)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Fizetések</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Összeg</th>
                                        <th>Módszer</th>
                                        <th>Státusz</th>
                                        <th>Dátum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservation->payments as $payment)
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <form action="/admin/reservations/{{ $reservation->id }}" method="POST" class="row">
            @csrf
            @method('PATCH')

            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Státusz</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                    required>
                    <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Függőben
                    </option>
                    <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Megerősítve
                    </option>
                    <option value="active" {{ $reservation->status == 'active' ? 'selected' : '' }}>Aktív bérlés
                    </option>
                    <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Lemondva
                    </option>
                    <option value="completed" {{ $reservation->status == 'completed' ? 'selected' : '' }}>Befejezve
                    </option>
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="pickup_location" class="form-label">Felvétel helye</label>
                <input type="text" class="form-control @error('pickup_location') is-invalid @enderror"
                    id="pickup_location" name="pickup_location" value="{{ $reservation->pickup_location }}">
                @error('pickup_location')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="dropoff_location" class="form-label">Visszáadás helye</label>
                <input type="text" class="form-control @error('dropoff_location') is-invalid @enderror"
                    id="dropoff_location" name="dropoff_location" value="{{ $reservation->dropoff_location }}">
                @error('dropoff_location')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="notes" class="form-label">Megjegyzések</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                    rows="4">{{ $reservation->notes }}</textarea>
                @error('notes')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Mentés
                </button>
                <a href="/admin/dashboard" class="btn btn-outline-secondary">Mégsem</a>
            </div>
        </form>
    </div>
@endsection
