@extends('layouts.app')

@section('title', 'Admin felület')

@section('content')
    @php
        $activeTab = request('tab', 'cars');
    @endphp

    <div class="container-fluid my-5">
        <h2 class="mb-4">Admin felület</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Összes felhasználó</h6>
                        <h3 class="text-primary">{{ $stats['users'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Autók</h6>
                        <h3 class="text-primary">{{ $stats['cars'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Foglalások</h6>
                        <h3 class="text-primary">{{ $stats['reservations'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Összes bevétel</h6>
                        <h3 class="text-success">{{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }} Ft</h3>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'cars' ? 'active' : '' }}" id="cars-tab" data-bs-toggle="tab" data-bs-target="#cars"
                    type="button" role="tab">
                    <i class="bi bi-car-front"></i> Autók kezelése
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'reservations' ? 'active' : '' }}" id="reservations-tab" data-bs-toggle="tab"
                    data-bs-target="#reservations" type="button" role="tab">
                    <i class="bi bi-calendar-check"></i> Foglalások
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'payments' ? 'active' : '' }}" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments"
                    type="button" role="tab">
                    <i class="bi bi-credit-card"></i> Fizetések
                </button>
            </li>
        </ul>

        <div class="tab-content">
            
            <div class="tab-pane fade {{ $activeTab === 'cars' ? 'show active' : '' }}" id="cars" role="tabpanel">
                <div class="mb-3">
                    <a href="/admin/cars/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Új autó
                    </a>
                </div>
                @if ($cars && $cars->isEmpty())
                    <div class="alert alert-info">Nincs autó regisztrálva.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Márka</th>
                                    <th>Modell</th>
                                    <th>Év</th>
                                    <th>Napidíj</th>
                                    <th>Státusz</th>
                                    <th>Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cars as $car)
                                    <tr>
                                        <td><strong>{{ $car->brand }}</strong></td>
                                        <td>{{ $car->model }}</td>
                                        <td>{{ $car->year }}</td>
                                        <td>{{ number_format($car->daily_price, 0, ',', ' ') }} Ft</td>
                                        <td>
                                            @php
                                                $badge = match ($car->status) {
                                                    'available' => 'success',
                                                    'maintenance' => 'warning',
                                                    'unavailable' => 'danger',
                                                    default => 'light',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ $car->status_label }}</span>
                                        </td>
                                        <td>
                                            <a href="/admin/cars/{{ $car->id }}/edit?from=/admin/dashboard" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete('/admin/cars/{{ $car->id }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Nincs autó.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            
            <div class="tab-pane fade {{ $activeTab === 'reservations' ? 'show active' : '' }}" id="reservations" role="tabpanel">
                @if ($reservations && $reservations->isEmpty())
                    <div class="alert alert-info">Nincs foglalás.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foglalás</th>
                                    <th>Felhasználó</th>
                                    <th>Autó</th>
                                    <th>Kezdés</th>
                                    <th>Vég</th>
                                    <th>Ár</th>
                                    <th>Státusz</th>
                                    <th>Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reservations as $reservation)
                                    <tr>
                                        <td>#{{ $reservation->id }}</td>
                                        <td>{{ $reservation->user?->name ?? 'N/A' }}</td>
                                        <td>{{ $reservation->car?->brand ?? 'N/A' }} {{ $reservation->car?->model ?? '' }}</td>
                                        <td>{{ $reservation->start_date }}</td>
                                        <td>{{ $reservation->end_date }}</td>
                                        <td>{{ number_format($reservation->total_price, 0, ',', ' ') }} Ft</td>
                                        <td>
                                            @php
                                                $badge = match ($reservation->status) {
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'active' => 'primary',
                                                    'cancelled' => 'danger',
                                                    'completed' => 'secondary',
                                                    default => 'light',
                                                };

                                                $statusLabel = match ($reservation->status) {
                                                    'pending' => 'Függőben',
                                                    'confirmed' => 'Megerősítve',
                                                    'active' => 'Aktív bérlés',
                                                    'cancelled' => 'Lemondva',
                                                    'completed' => 'Teljesítve',
                                                    default => $reservation->status,
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            <a href="/admin/reservations/{{ $reservation->id }}/edit"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Nincs foglalás.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $reservations->appends(['tab' => 'reservations'])->links() }}
                    </div>
                @endif
            </div>

            
            <div class="tab-pane fade {{ $activeTab === 'payments' ? 'show active' : '' }}" id="payments" role="tabpanel">
                @if ($payments && $payments->isEmpty())
                    <div class="alert alert-info">Nincs fizetés.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Felhasználó</th>
                                    <th>Foglalás</th>
                                    <th>Autó</th>
                                    <th>Összeg</th>
                                    <th>Módszer</th>
                                    <th>Státusz</th>
                                    <th>Dátum</th>
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
                                        <td>{{ $payment->user?->name ?? 'N/A' }}</td>
                                        <td>#{{ $payment->reservation_id }}</td>
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
                                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
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

    <script>
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(button => {
            button.addEventListener('shown.bs.tab', event => {
                const tabName = event.target.dataset.bsTarget.replace('#', '');
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabName);

                if (tabName !== 'reservations') {
                    url.searchParams.delete('reservations_page');
                }

                window.history.replaceState({}, '', url);
            });
        });

        function confirmDelete(url) {
            if (confirm('Biztos hogy törölni szeretnéd?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Hiba a törlés során.');
                    }
                });
            }
        }
    </script>
@endsection
