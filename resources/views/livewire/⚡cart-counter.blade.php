<?php

use Livewire\Component; // Используем Volt-версию, раз у тебя анонимные классы
use Livewire\Attributes\On; 

new class extends Component
{
    // Этот атрибут заставляет компонент перерисоваться, 
    // когда где-то срабатывает $this->dispatch('cart-updated')
    #[On('cart-updated')]
    public function updateCounter()
    {
        // Просто пустой метод, его вызов заставит Livewire 
        // обновить HTML этого компонента
    }

    public function getCountProperty()
    {
        $cart = session('cart', []);
        return count($cart);
    }
};
?>

<a href="{{ route('cart.index') }}" class="relative group p-2 bg-slate-800 rounded-xl border border-slate-700 hover:border-cyan-500 transition-all">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white group-hover:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
    </svg>
    
    @if($this->count > 0)
        <span class="absolute -top-2 -right-2 bg-cyan-500 text-slate-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-slate-900">
            {{ $this->count }}
        </span>
    @endif
</a>