<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    #[On('cart-updated')]
    public function refresh() {} 

    public function getCartProperty()
    {
        return session()->get('cart', []);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }
};
?>

<div class="min-h-screen bg-slate-900">
    <livewire:header />

    <div class="max-w-5xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold mb-8 text-cyan-400">Ваша корзина</h1>

        @if(count($this->cart) > 0)
            <div class="bg-slate-800 rounded-3xl p-8 border border-slate-700 shadow-xl">
                @foreach($this->cart as $id => $details)
                    <div class="flex items-center justify-between border-b border-slate-700 py-6 last:border-0">
                        <div class="flex items-center space-x-6">
                            <img src="{{ $details['image'] }}" class="w-20 h-20 rounded-xl object-cover shadow-lg">
                            <div>
                                <h3 class="font-bold text-lg text-white">{{ $details['name'] }}</h3>
                                <p class="text-slate-400 mb-2">{{ number_format($details['price'], 0, '.', ' ') }} ₽ / шт.</p>
                                
                                <livewire:cart-button :product-id="$id" :key="'cart-page-item-'.$id" />
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500 text-sm mb-1">Сумма</p>
                            <span class="text-2xl font-mono text-cyan-400 font-bold">
                                {{ number_format($details['price'] * $details['quantity'], 0, '.', ' ') }} ₽
                            </span>
                        </div>
                    </div>
                @endforeach

                <div class="mt-8 flex justify-between items-center pt-6 border-t border-slate-600">
                    <span class="text-2xl text-white">Итого к оплате:</span>
                    <span class="text-4xl font-bold text-cyan-400 shadow-cyan-500/20 drop-shadow-md">
                        {{ number_format($this->total, 0, '.', ' ') }} ₽
                    </span>
                </div>
                
                <button class="w-full mt-8 bg-cyan-600 hover:bg-cyan-500 py-4 rounded-2xl font-bold text-xl transition-all transform active:scale-[0.98] shadow-lg shadow-cyan-900/40">
                    Оформить заказ
                </button>
            </div>
        @else
            <div class="text-center py-20 bg-slate-800/50 rounded-3xl border border-dashed border-slate-700">
                <div class="mb-4 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p class="text-slate-400 text-xl">В корзине пока пусто...</p>
                <a href="/" class="mt-6 inline-block bg-slate-700 hover:bg-slate-600 px-8 py-3 rounded-xl transition text-white">
                    Вернуться за покупками
                </a>
            </div>
        @endif
    </div>
</div>