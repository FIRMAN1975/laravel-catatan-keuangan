<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="/budget.png" type="image/x-icon" />
    <title>Laravel Cashflows</title>

    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <div class="container-fluid py-4">
        {{ $slot }}
    </div>

    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("livewire:initialized", () => {
            Livewire.on("closeModal", (data) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(data.id));
                if (modal) modal.hide();
            });

            Livewire.on("showModal", (data) => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(data.id));
                if (modal) modal.show();
            });
        });
    </script>

    @livewireScripts
</body>
</html>