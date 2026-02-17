<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechShop - Магазин периферии</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white antialiased">
    @if(session('success'))
        <div id="flash-message" class="fixed top-4 right-4 z-50 bg-cyan-500 text-slate-900 px-6 py-3 rounded-2xl shadow-2xl font-bold transition-opacity duration-500">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const msg = document.getElementById('flash-message');
                if (msg) {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    <div class=" mx-auto  sm:px-6 ">
        
        <livewire:header :key="'main-header'" />

        <livewire:product-catalog />

    </div>
</body>
</html>