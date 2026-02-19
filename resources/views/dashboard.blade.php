<x-app-layout>
    
    <div class="min-h-screen bg-slate-900">
        <livewire:header />

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
  <div class="bg-slate-800 border border-slate-700 overflow-hidden shadow-2xl sm:rounded-3xl mb-12">
                    <div class="p-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            
                            
                            <div class="flex items-center gap-6">
                                
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-3xl ">
                                    {{ Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                
                                <div>
                                    <h2 class="text-3xl font-black text-white leading-none mb-2">
                                        {{ Auth::user()->name }}
                                    </h2>
                                    <div class="flex flex-wrap gap-y-2 gap-x-4 text-sm">
                                        <span class="flex items-center gap-1.5 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ Auth::user()->email }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            На сайте с {{ Auth::user()->created_at->format('d.m.Y') }}
                                        </span>
                                        
                                        @if(isset(Auth::user()->phone))
                                            <span class="flex items-center gap-1.5 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ Auth::user()->phone }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            
                            <div class="shrink-0">
                                <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center justify-center gap-2 px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-2xl transition-all border border-slate-600 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Настройки
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                
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