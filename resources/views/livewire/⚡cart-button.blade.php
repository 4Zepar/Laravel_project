<?php

use Livewire\Component;
use App\Models\Product;

new class extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    // Вычисляемое свойство: подтягивает данные из сессии при каждом рендере
    public function getCartItemProperty()
    {
        return session('cart')[$this->productId] ?? null;
    }

    public function add()
    {
        $product = Product::find($this->productId);
        if (!$product) return;

        $cart = session()->get('cart', []);

        if(isset($cart[$this->productId])) {
            $cart[$this->productId]['quantity']++;
        } else {
            $cart[$this->productId] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        // ВАЖНО: Принудительно сохраняем, чтобы убрать лаги Windows/OpenServer
        session()->save(); 
        
        $this->dispatch('cart-updated'); 
    }

    public function remove()
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$this->productId])) {
            if($cart[$this->productId]['quantity'] > 1) {
                $cart[$this->productId]['quantity']--;
            } else {
                unset($cart[$this->productId]);
            }
        }

        session()->put('cart', $cart);
        // ВАЖНО: Снимаем блокировку файла сессии немедленно
        session()->save(); 

        $this->dispatch('cart-updated');
    }
};
?>

<div class="relative inline-block">
    {{-- Индикатор загрузки (появляется только если запрос затянулся более чем на 150мс) --}}
    <div wire:loading.flex wire:target="add, remove" class="absolute inset-0 z-10 items-center justify-center bg-slate-900/40 rounded-xl backdrop-blur-[1px]">
        <svg class="animate-spin h-5 w-5 text-cyan-400" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    @if($this->cartItem)
        <div class="flex items-center bg-slate-700 rounded-xl p-1 border border-cyan-500/50 shadow-lg">
            <button 
                wire:click.stop="remove" 
                wire:loading.attr="disabled" 
                class="w-10 h-10 flex items-center justify-center hover:bg-slate-600 rounded-lg text-cyan-400 font-bold transition-all active:scale-90 disabled:opacity-50"
            >-</button>
            
            <span class="px-4 font-mono font-bold text-white text-lg min-w-[3rem] text-center">
                {{ $this->cartItem['quantity'] }}
            </span>
            
            <button 
                wire:click.stop="add" 
                wire:loading.attr="disabled" 
                class="w-10 h-10 flex items-center justify-center hover:bg-slate-600 rounded-lg text-cyan-400 font-bold transition-all active:scale-90 disabled:opacity-50"
            >+</button>
        </div>
    @else
        <button 
            wire:click.stop="add" 
            wire:loading.attr="disabled" 
            class="bg-cyan-600 hover:bg-cyan-500 p-2 px-6 rounded-xl transition-all flex items-center gap-2 text-white font-semibold shadow-lg shadow-cyan-900/40 active:scale-95 disabled:opacity-50"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Купить</span>
        </button>
    @endif
</div>