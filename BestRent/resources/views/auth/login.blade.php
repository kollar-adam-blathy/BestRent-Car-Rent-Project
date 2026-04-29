@extends('layouts.app')

@section('title', 'Bejelentkezés')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4 text-center">Bejelentkezés</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="/login">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email cím</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Jelszó</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Emlékezz rám</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Bejelentkezés</button>
                        </form>

                        <hr>

                        <p class="text-center text-muted">
                            Még nincs fiókod?
                            <a href="/register" class="text-decoration-none">Regisztrálj most</a>
                        </p>
                    </div>
                </div>

                <div class="mt-3 alert alert-info" role="alert">
                    <strong>Teszt adatok:</strong><br>
                        Admin: <code>admin@bestrent.com</code> / <code>password</code><br>
                        Ügyfél: <code>user@bestrent.com</code> / <code>password</code>
                </div>
            </div>
        </div>
    </div>
@endsection
