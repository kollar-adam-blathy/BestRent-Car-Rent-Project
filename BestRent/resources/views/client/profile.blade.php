@extends('layouts.app')

@section('title', 'Profilom')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2>Profilom</h2>
            @if ($user->is_admin)
                <a href="/admin/dashboard" class="btn btn-outline-primary">Admin felület</a>
            @else
                <a href="/client/dashboard" class="btn btn-outline-primary">Foglalásaim</a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Személyes adatok</h5>

                        <form method="POST" action="/client/profile" class="mb-4">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="name" class="form-label">Teljes név</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email cím</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Telefonszám</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+36201234567" required>
                                <div class="form-text">Elfogadott formátum: +36201234567 vagy 06201234567</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Új jelszó</label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Új jelszó megerősítése</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            </div>

                            <p class="mb-3"><strong>Szerepkör:</strong> {{ $user->is_admin ? 'Admin' : 'Ügyfél' }}</p>
                            <p class="mb-3"><strong>Regisztráció:</strong> {{ $user->created_at?->format('Y-m-d H:i') }}</p>

                            <button type="submit" class="btn btn-primary">Adatok mentése</button>
                        </form>

                        <form method="POST" action="/client/profile" onsubmit="return confirm('Biztosan törölni szeretnéd a fiókodat?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Fiók törlése</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Összegzés</h5>

                        @if ($user->is_admin)
                            <p class="mb-2"><strong>Autók száma:</strong> {{ $stats['cars'] }}</p>
                            <p class="mb-2"><strong>Foglalások száma:</strong> {{ $stats['reservations'] }}</p>
                            <p class="mb-2"><strong>Fizetések száma:</strong> {{ $stats['payments'] }}</p>
                            <p class="mb-0"><strong>Össz bevétel:</strong> {{ number_format($stats['revenue'], 0, ',', ' ') }} Ft</p>
                        @else
                            <p class="mb-2"><strong>Foglalásaim száma:</strong> {{ $stats['reservations'] }}</p>
                            <p class="mb-2"><strong>Aktív foglalásaim:</strong> {{ $stats['active_reservations'] }}</p>
                            <p class="mb-2"><strong>Fizetéseim száma:</strong> {{ $stats['payments'] }}</p>
                            <p class="mb-0"><strong>Összes kifizetésem:</strong> {{ number_format($stats['paid_total'], 0, ',', ' ') }} Ft</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
