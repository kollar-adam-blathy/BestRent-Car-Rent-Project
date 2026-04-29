<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        
        <a class="navbar-brand fw-bold" href="/">
            <img src="{{ asset('images/best-rent-logo.png') }}" alt="Best-Rent" class="navbar-brand-logo">
            <span>Best-Rent</span>
        </a>

        <div class="navbar-end-controls">
            @auth
                <div class="dropdown navbar-user-dropdown">
                    <a class="nav-link dropdown-toggle nav-link-user" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="/client/profile"><i class="bi bi-person"></i> Profil</a></li>
                        @if (!auth()->user()->is_admin)
                            <li><a class="dropdown-item" href="/client/dashboard"><i class="bi bi-calendar-check"></i> Foglalásaim</a></li>
                        @endif
                        @if (auth()->user()->is_admin)
                            <li><a class="dropdown-item" href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Admin felület</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="/logout" class="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Kijelentkezés
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <ul class="navbar-nav mx-auto main-nav">
                <li class="nav-item">
                    <a class="nav-link nav-link-main {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="bi bi-house"></i> Kezdőlap
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-main {{ request()->is('cars*') ? 'active' : '' }}" href="/cars">
                        <i class="bi bi-car-front"></i> Autók Megtekintése
                    </a>
                </li>
            </ul>

            @guest
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    <li class="nav-item">
                        <a class="btn btn-outline-primary" href="/login">
                            <i class="bi bi-box-arrow-in-right"></i> Bejelentkezés
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary" href="/register">
                            <i class="bi bi-person-plus"></i> Regisztráció
                        </a>
                    </li>
                </ul>
            @endguest
        </div>
    </div>
</nav>
