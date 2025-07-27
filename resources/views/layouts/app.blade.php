<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
     <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        /* Light Mode */
        body.light {
            background-color: #ffffff;
            color: #000000;
        }

        /* Dark Mode */
        body.dark {
            background-color: #000000;
            color: #ffffff;
        }
    </style>
</head>
<body class="{{ Auth::check() && Auth::user()->display_mode === 'dark' ? 'bg-dark text-light' : 'bg-light text-dark' }}">
   
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest

                            @if (Route::has('about'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('about') }}">{{ __('About') }}</a>
                                </li>
                            @endif
                            @if (Route::has('contact'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('contact') }}">{{ __('Contact Us') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                             @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('rooms.index') }}">{{ __('Rooms') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('bookings.my-bookings') }}">{{ __('My Bookings') }}</a>
                            </li>
                            @if(Auth::user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}" style="color: #3b82f6; font-weight: 600;">{{ __('Admin Panel') }}</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('about') }}">{{ __('About') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact') }}">{{ __('Contact Us') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                            {{ __('Account Settings') }}
                                        </a>
                                        @if(Auth::user()->is_admin)
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header">Admin Panel</h6>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>{{ __('Dashboard') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            <i class="fas fa-users me-2"></i>{{ __('Manage Users') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.rooms.index') }}">
                                            <i class="fas fa-home me-2"></i>{{ __('Manage Rooms') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.payments.index') }}">
                                            <i class="fas fa-credit-card me-2"></i>{{ __('Payment Approvals') }}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        @endif
                                         {{--<a class="dropdown-item" href="{{ route('help') }}">
                                            {{ __('Help & Support') }}
                                        </a> --}}
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
