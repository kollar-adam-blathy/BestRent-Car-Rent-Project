@extends('layouts.app')

@section('title', 'Autó foglalása')

@section('css')
    <style>
        .location-picker {
            position: relative;
        }

        .location-picker-menu {
            position: absolute;
            top: calc(100% + 0.4rem);
            left: 0;
            right: 0;
            max-height: 230px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.65rem;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
            z-index: 30;
            padding: 0.35rem;
        }

        .location-picker-item {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
            padding: 0.55rem 0.7rem;
            border-radius: 0.45rem;
            color: #212529;
            font-size: 0.95rem;
        }

        .location-picker-item:hover,
        .location-picker-item.is-active {
            background: #f1f5f9;
        }

        .location-picker-item.is-empty {
            color: #6c757d;
            cursor: default;
        }
    </style>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        @if ($car->image_url)
                            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="img-fluid rounded mb-3 image-cover-260">
                        @endif
                        <h3>{{ $car->brand }} {{ $car->model }}</h3>
                        <p class="text-muted mb-2">{{ $car->category }} • {{ $car->year }}</p>
                        <p class="mb-1"><strong>Napidíj:</strong> {{ number_format($car->daily_price, 0, ',', ' ') }} Ft</p>
                        <p class="mb-1"><strong>Üzemanyag:</strong> {{ $car->fuel_type ?: '-' }}</p>
                        <p class="mb-0"><strong>Váltó:</strong> {{ $car->transmission ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-4">Foglalási adatok</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="/reservations" id="reservation-form" class="row g-3">
                            @csrf
                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            <input type="hidden" id="daily_price" value="{{ $car->daily_price }}">
                            @php($reservationLocations = config('reservation.locations', []))

                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Kezdés dátuma</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" min="{{ now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Befejezés dátuma</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" min="{{ now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="pickup_location" class="form-label">Felvétel helye</label>
                                <div class="location-picker" data-location-picker>
                                    <input type="text" class="form-control @error('pickup_location') is-invalid @enderror" id="pickup_location" name="pickup_location" value="{{ old('pickup_location') }}" placeholder="Válassz helyszínt" required autocomplete="off">
                                    <div class="location-picker-menu d-none" id="pickup_location_menu"></div>
                                </div>
                                <small class="text-muted">Kezdd el beírni a helyszín nevét, és válassz a listából.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="dropoff_location" class="form-label">Leadás helye</label>
                                <div class="location-picker" data-location-picker>
                                    <input type="text" class="form-control @error('dropoff_location') is-invalid @enderror" id="dropoff_location" name="dropoff_location" value="{{ old('dropoff_location') }}" placeholder="Válassz helyszínt" required autocomplete="off">
                                    <div class="location-picker-menu d-none" id="dropoff_location_menu"></div>
                                </div>
                                <small class="text-muted">Kezdd el beírni a helyszín nevét, és válassz a listából.</small>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Megjegyzés</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Opcionális megjegyzés a foglaláshoz">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-calendar-check"></i> Foglalás elküldése
                                </button>
                                <a href="/cars/{{ $car->id }}" class="btn btn-outline-secondary">Vissza</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-lg-3">
                <div class="reserve-summary-card">
                    <h5 class="reserve-summary-title">Foglalás összegző</h5>
                    <div class="reserve-summary-car">
                        <i class="bi bi-car-front"></i>
                        {{ $car->brand }} {{ $car->model }}
                    </div>
                    <hr class="reserve-summary-divider">
                    <div class="reserve-summary-row">
                        <span>Napidíj</span>
                        <span class="reserve-summary-value">{{ number_format($car->daily_price, 0, ',', ' ') }} Ft</span>
                    </div>
                    <div class="reserve-summary-row">
                        <span>Napok száma</span>
                        <span class="reserve-summary-value" id="days_count">—</span>
                    </div>
                    <hr class="reserve-summary-divider">
                    <div class="reserve-summary-total">
                        <span>Végösszeg</span>
                        <span id="total_price">—</span>
                    </div>
                    <p class="reserve-summary-note">
                        <i class="bi bi-shield-check"></i> Teljes biztosítás beleszámítva
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const reservationLocations = @json($reservationLocations);
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const dailyPrice = parseFloat(document.getElementById('daily_price').value);
        const daysCount = document.getElementById('days_count');
        const totalPrice = document.getElementById('total_price');

        function initLocationPicker(inputId, menuId) {
            const input = document.getElementById(inputId);
            const menu = document.getElementById(menuId);

            if (!input || !menu) {
                return;
            }

            let activeIndex = -1;

            function closeMenu() {
                menu.classList.add('d-none');
                activeIndex = -1;
            }

            function highlightItem(items) {
                items.forEach((item, index) => {
                    item.classList.toggle('is-active', index === activeIndex);
                });
            }

            function selectValue(value) {
                input.value = value;
                closeMenu();
                input.dispatchEvent(new Event('change'));
            }

            function renderOptions(keyword) {
                const term = keyword.trim().toLocaleLowerCase('hu-HU');
                const matches = reservationLocations
                    .filter((location) => location.toLocaleLowerCase('hu-HU').includes(term))
                    .slice(0, 10);

                menu.innerHTML = '';
                activeIndex = -1;

                if (matches.length === 0) {
                    const emptyItem = document.createElement('button');
                    emptyItem.type = 'button';
                    emptyItem.className = 'location-picker-item is-empty';
                    emptyItem.textContent = 'Nincs találat';
                    emptyItem.disabled = true;
                    menu.appendChild(emptyItem);
                    menu.classList.remove('d-none');
                    return;
                }

                matches.forEach((location) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'location-picker-item';
                    button.textContent = location;

                    button.addEventListener('mousedown', (event) => {
                        event.preventDefault();
                        selectValue(location);
                    });

                    menu.appendChild(button);
                });

                menu.classList.remove('d-none');
            }

            input.addEventListener('focus', () => renderOptions(input.value));
            input.addEventListener('input', () => renderOptions(input.value));

            input.addEventListener('keydown', (event) => {
                const items = [...menu.querySelectorAll('.location-picker-item:not(.is-empty)')];

                if (menu.classList.contains('d-none') && (event.key === 'ArrowDown' || event.key === 'ArrowUp')) {
                    renderOptions(input.value);
                    return;
                }

                if (!items.length) {
                    return;
                }

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    activeIndex = (activeIndex + 1) % items.length;
                    highlightItem(items);
                    return;
                }

                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    activeIndex = activeIndex <= 0 ? items.length - 1 : activeIndex - 1;
                    highlightItem(items);
                    return;
                }

                if (event.key === 'Enter' && activeIndex >= 0 && items[activeIndex]) {
                    event.preventDefault();
                    selectValue(items[activeIndex].textContent.trim());
                    return;
                }

                if (event.key === 'Escape') {
                    closeMenu();
                }
            });

            document.addEventListener('click', (event) => {
                if (!menu.contains(event.target) && event.target !== input) {
                    closeMenu();
                }
            });
        }

        function formatPrice(value) {
            return new Intl.NumberFormat('hu-HU').format(value) + ' Ft';
        }

        function calculateReservation() {
            const startValue = startDateInput.value;
            const endValue = endDateInput.value;

            if (!startValue || !endValue) {
                daysCount.textContent = '—';
                totalPrice.textContent = '—';
                return;
            }

            const start = new Date(startValue);
            const end = new Date(endValue);
            const diff = end.getTime() - start.getTime();
            const dayCount = Math.floor(diff / (1000 * 60 * 60 * 24)) + 1;

            if (dayCount <= 0) {
                daysCount.textContent = '—';
                totalPrice.textContent = '—';
                return;
            }

            daysCount.textContent = dayCount + ' nap';
            totalPrice.textContent = formatPrice(dayCount * dailyPrice);
        }

        startDateInput.addEventListener('change', calculateReservation);
        endDateInput.addEventListener('change', calculateReservation);

        initLocationPicker('pickup_location', 'pickup_location_menu');
        initLocationPicker('dropoff_location', 'dropoff_location_menu');
        calculateReservation();
    </script>
@endsection
