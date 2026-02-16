<x-app-layout>
    {{-- Удаляем слот "header", так как наш новый Livewire Header берет на себя роль навигации --}}
    
    <div class="min-h-screen bg-slate-900">
        {{-- Вставляем твой выбранный хедер --}}
        <livewire:header />

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Блок приветствия --}}
                <div class="bg-slate-800 border border-slate-700 overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                    <div class="p-8 text-white">
                        <h2 class="text-2xl font-bold mb-2">Привет, {{ Auth::user()->name }}! 👋</h2>
                        <p class="text-slate-400">{{ __("Рады видеть тебя снова. Здесь твои настройки и избранные товары.") }}</p>
                    </div>
                </div>

                {{-- Секция лайков в стиле твоего магазина --}}
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-white tracking-tight">
                            Ваши <span class="text-cyan-400">лайки</span>
                        </h2>
                        <span class="bg-slate-800 text-cyan-400 px-4 py-1 rounded-full border border-slate-700 text-sm">
                            Всего: {{ Auth::user()->likedProducts->count() }}
                        </span>
                    </div>

                    @if(Auth::user()->likedProducts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach(Auth::user()->likedProducts as $product)
                                <div class="bg-slate-800 border border-slate-700 p-5 rounded-2xl hover:border-cyan-500/50 transition-all group shadow-lg">
                                    <div class="flex items-center gap-4">
                                        {{-- Мини-превью изображения --}}
                                        <img src="{{ $product->image }}" class="w-16 h-16 rounded-lg object-cover border border-slate-600">
                                        
                                        <div class="flex-1">
                                            <p class="font-bold text-white group-hover:text-cyan-400 transition">{{ $product->name }}</p>
                                            <p class="text-slate-500 text-xs mb-2">{{ number_format($product->price, 0, '.', ' ') }} ₽</p>
                                            
                                            <div class="flex justify-between items-center">
                                                <a href="{{ route('product.show', $product->id) }}" class="text-cyan-400 text-sm font-medium hover:underline">
                                                    Перейти →
                                                </a>
                                                {{-- Добавляем кнопку корзины, раз уж мы в профиле --}}
                                                <livewire:cart-button :product-id="$product->id" :key="'fav-'.$product->id" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20 bg-slate-800/30 rounded-3xl border border-dashed border-slate-700">
                            <p class="text-slate-500 text-lg">Вы еще ничего не лайкнули. Самое время зайти в каталог!</p>
                            <a href="/" wire:navigate class="mt-4 inline-block text-cyan-400 hover:text-cyan-300 transition font-bold">
                                Перейти к покупкам
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>