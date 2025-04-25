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

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* This prevents scroll */
        }

        body {
            display: flex;
            flex-direction: column;
        }

        #app {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            overflow: auto; /* If you want scroll only within main when needed */
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <div id="app" class="flex-grow-1">

        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @auth
                        @if (Auth::user()->role == 2)
                            <div class="ms-5 d-flex align-items-center gap-2">
                                @php $workStarted = Auth::user()->work_started === 'yes'; @endphp
                                @if (!$workStarted)
                                    <form id="startWorkForm" action="{{ route('start.work') }}" method="POST">
                                        @csrf
                                        <button type="submit" id="startBtn" class="btn btn-success">Start Work</button>
                                    </form>
                                @endif
                                <a href="{{ url('user/report') }}" class="btn btn-outline-primary">Report</a>
                                <a href="#" class="btn btn-outline-primary">Payout</a>
                                <a href="#" class="btn btn-outline-secondary">Softwares</a>
                            </div>
                        @endif
                    @endauth
                    <ul class="navbar-nav ms-auto">
                        @guest
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
   
        {{-- Your navbar and main content --}}
        <main class="py-2">
            @yield('content')
        </main>
  

    {{-- Footer placed outside the main div --}}
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container d-flex justify-content-between align-items-center flex-column flex-md-row text-center text-md-start">
            <!-- Left side: Terms and Conditions & Privacy Policy links -->
            <div class="d-flex flex-column flex-md-row">
                <a href="{{ route('terms') }}" class="text-decoration-none text-warning me-md-3 mb-2 mb-md-0">Terms and Conditions</a>
                <a href="{{ route('privacy') }}" class="text-decoration-none text-warning">Privacy Policy</a>
            </div>
    
            <!-- Right side: Copyright Notice -->
            <p class="mb-0 mt-3 mt-md-0">&copy; 2022 {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
    
</div>
</body>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutLink = document.querySelector('a[href="{{ route('logout') }}"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            }
        });
    </script>

</html>
