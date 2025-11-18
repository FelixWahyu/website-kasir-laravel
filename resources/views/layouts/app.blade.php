<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (isset($settings['app_logo']) && $settings['app_logo'])
        <link rel="icon" href="{{ Storage::url($settings['app_logo']) }}" type="image/png">
    @endif
    <title>{{ $title ?? 'Sistem Point Of Sale' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <main class="mb-12 mt-4">
        @yield('content')
    </main>

</body>

</html>
