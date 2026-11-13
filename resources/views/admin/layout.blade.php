<!DOCTYPE html>
<html lang="tk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EmläkGO {{ auth()->user() && auth()->user()->isAdmin() ? 'Admin' : 'Paneli' }} - @yield('title', 'Panel')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navigation (Goýy ýaşyl / Dark emerald) -->
    <nav class="bg-gray-900 border-b border-emerald-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold tracking-tight flex items-center gap-2">
                        <span>🏠</span>
                        <span>Emläk<strong class="text-emerald-300">GO</strong> {{ auth()->user()->isAdmin() ? 'Admin' : 'Paneli' }}</span>
                    </a>
                    <div class="hidden md:flex space-x-3">
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-100' }}">
                            Baş sahypa
                        </a>
                        <a href="{{ route('admin.properties.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition {{ request()->routeIs('admin.properties.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-100' }}">
                            @if(auth()->user()->isAdmin())
                                Emläkler
                            @else
                                Meniň Emläklerim
                            @endif
                        </a>

                        <a href="{{ route('admin.inquiries.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition {{ request()->routeIs('admin.inquiries.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-100' }}">
                            Soraglar
                        </a>

                        <a href="{{ route('admin.comments.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition {{ request()->routeIs('admin.comments.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-100' }}">
                            Teswirler
                        </a>

                        <a href="{{ route('admin.favorites.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition {{ request()->routeIs('admin.favorites.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-100' }}">
                            Halananlar
                        </a>

                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition {{ request()->routeIs('admin.users.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-100' }}">
                            Ulanyjylar
                        </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-emerald-100">{{ auth()->user()->name }}</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold {{ auth()->user()->isAdmin() ? 'bg-emerald-950 text-emerald-300 border border-emerald-600' : 'bg-teal-900 text-teal-200' }}">
                            {{ auth()->user()->isAdmin() ? 'Admin' : 'Ulanyjy' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition text-emerald-100">
                            Çykmak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl mb-4 font-medium flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4 font-medium flex items-center gap-2">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <footer class="text-center text-sm text-gray-500 py-6 mt-8">
        &copy; {{ date('Y') }} EmläkGO — Ähli hukuklar goralýar.
    </footer>

</body>
</html>
