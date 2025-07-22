<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-cream-50">
    <!-- Fixed Navbar -->
    <nav class="fixed top-0 w-full bg-white shadow-sm border-b border-cream-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Navigation -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="text-2xl">📚</span>
                        <span class="text-xl font-semibold text-primary-800">Perpustakaan</span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8 ml-10">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700">Beranda</a>
                        <a href="{{ route('buku.index') }}" class="text-sm font-medium text-gray-700">Koleksi Buku</a>
                        @auth
                            <a href="{{ route('perpustakaan-saya') }}"
                                class="text-sm font-medium text-gray-700">Perpustakaan Saya</a>
                            <a href="{{ route('rekomendasi') }}" class="text-sm font-medium text-gray-700">Rekomendasi</a>
                        @endauth
                    </div>
                </div>

                <!-- Search -->
                <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end max-w-lg">
                    <div class="w-full">
                        <form action="{{ route('buku.index') }}" method="GET" class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari buku, penulis..."
                                class="w-full pl-10 pr-4 py-2 border border-cream-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary text-sm">Daftar</a>
                    @else
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button type="button" class="flex items-center space-x-2 text-sm" id="user-menu-button">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-primary-700 font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            </button>

                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-cream-200 hidden"
                                id="user-menu">
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700">Profil</a>
                                    <a href="{{ route('perpustakaan-saya') }}"
                                        class="block px-4 py-2 text-sm text-gray-700">Perpustakaan Saya</a>
                                    <hr class="my-1 border-cream-200">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-primary-500 to-primary-600 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-sm text-white">&copy; {{ date('Y') }} Perpustakaan Digital. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Simple dropdown toggle
        document.getElementById('user-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-menu');

            if (button && menu && !button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
