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
    @livewireStyles
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                {{-- <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a> --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->isLecturer())
                                <li class="nav-item"><a class="nav-link" href="{{ route('lecturer.dashboard') }}">Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('lecturer.classes') }}">Classes</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('lecturer.subjects') }}">Subjects</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('lecturer.exams') }}">Exams</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('lecturer.results') }}">Results</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('student.exams') }}">My Exams</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('student.results') }}">Results</a></li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        {{ __('Profile') }}
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
    @livewireScripts

    {{-- SweetAlert2 for success messages --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success') || session('status') || session('message'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success') ?? session('status') ?? session('message')),
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });

        document.addEventListener('livewire:init', () => {
            if (window.Livewire) {
                Livewire.on('notify', (event) => {
                    const type = event.type || 'success';
                    const message = event.message || '';
                    if (!message) return;

                    Swal.fire({
                        icon: type,
                        title: type.charAt(0).toUpperCase() + type.slice(1),
                        text: message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
            }
        });
    </script>
</body>
</html>
