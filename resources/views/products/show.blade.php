<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - TechShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white antialiased">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <a href="{{ route('home') }}" class="text-cyan-400 hover:text-cyan-300 mb-8 inline-block">← Назад в каталог</a>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-slate-800 p-8 rounded-3xl border border-slate-700">
            <div class="rounded-2xl overflow-hidden bg-slate-700">
                <img src="{{ $product->image }}" class="w-full h-full object-cover">
            </div>

            <div class="flex flex-col">
                <span class="text-cyan-400 font-mono mb-2">{{ $product->category->name }}</span>
                <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>
                <p class="text-slate-400 text-lg mb-8 leading-relaxed">{{ $product->description }}</p>
                
                <div class="mt-auto flex items-center justify-between">
                    <span class="text-3xl font-bold">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                    <button class="bg-cyan-600 hover:bg-cyan-500 text-white px-8 py-4 rounded-2xl font-bold transition-all shadow-lg shadow-cyan-900/20">
                        Добавить в корзину
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>