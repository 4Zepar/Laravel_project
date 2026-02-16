<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - TechShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white antialiased min-h-screen">
    
    {{-- Умный хедер теперь всегда сверху --}}
    <livewire:header />

    <div class="max-w-7xl mx-auto px-4 py-8">
        
        {{-- Кнопка назад под хедером --}}
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-slate-400 hover:text-cyan-400 inline-flex items-center gap-2 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Назад в каталог
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-slate-800 p-8 rounded-3xl border border-slate-700 shadow-2xl">
            {{-- Блок изображения --}}
            <div class="rounded-2xl overflow-hidden bg-slate-700 relative group aspect-square md:aspect-auto">
                <img src="{{ $product->image }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            </div>

            {{-- Блок информации --}}
            <div class="flex flex-col">
                <span class="inline-block bg-slate-900/50 text-cyan-400 px-3 py-1 rounded-lg text-sm font-mono mb-4 w-fit border border-slate-600">
                    {{ $product->category->name ?? 'Категория' }}
                </span>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white tracking-tight">{{ $product->name }}</h1>
                
                <p class="text-slate-400 text-lg mb-8 leading-relaxed border-l-4 border-slate-600 pl-4">
                    {{ $product->description }}
                </p>
                
                <div class="mt-auto pt-8 border-t border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="text-center sm:text-left">
                        <span class="block text-sm text-slate-500 mb-1">Стоимость</span>
                        <span class="text-4xl font-bold text-white">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                    </div>

                    <div class="transform scale-110 sm:scale-100">
                         <livewire:cart-button :product-id="$product->id" :key="'product-page-'.$product->id" />
                    </div>
                </div>
            </div>
        </div>

        <livewire:product-comments :productId="$product->id" />
    </div>
</body>
</html>