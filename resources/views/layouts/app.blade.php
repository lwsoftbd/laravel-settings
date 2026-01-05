<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS (cdn) -->
    @if((bool) setting('default_layout'))
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <!-- Optional custom CSS -->
    @stack('styles')
</head>
<body>
    <div class="container-fluid bg-light">
        <div id="main menu" class="container-md">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="#">{{ setting('site_name') ?? 'LW Settings' }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ url('/dashboard') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('site.settings') }}">Site Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('site.settings.create') }}">Add New</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('settings.cache.clear.all') }}" method="POST"
                                onsubmit="return confirm('Are you sure? This will clear all settings cache!')">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">
                                    Clear Cache
                                </button>
                            </form>
                        </li>
                        <!-- User Auth -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Hi, {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @auth
                                    <li class="dropdown-item"><a class="nav-link" href="{{ route('site-settings.preference') }}">Preference</a></li>
                                    <li class="dropdown-item">
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="nav-link" type="submit">Logout</button>
                                        </form>
                                    </li>
                                @endauth
                                @guest
                                    <li class="dropdown-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                                @endguest
                            </ul>
                        </li>
                    </ul>

                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>

                </div>
            </nav>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            {{-- ===== Flash Messages ===== --}}
            <div class="container mt-3">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Main content (full width) -->
            <main class="col-12 px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-auto">
        &copy; {{ date('Y') }} LW SOFT BD. All rights reserved.
    </footer>

    <!-- Bootstrap JS (cdn) -->
    @if((bool) setting('default_layout'))
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @endif

    @stack('scripts')
</body>
</html>