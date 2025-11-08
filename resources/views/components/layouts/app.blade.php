<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cashflow App</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    {{-- Trix Editor --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.8/trix.min.css">

    @livewireStyles
</head>

<body class="bg-light">

    <div class="container py-4">
        {{ $slot }}
    </div>

    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Trix Editor --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.8/trix.min.js"></script>

    @livewireScripts
</body>
</html>
