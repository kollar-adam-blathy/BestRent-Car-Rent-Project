@extends('layouts.app')

@section('title', 'Kezdőlap')

@section('content')

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Béreljen Autót Egyszerűen</h1>
                <p>Felfedezze a legmodernebb autóbérlési szolgáltatást. Gyors foglalás, kedvező árak, megbízható járművek.</p>
                <div class="hero-buttons">
                    <a href="/cars" class="btn btn-hero-primary">
                        <i class="bi bi-car-front"></i> Autók Böngészése
                    </a>
                    @guest
                        <a href="/register" class="btn btn-hero-secondary">
                            <i class="bi bi-person-plus"></i> Regisztráció
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <section class="featured-section">
        <div class="container">
            <div class="section-header">
                <h2>Kiemelkedő Autók</h2>
                <p>Válassza ki az Ön igényeinek megfelelő járművet a legmodernebb autók közül</p>
            </div>

            @php
                $featuredCars = \App\Models\Car::where('status', 'available')
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
            @endphp

            @if($featuredCars->count() > 0)
                <div class="featured-grid">
                    @foreach($featuredCars as $car)
                        <div class="card car-card">
                            
                            <div class="car-card-image">
                                @if($car->image_url)
                                    <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" />
                                @else
                                    <div class="home-car-image-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                                <div class="car-card-badges">
                                    <span class="car-badge">{{ $car->category }}</span>
                                </div>
                            </div>

                            
                            <div class="card-body">
                                <h3 class="home-car-title">
                                    {{ $car->brand }} {{ $car->model }}
                                </h3>
                                <p class="home-car-color">{{ $car->color }}</p>

                                
                                <div class="car-card-specs">
                                    <div class="car-spec">
                                        <div class="car-spec-icon"><i class="bi bi-calendar-event"></i></div>
                                        <div class="car-spec-label">Év</div>
                                        <div class="car-spec-value">{{ $car->year }}</div>
                                    </div>
                                    <div class="car-spec">
                                        <div class="car-spec-icon"><i class="bi bi-person"></i></div>
                                        <div class="car-spec-label">Ülések</div>
                                        <div class="car-spec-value">{{ $car->seats }}</div>
                                    </div>
                                    <div class="car-spec">
                                        <div class="car-spec-icon">
                                            @if($car->fuel_type === 'Benzin')
                                                <i class="bi bi-droplet-fill fuel-icon-benzin"></i>
                                            @elseif($car->fuel_type === 'Dízel')
                                                <i class="bi bi-droplet-fill fuel-icon-dizel"></i>
                                            @else
                                                <i class="bi bi-droplet-fill fuel-icon-hibrid"></i>
                                            @endif
                                        </div>
                                        <div class="car-spec-label">Üzemanyag</div>
                                        <div class="car-spec-value car-spec-fuel">{{ $car->fuel_type }}</div>
                                    </div>
                                </div>

                                
                                <div class="car-card-price">
                                    <div>
                                        <div class="price-label">Napi ár</div>
                                        <div class="price-amount">{{ number_format($car->daily_price, 0) }} Ft</div>
                                    </div>
                                    @auth
                                        @if (auth()->user()->is_admin)
                                            <div class="d-flex flex-column gap-2 align-items-stretch">
                                                <a href="/cars/{{ $car->id }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i> Megtekintés
                                                </a>
                                                <a href="/admin/cars/{{ $car->id }}/edit?from=/" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-pencil"></i> Szerkesztés
                                                </a>
                                            </div>
                                        @else
                                            <a href="/cars/{{ $car->id }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i> Megtekintés
                                            </a>
                                        @endif
                                    @else
                                        <a href="/cars/{{ $car->id }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> Megtekintés
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="home-featured-footer">
                    <a href="/cars" class="btn btn-outline-primary home-featured-all-btn">
                        <i class="bi bi-arrow-right"></i> Összes Autó Megtekintése
                    </a>
                </div>
            @else
                <div class="home-no-cars-box">
                    <i class="bi bi-car-front home-no-cars-icon"></i>
                    <p class="home-no-cars-text">Jelenleg nincsenek elérhető autók. Térjen vissza később!</p>
                </div>
            @endif
        </div>
    </section>

    <section class="why-choose-section">
        <div class="container">
            <div class="section-header">
                <h2>Miért Minket Válasszon?</h2>
                <p>Minden, amit szüksége van egy kiváló autóbérlési élményhez</p>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-item-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3>Megbízható Autók</h3>
                    <p>Csak biztonságos, jól karbantartott és magas biztosítással rendelkező járművek.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-item-icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <h3>Kedvező Árak</h3>
                    <p>Piaci versenytársiainál alacsonyabb árak és rugalmas fizetési lehetőségek.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-item-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h3>Gyors Folyamat</h3>
                    <p>Pár perc alatt megtudja foglalni autóját online, egyszerű és gyors folyamattal.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-item-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h3>24/7 Ügyfélsegítség</h3>
                    <p>Szakképzett csapatunk mindig rendelkezésre áll felmerülő kérdéseihez.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-item-icon">
                        <i class="bi bi-pin-map-fill"></i>
                    </div>
                    <h3>Rugalmas Helyek</h3>
                    <p>Autópályán több helyen lehet felvenni és leadni az autókat.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-item-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h3>Teljes Biztosítás</h3>
                    <p>Minden autó teljes biztosítás alatt áll, Ön pedig biztonságban van.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Rólunk</h2>
                    <p>A Best-Rent az elmúlt 15 évben hazánk vezetőjévé vált az autóbérlési iparban. Egyszerű misszió: magas minőségű autóbérlési szolgáltatás, amit mindenki megengedheti magának.</p>
                    
                    <p>Több mint 500 autóval rendelkezünk, és több mint 50,000 elégedett ügyfél bizik benünk. Az autóink legújabb technológiával és biztonsági eszközökkel vannak felszerelve.</p>

                    <ul class="about-features">
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>15 év tapasztalat az autóbérlési iparban</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>500+ járműpark a legjobb márkáktól</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>50,000+ elégedett ügyfél</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Biztosítás és 24/7 támogatás</span>
                        </li>
                    </ul>

                    <a href="/cars" class="btn btn-primary mt-4">
                        <i class="bi bi-arrow-right"></i> Kezdje el Most
                    </a>
                </div>

                <div class="about-image">
                    <img src="{{ asset('images/best-rent-about.png') }}" alt="Rólunk" />
                </div>
            </div>
        </div>
    </section>
    
    <section class="cta-section">
        <div class="container">
            <h2>Kész a Foglalásra?</h2>
            <p>Válassza ki azt az autót, amelyet szeretne, és kezdje el az Ön utazását még ma!</p>
            <div class="cta-buttons">
                <a href="/cars" class="btn btn-hero-primary cta-primary-btn">
                    <i class="bi bi-car-front"></i> Autók Böngészése
                </a>
                @guest
                    <a href="/register" class="btn btn-hero-secondary">
                        <i class="bi bi-person-plus"></i> Regisztráció
                    </a>
                @endguest
            </div>
        </div>
    </section>
@endsection