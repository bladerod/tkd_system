
@php
    $branding = \App\Models\Branding::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($branding) && $branding->logo_path)
        <link rel="icon" href="{{ Storage::url($branding->logo_path) }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TKD')</title>

    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/user.css'])
    @vite(['resources/css/reports.css'])
    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add Simple-Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <script src="//unpkg.com/alpinejs" defer></script>

    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('includes.navbar')
    @include('includes.sidebar')
    <div class="row">
        <main class="content-wrapper p-6" style="margin-left: 14%">
            @yield('content')
        </main>
    </div>


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/navbarDrop.js'])
    @vite(['resources/js/classes.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>
