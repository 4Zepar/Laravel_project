<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Корзина - TechShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white">
    <div class="max-w-5xl mx-auto px-4 py-12">
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
        <h1 class="text-3xl font-bold mb-8 text-cyan-400">Ваша корзина</h1>

        @if(session('cart'))
            <div class="bg-slate-800 rounded-3xl p-8 border border-slate-700 shadow-xl">
                @foreach($cart as $id => $details)
                    <div class="flex items-center justify-between border-b border-slate-700 py-4 last:border-0">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $details['image'] }}" class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <h3 class="font-bold">{{ $details['name'] }}</h3>
                                <p class="text-slate-400">{{ $details['quantity'] }} шт. x {{ number_format($details['price'], 0, '.', ' ') }} ₽</p>
                            </div>
                        </div>
                        <span class="text-xl font-mono text-cyan-400">{{ number_format($details['price'] * $details['quantity'], 0, '.', ' ') }} ₽</span>
                    </div>
                @endforeach

                <div class="mt-8 flex justify-between items-center pt-6 border-t border-slate-600">
                    <span class="text-2xl">Итого:</span>
                    <span class="text-4xl font-bold text-cyan-400">{{ number_format($total, 0, '.', ' ') }} ₽</span>
                </div>
                
                <button class="w-full mt-8 bg-cyan-600 hover:bg-cyan-500 py-4 rounded-2xl font-bold text-xl transition">
                    Оформить заказ
                </button>
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-slate-400 text-xl">В корзине пока пусто...</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block text-cyan-400 underline">Вернуться за покупками</a>
            </div>
        @endif
    </div>
</body>
</html>