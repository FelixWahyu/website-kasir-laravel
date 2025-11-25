<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($settings['app_logo']) && $settings['app_logo'])
        <link rel="icon" href="{{ Storage::url($settings['app_logo']) }}" type="image/png">
    @endif
    <title>{{ $title ?? 'Sistem Point Of Sale' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div x-data="{ open: false }" class="min-h-screen flex bg-gray-50">

        @include('layouts.sidebar')

        <div x-show="open" x-transition.opacity @click="open = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"></div>

        <div class="flex flex-col flex-1 min-h-screen transition-all duration-300 ease-in-out">

            <nav class="bg-white shadow-md">
                <div class="flex justify-between items-center px-4 md:px-6 h-16">
                    <div class="flex items-center">
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

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-30">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Edit Profil
                            </a>
                            <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="openLogoutModal();"
                                    class="block cursor-pointer w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="p-2.5 md:p-4 h-full">
                @yield('content')
            </main>
        </div>
    </div>
    <div id="logoutModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-300">

        <div id="logoutModalBox"
            class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-sm scale-90 opacity-0 transition-all duration-300">

            <h2 class="text-lg font-semibold text-gray-800 mb-3">Konfirmasi Logout</h2>

            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari akun?</p>

            <div class="flex justify-end space-x-2">
                <button id="cancelLogout"
                    class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 cursor-pointer hover:bg-gray-300 transition">
                    Batal
                </button>

                <button id="confirmLogout"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white cursor-pointer hover:bg-red-700 transition">
                    Logout
                </button>
            </div>

        </div>
    </div>
    <script>
        let logoutForm = document.getElementById('logoutForm');
        const logoutModal = document.getElementById('logoutModal');
        const logoutModalBox = document.getElementById('logoutModalBox');

        function openLogoutModal() {
            logoutModal.classList.remove("hidden");
            logoutModal.classList.add("flex");

            setTimeout(() => {
                logoutModalBox.classList.remove("scale-90", "opacity-0");
                logoutModalBox.classList.add("scale-100", "opacity-100");
            }, 10);
        }

        function closeLogoutModal() {
            logoutModalBox.classList.add("scale-90", "opacity-0");
            logoutModalBox.classList.remove("scale-100", "opacity-100");

            setTimeout(() => {
                logoutModal.classList.add("hidden");
                logoutModal.classList.remove("flex");
            }, 200);
        }

        document.getElementById("cancelLogout").addEventListener("click", closeLogoutModal);

        document.getElementById("confirmLogout").addEventListener("click", () => {
            logoutForm.submit();
        });
    </script>
    @vite('resources/js/app.js')
    {{-- @stack('script') --}}
</body>

</html>
