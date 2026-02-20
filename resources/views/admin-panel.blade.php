@component('layouts.admin')
<div class="h-[calc(100vh-140px)] flex flex-col overflow-hidden">
        
        <header class="flex items-center justify-between mb-4 flex-none">
            <h1 class="text-2xl font-bold text-white tracking-tight">Панель управления</h1>
            <div class="px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded-full text-cyan-400 text-[10px] font-mono uppercase tracking-widest">
                система активна
            </div>
        </header>
        
        {{-- Сетка: задаем h-full и min-h-0 для корректной работы вложенных скроллов --}}
        <div class="grid grid-cols-12 gap-6 flex-1 min-h-0">
            
            {{-- Левая колонка (Товары) --}}
            <div class="col-span-7 h-full min-h-0">
                <livewire:admin.products-manager />
            </div>

            {{-- Правая колонка (Заказы + Пользователи) --}}
            {{-- Именно здесь flex-col и gap-6 решают твою проблему --}}
            <div class="col-span-5 flex flex-col gap-6 h-full min-h-0">
                <div class="flex-1 min-h-0">
                    <livewire:admin.orders-manager />
                </div>
                <div class="flex-1 min-h-0">
                    <livewire:admin.users-manager />
                </div>
            </div>
        </div>
    </div>
@endcomponent