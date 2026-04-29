@extends('layouts.app')

@section('title', 'Autók')

@section('content')
    
    <section class="cars-header">
        <div class="container">
            <h1><i class="bi bi-car-front"></i> Autók Böngészése</h1>
            <p>Válassza ki az Ön igényeinek megfelelő járművet<span class="cars-header-count"> • {{ $cars->total() }} autó elérhető</span></p>
        </div>
    </section>

    
    <div class="container">
        
        <div class="filter-section">
            <h3><i class="bi bi-funnel"></i> Szűrés és Keresés</h3>

            <form action="/cars" method="GET">
                <div class="filter-grid">
                    
                    <div class="filter-group">
                        <label for="q">Keresés</label>
                        <input type="text" id="q" name="q" placeholder="Márka, modell, szín..." value="{{ request('q') }}" />
                    </div>

                    
                    <div class="filter-group">
                        <label for="brand">Márka</label>
                        <input type="text" id="brand" name="brand" placeholder="pl. Toyota" value="{{ request('brand') }}" />
                    </div>

                    
                    <div class="filter-group">
                        <label for="model">Modell</label>
                        <input type="text" id="model" name="model" placeholder="pl. Camry" value="{{ request('model') }}" />
                    </div>

                    
                    <div class="filter-group">
                        <label for="color">Szín</label>
                        <input type="text" id="color" name="color" placeholder="pl. Fekete" value="{{ request('color') }}" />
                    </div>

                    
                    <div class="filter-group">
                        <label for="category">Kategória</label>
                        <select id="category" name="category">
                            <option value="">Összes kategória</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="filter-group">
                        <label for="fuel_type">Üzemanyag típusa</label>
                        <select id="fuel_type" name="fuel_type">
                            <option value="">Összes típus</option>
                            @foreach ($fuelTypes as $fuelType)
                                <option value="{{ $fuelType }}" {{ request('fuel_type') == $fuelType ? 'selected' : '' }}>
                                    {{ $fuelType }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="filter-group">
                        <label for="transmission">Váltó</label>
                        <select id="transmission" name="transmission">
                            <option value="">Összes váltó</option>
                            @foreach ($transmissions as $transmission)
                                <option value="{{ $transmission }}" {{ request('transmission') == $transmission ? 'selected' : '' }}>
                                    {{ $transmission }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="filter-group">
                        <label for="seats">Ülések száma</label>
                        <input type="number" id="seats" name="seats" placeholder="pl. 5" min="2" max="9" value="{{ request('seats') }}" />
                    </div>

                    
                    <div class="filter-group">
                        <label for="year">Évjárat</label>
                        <input type="number" id="year" name="year" placeholder="pl. 2023" min="1980" max="2100" value="{{ request('year') }}" />
                    </div>

                    
                    <div class="filter-group">
                        <label for="min_price">Min. ár (Ft)</label>
                        <input type="number" id="min_price" name="min_price" placeholder="0" min="0" value="{{ request('min_price') }}" />
                    </div>

                    
                    <div class="filter-group">
                        <label for="max_price">Max. ár (Ft)</label>
                        <input type="number" id="max_price" name="max_price" placeholder="100000" min="0" value="{{ request('max_price') }}" />
                    </div>
                </div>

                
                <div class="filter-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Szűrés Alkalmazása
                    </button>
                    <a href="/cars" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Szűrők Törlése
                    </a>
                </div>
            </form>
        </div>

        
        @if ($cars->isEmpty())
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h3 class="no-results-title">Nincs találat</h3>
                <p class="no-results-text">Sajnos nem találtunk a szűrőknek megfelelő autót. Próbálja meg módosítani a keresési feltételeket!</p>
                <a href="/cars" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left"></i> Vissza az összes autóhoz
                </a>
            </div>
        @else
            <div class="cars-grid">
                @foreach ($cars as $car)
                    <div class="card car-card">
                        
                        <div class="car-card-image">
                            @if ($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" />
                            @else
                                <div class="car-image-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            <div class="car-card-badges">
                                <span class="car-badge">{{ $car->category }}</span>
                                @if ($car->status === 'available')
                                    <span class="car-badge secondary">Elérhető</span>
                                @elseif ($car->status === 'maintenance')
                                    <span class="car-badge accent">Karbantartás</span>
                                @else
                                    <span class="car-badge">{{ $car->status_label }}</span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="card-body">
                            <h3 class="car-card-title">
                                {{ $car->brand }} {{ $car->model }}
                            </h3>
                            <p class="car-card-color">{{ $car->color }}</p>

                            
                            <div class="car-card-specs">
                                <div class="car-spec">
                                    <div class="car-spec-icon"><i class="bi bi-calendar"></i></div>
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
                                        @if ($car->fuel_type === 'Benzin')
                                            <i class="bi bi-droplet-fill fuel-icon-benzin"></i>
                                        @elseif ($car->fuel_type === 'Dízel')
                                            <i class="bi bi-droplet-fill fuel-icon-dizel"></i>
                                        @else
                                            <i class="bi bi-droplet-fill fuel-icon-hibrid"></i>
                                        @endif
                                    </div>
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
                                            <a href="/admin/cars/{{ $car->id }}/edit?from=/cars" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i> Szerkesztés
                                            </a>
                                        </div>
                                    @else
                                        <a href="/cars/{{ $car->id }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> Részletek
                                        </a>
                                    @endif
                                @else
                                    <a href="/cars/{{ $car->id }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye"></i> Részletek
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $cars->links() }}
            </div>
        @endif
    </div>
@endsection