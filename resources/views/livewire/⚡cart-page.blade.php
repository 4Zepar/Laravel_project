<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    // Поля для гостевой формы
    public $showSuccessModal = false;
    public $showGuestForm = false;
    public $name = '';
    public $phone = '';
    public $email = '';

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

    // Главная логика кнопки
    public function checkout()
    {
        if (Auth::check()) {
            $this->placeOrder();
        } else {
            $this->showGuestForm = true;
        }
    }

    public function placeOrder()
    {
        // Валидация только для гостей
        if (!Auth::check()) {
            $this->validate([
                'name' => 'required|min:2',
                'phone' => 'required',
                'email' => 'required|email',
            ]);
        }

        // 1. Создаем заказ
        $order = Order::create([
            'user_id' => Auth::id(),
            'client_name' => Auth::check() ? Auth::user()->name : $this->name,
            'client_email' => Auth::check() ? Auth::user()->email : $this->email,
            'client_phone' => $this->phone, // для гостей обязательно
            'total_price' => $this->total,
        ]);

        // 2. Сохраняем товары из сессии
        foreach ($this->cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        // 3. Чистим корзину
        session()->forget('cart');
        $this->dispatch('cart-updated');

        $this->showSuccessModal = true;
    }
};
?>

<div class="min-h-screen bg-slate-900">
    <livewire:header />

    <div class="max-w-5xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold mb-8 text-cyan-400">Ваша корзина</h1>

        @if(count($this->cart) > 0)
            <div class="bg-slate-800 rounded-3xl p-8 border border-slate-700 shadow-xl">
                {{-- Список товаров (оставляем твой код) --}}
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
                
                {{-- ЛОГИКА ОФОРМЛЕНИЯ --}}
                @if(!$showGuestForm)
                    <button wire:click="checkout" class="w-full mt-8 bg-cyan-600 hover:bg-cyan-500 py-4 rounded-2xl font-bold text-xl transition-all transform active:scale-[0.98] shadow-lg shadow-cyan-900/40 text-white">
                        Оформить заказ
                    </button>
                @else
                    {{-- ФОРМА ДЛЯ ГОСТЯ --}}
                    <div class="mt-8 p-6 bg-slate-700/50 rounded-2xl border border-cyan-500/30">
                        <h2 class="text-xl font-bold text-white mb-4">Данные получателя</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" wire:model="name" placeholder="Ваше имя" class="bg-slate-800 border-slate-600 text-white rounded-xl p-3 focus:ring-cyan-500">
                            <input type="text" wire:model="phone" placeholder="Телефон" class="bg-slate-800 border-slate-600 text-white rounded-xl p-3 focus:ring-cyan-500">
                            <input type="email" wire:model="email" placeholder="Email" class="bg-slate-8:00 border-slate-600 text-black rounded-xl p-3 focus:ring-cyan-500">
                        </div>
                        @if ($errors->any())
                            <div class="text-red-400 mt-2 text-sm">Пожалуйста, заполните все поля корректно.</div>
                        @endif
                        <button wire:click="placeOrder" class="w-full mt-6 bg-green-600 hover:bg-green-500 py-3 rounded-xl font-bold text-white transition-all">
                            Подтвердить покупку
                        </button>
                    </div>
                @endif
            </div>
        @else
            {{-- Заглушка пустой корзины --}}
            <div class="text-center py-20 bg-slate-800/50 rounded-3xl border border-dashed border-slate-700">
                <p class="text-slate-400 text-xl">В корзине пока пусто...</p>
                <a href="/" class="mt-6 inline-block bg-slate-700 hover:bg-slate-600 px-8 py-3 rounded-xl transition text-white">
                    Вернуться за покупками
                </a>
            </div>
        @endif
    </div>

    {{-- Модальное окно --}}
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div class="bg-slate-800 border border-slate-700 p-8 rounded-3xl max-w-sm w-full text-center shadow-2xl transform transition-all">
                <div class="w-20 h-20 bg-cyan-500/20 text-cyan-400 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Заказ принят!</h2>
                <p class="text-slate-400 mb-8">Спасибо за покупку. Мы свяжемся с вами в ближайшее время.</p>
                
                <a href="/" class="block w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 rounded-xl transition-colors">
                    Вернуться на главную
                </a>
            </div>
        </div>
    @endif
</div>