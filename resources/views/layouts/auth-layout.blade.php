<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Point Of Sale' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div x-data="{ open: false }" class="min-h-screen flex bg-gray-50">

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Overlay (untuk mode mobile) -->
        <div x-show="open" x-transition.opacity @click="open = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"></div>

        <!-- Konten utama -->
        <div class="flex flex-col flex-1 min-h-screen transition-all duration-300 ease-in-out">

            <!-- Navbar -->
            <nav class="bg-white shadow-md">
                <div class="flex justify-between items-center px-4 md:px-6 h-16">
                    <div class="flex items-center">
                        <!-- Tombol toggle sidebar (mobile) -->
                        <button @click="open = !open"
                            class="text-gray-600 hover:text-gray-800 focus:outline-none md:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="ml-3 text-lg font-semibold text-gray-800">{{ $title ?? 'Point of Sale' }}</h1>
                    </div>

                    <!-- Dropdown Profil -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center cursor-pointer space-x-2 bg-gray-50 hover:bg-gray-100 px-3 py-2 rounded-md transition">
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-30">
                            <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" onsubmit="return confirm('Apakah anda yakin ingin keluar?')"
                                    class="block cursor-pointer w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Konten -->
            <main class="flex-1 p-2.5 md:p-4 h-full">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('script')
</body>

</html>
