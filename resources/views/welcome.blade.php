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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <livewire:header :key="'main-header'" />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden hover:border-cyan-500/50 transition-all group flex flex-col h-full">
                    {{-- Изображение --}}
                    <div class="relative h-52 bg-slate-700 shrink-0">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 bg-slate-900/90 text-cyan-400 text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded-md border border-cyan-500/30 backdrop-blur-sm">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    {{-- Контентная часть --}}
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex-grow">
                            <a href="{{ route('product.show', $product->id) }}">
                                <h3 class="text-lg font-bold mb-2 group-hover:text-cyan-400 transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            <p class="text-slate-400 text-sm mb-6 line-clamp-2 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>
                        
                        {{-- Футер карточки (Цена и Кнопки) --}}
                        <div class="pt-4 border-t border-slate-700/50 flex items-center justify-between gap-2">
                            <div class="flex flex-col">
                                <span class="text-xs text-slate-500 uppercase font-semibold">Цена</span>
                                <span class="text-xl font-black text-white whitespace-nowrap">
                                    {{ number_format($product->price, 0, '.', ' ') }} ₽
                                </span>
                            </div>

                            <div class="flex items-center gap-1">
                                {{-- Кнопка лайка --}}
                               <livewire:like-button :product-id="$product->id" :key="'like-'.$product->id" />

                                {{-- Кнопка корзины --}}
                                <div class="transform scale-90 origin-right">
                                    <livewire:cart-button :product-id="$product->id" :key="'cart-btn-'.$product->id" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>