@extends('layouts.app')

@section('title', $car->brand . ' ' . $car->model)

@section('content')
    
    <section class="car-detail-hero">
        <div class="container">
            <div class="car-detail-header">
                <a href="/cars" class="btn btn-outline-primary btn-sm mb-3">
                    <i class="bi bi-chevron-left"></i> Vissza az autókhoz
                </a>
                <h1>{{ $car->brand }} {{ $car->model }}</h1>
                <p class="lead text-muted">{{ $car->color }} • {{ $car->year }} • {{ $car->category }}</p>
            </div>
        </div>
    </section>

    
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-8">
                
                <div class="car-detail-image-wrap">
                    @if ($car->image_url)
                        <img src="{{ $car->image_url }}" class="img-fluid rounded-lg shadow-lg w-100 car-detail-image" alt="{{ $car->brand }} {{ $car->model }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-lg shadow-md car-detail-image-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>

                
                <div class="car-specs-detail">
                    <div class="spec-item">
                        <div class="spec-icon"><i class="bi bi-calendar-event"></i></div>
                        <div class="spec-label">Évjárat</div>
                        <div class="spec-value">{{ $car->year }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-icon"><i class="bi bi-person"></i></div>
                        <div class="spec-label">Ülések</div>
                        <div class="spec-value">{{ $car->seats }} fő</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-icon">
                            @if ($car->fuel_type === 'Benzin')
                                <i class="bi bi-droplet-fill fuel-icon-benzin"></i>
                            @elseif ($car->fuel_type === 'Dízel')
                                <i class="bi bi-droplet-fill fuel-icon-dizel"></i>
                            @elseif ($car->fuel_type === 'Hibrid')
                                <i class="bi bi-droplet-fill fuel-icon-hibrid"></i>
                            @else
                                <i class="bi bi-lightning-charge-fill fuel-icon-elektromos"></i>
                            @endif
                        </div>
                        <div class="spec-label">Üzemanyag</div>
                        <div class="spec-value">{{ $car->fuel_type }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-icon"><i class="bi bi-gear-wide"></i></div>
                        <div class="spec-label">Váltó</div>
                        <div class="spec-value">{{ $car->transmission }}</div>
                    </div>
                </div>

                
                @if ($car->color || $car->plate_number || $car->category)
                    <div class="car-features-section">
                        <h3 class="car-features-title">Autó Jellemzői</h3>
                        <div class="car-features">
                            @if ($car->color)
                                <div class="feature">
                                    <div class="feature-icon"><i class="bi bi-palette"></i></div>
                                    <div class="feature-text">
                                        <div class="feature-label">Szín</div>
                                        <div class="feature-value">{{ $car->color }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($car->category)
                                <div class="feature">
                                    <div class="feature-icon"><i class="bi bi-tag"></i></div>
                                    <div class="feature-text">
                                        <div class="feature-label">Kategória</div>
                                        <div class="feature-value">{{ $car->category }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($car->plate_number)
                                <div class="feature">
                                    <div class="feature-icon"><i class="bi bi-shield"></i></div>
                                    <div class="feature-text">
                                        <div class="feature-label">Rendszám</div>
                                        <div class="feature-value">{{ $car->plate_number }}</div>
                                    </div>
                                </div>
                            @endif

                            <div class="feature">
                                <div class="feature-icon"><i class="bi bi-check-circle"></i></div>
                                <div class="feature-text">
                                    <div class="feature-label">Állapot</div>
                                    <div class="feature-value">
                                        @if ($car->status === 'available')
                                            <span class="status-text-available">Elérhető</span>
                                        @else
                                            <span class="status-text-unavailable">{{ $car->status_label }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                
                @if ($car->description)
                    <div class="car-description">
                        <h3><i class="bi bi-info-circle"></i> Leírás</h3>
                        <p>{{ $car->description }}</p>
                    </div>
                @endif
            </div>

            
            <div class="col-lg-4">
                <div class="booking-section">
                    
                    <div class="price-display">
                        <div class="price-amount">{{ number_format($car->daily_price, 0) }} Ft</div>
                        <div class="price-label">/ nap</div>
                    </div>

                    
                    @if ($car->status === 'available')
                        <span class="status-badge status-available">
                            <i class="bi bi-check-circle"></i> Elérhető
                        </span>
                    @else
                        <span class="status-badge status-unavailable">
                            <i class="bi bi-x-circle"></i> Nem elérhető
                        </span>
                    @endif

                    
                    @auth
                        @if (!auth()->user()->is_admin)
                            @if ($car->status === 'available')
                                <a href="/client/reserve?car_id={{ $car->id }}" class="btn btn-primary w-100 booking-primary-action">
                                    <i class="bi bi-calendar-check"></i> Most foglalok
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 booking-primary-action" disabled>
                                    <i class="bi bi-x-circle"></i> Nem elérhető
                                </button>
                            @endif


                        @endif
                    @else
                        <div class="booking-login-note">
                            <p class="booking-login-text">
                                <strong>Foglaláshoz bejelentkezés szükséges</strong>
                            </p>
                            <a href="/login" class="btn btn-primary w-100 booking-login-btn">
                                <i class="bi bi-box-arrow-in-right"></i> Bejelentkezés
                            </a>
                            <a href="/register" class="btn btn-outline-primary w-100">
                                <i class="bi bi-person-plus"></i> Regisztráció
                            </a>
                        </div>
                    @endauth

                    
                    <div class="booking-marketing">
                        <div class="booking-marketing-item">
                            <i class="bi bi-shield-check"></i> Teljes biztosítás
                        </div>
                        <div class="booking-marketing-item">
                            <i class="bi bi-lightning"></i> Gyors foglalás
                        </div>
                        <div class="booking-marketing-item">
                            <i class="bi bi-telephone"></i> 24/7 support
                        </div>
                        <div class="booking-marketing-item">
                            <i class="bi bi-geo-alt"></i> Több felvételi hely
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection