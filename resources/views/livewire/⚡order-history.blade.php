<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function getOrdersProperty()
    {
        return Auth::user()->orders()->with('items.product')->get();
    }
};
?>

<div class="mt-12 space-y-4">
    <div class="flex items-center justify-between mb-8">
       <h2 class="text-3xl font-bold text-white tracking-tight">
            История <span class="text-cyan-400">заказов</span>
        </h2>
    </div>

    @forelse($this->orders as $order)
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden shadow-sm hover:border-slate-600 transition-colors">
            <div class="flex flex-wrap items-center justify-between p-4 bg-slate-800/80 gap-4">
                <div class="flex items-center space-x-4">
                    <span class="text-cyan-500 font-mono font-bold">#{{ $order->id }}</span>
                    <span class="text-slate-400 text-sm">{{ $order->created_at->format('d.m.y H:i') }}</span>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <span class="text-xs text-slate-500 block uppercase tracking-wider">Сумма</span>
                        <span class="text-white font-bold">{{ number_format($order->total_price, 0, '.', ' ') }} ₽</span>
                    </div>
                    
                    <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter {{ $order->status == 'completed' ? 'bg-green-500/10 text-green-400' : 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' }}">
                        {{ $order->status }}
                    </span>
                </div>
            </div>

            <div class="px-4 py-3 bg-slate-900/30 border-t border-slate-700/50">
                <div class="space-y-1">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-[13px]">
                            <span class="text-slate-400">
                                <span class="text-slate-500 font-bold">{{ $item->quantity }}x</span> 
                                {{ $item->product->name ?? 'Товар удален' }}
                            </span>
                            <span class="text-slate-500 font-mono">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-10 bg-slate-800/30 rounded-xl border border-dashed border-slate-700">
            <p class="text-slate-500 text-sm">История заказов пуста</p>
        </div>
    @endforelse
</div>