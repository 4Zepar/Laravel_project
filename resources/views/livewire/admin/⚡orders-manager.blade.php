<?php
use Livewire\Component;
use App\Models\Order;

new class extends Component {
    public function updateStatus($id, $status) {
        Order::find($id)->update(['status' => $status]);
    }
}; ?>

<div class="bg-slate-800 border border-slate-700/50 rounded-3xl p-6 h-full flex flex-col shadow-xl backdrop-blur-md">
    <div class="flex-1 overflow-y-auto custom-scrollbar pr-2">
        <table class="w-full text-sm text-left text-slate-300">
            <thead>
                <tr class="text-slate-400 border-b border-slate-700">
                    <th class="pb-4 font-medium">Заказ</th>
                    <th class="pb-4 font-medium">Сумма</th>
                    <th class="pb-4 font-medium">Статус</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @foreach(Order::latest()->get() as $order)
                    <tr wire:key="order-{{ $order->id }}">
                        <td class="py-4">
                            <span class="text-cyan-400 font-mono">#{{ $order->id }}</span>
                            <div class="text-[10px] text-slate-500">{{ $order->client_name }}</div>
                        </td>
                        <td class="py-4 text-sm font-bold">{{ number_format($order->total_price, 0, '.', ' ') }} ₽</td>
                        <td class="py-4">
                            <select wire:change="updateStatus({{ $order->id }}, $event.target.value)" class="bg-slate-900 border-slate-700 text-[10px] rounded-lg text-white">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Новый</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Ок</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>