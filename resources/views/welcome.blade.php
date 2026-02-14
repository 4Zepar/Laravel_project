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
                    msg.style.opacity = '0'; // Делаем прозрачным
                    setTimeout(() => msg.remove(), 500); // Удаляем из кода через 0.5 сек
                }
            }, 3000); // Висит 3 секунды
        </script>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <header class="flex justify-between items-center mb-12">
            <h1 class="text-3xl font-bold tracking-tight text-cyan-400">TECH<span class="text-white">SHOP</span></h1>
            
            <nav class="flex items-center space-x-6">
                <a href="{{ route('cart.index') }}" class="relative group p-2 bg-slate-800 rounded-xl border border-slate-700 hover:border-cyan-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white group-hover:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 bg-cyan-500 text-slate-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-slate-900">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                @if (Route::has('login'))
                    <div class="space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm border border-cyan-500 px-4 py-2 rounded-lg hover:bg-cyan-500 transition">Кабинет</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm hover:text-cyan-400">Войти</a>
                            <a href="{{ route('register') }}" class="text-sm bg-cyan-600 px-4 py-2 rounded-lg hover:bg-cyan-500 transition shadow-lg shadow-cyan-900/20">Регистрация</a>
                        @endauth
                    </div>
                @endif
            </nav>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden hover:border-cyan-500/50 transition-all group">
                    <div class="relative h-48 bg-slate-700">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-3 right-3 bg-slate-900/80 text-cyan-400 text-xs px-2 py-1 rounded-md border border-cyan-500/30">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div class="p-5">
                        <a href="{{ route('product.show', $product->id) }}">
                            <h3 class="text-lg font-semibold mb-2 group-hover:text-cyan-400 transition">{{ $product->name }}</h3>
                        </a>
                        <p class="text-slate-400 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                            <div class="flex justify-between items-center">
                                <form action="{{ route('product.like', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 transition {{ Auth::user()?->likedProducts->contains($product->id) ? 'text-red-500' : 'text-slate-400 hover:text-red-400' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="{{ Auth::user()?->likedProducts->contains($product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </form>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 p-2 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>