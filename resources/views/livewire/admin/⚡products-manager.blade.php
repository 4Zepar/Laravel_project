<?php
use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

new class extends Component {
    public $productId, $name, $description, $price, $image, $category_id;
    public $specs = []; // Для хранения JSON характеристик
    public $showModal = false;

    public function openModal($id = null) {
        $this->resetErrorBag();
        if ($id) {
            $product = Product::find($id);
            $this->productId = $id;
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->image = $product->image;
            $this->category_id = $product->category_id;
            $this->specs = $product->specs ?? [];
        } else {
            $this->reset(['productId', 'name', 'description', 'price', 'image', 'category_id', 'specs']);
            $this->category_id = Category::first()?->id;
            $this->specs = ['Бренд' => '', 'Гарантия' => '12 мес.'];
        }
        $this->showModal = true;
    }

    // Добавление новой строки в характеристики
    public function addSpec() {
        $this->specs['Новый параметр'] = '';
    }

    // Удаление параметра
    public function removeSpec($key) {
        unset($this->specs[$key]);
    }

    public function save() {
        // Лог для отладки (можно посмотреть в storage/logs/laravel.log если не сработает)
        // \Log::info($this->specs);

        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|string', // Убрали url для теста, вдруг там длинная base64 или специфичный путь
            'category_id' => 'required|exists:categories,id',
            'specs' => 'nullable|array',
        ]);

        try {
            Product::updateOrCreate(['id' => $this->productId], [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'image' => $this->image,
                'category_id' => $this->category_id,
                'specs' => $this->specs,
            ]);

            $this->showModal = false;
            // Сброс полей после создания
            $this->reset(['productId', 'name', 'description', 'price', 'image', 'specs']);
        } catch (\Exception $e) {
            // Если будет ошибка базы данных, она отобразится в логах
            session()->flash('error', 'Ошибка при сохранении: ' . $e->getMessage());
        }
    }

    public function delete($id) {
        Product::destroy($id);
    }
}; ?>

<div class="bg-slate-800 border border-slate-700/50 rounded-3xl p-6 h-full flex flex-col shadow-xl backdrop-blur-md">
    <div class="flex justify-between items-center mb-6 flex-none">
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Каталог товаров</h3>
        <button wire:click="openModal()" class="bg-cyan-600 hover:bg-cyan-500 text-[10px] px-4 py-2 rounded-xl font-bold text-white transition-all shadow-lg shadow-cyan-900/20">
            + НОВЫЙ ТОВАР
        </button>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar pr-2">
        <table class="w-full text-sm text-left text-slate-300">
            <thead>
                <tr class="text-slate-400 border-b border-slate-700/50">
                    <th class="pb-4 font-medium uppercase text-[10px]">Товар</th>
                    <th class="pb-4 font-medium uppercase text-[10px]">Категория</th>
                    <th class="pb-4 font-medium uppercase text-[10px]">Цена</th>
                    <th class="pb-4 font-medium text-right uppercase text-[10px]">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
                @foreach(Product::with('category')->latest()->get() as $product)
                    <tr wire:key="prod-{{ $product->id }}" class="hover:bg-slate-700/20 transition-colors">
                        <td class="py-3 flex items-center gap-3">
                            <img src="{{ $product->image }}" class="w-10 h-10 rounded-lg object-cover bg-slate-900">
                            <span class="font-bold text-white">{{ $product->name }}</span>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 bg-slate-900 rounded-md text-[10px] text-slate-400">{{ $product->category?->name }}</span>
                        </td>
                        <td class="py-3 text-cyan-400 font-mono">{{ number_format($product->price, 0, '.', ' ') }} ₽</td>
                        <td class="py-3 text-right space-x-2">
                            <button wire:click="openModal({{ $product->id }})" class="text-cyan-400">✎</button>
                            <button wire:click="delete({{ $product->id }})" onclick="confirm('Удалить?') || event.stopImmediatePropagation()" class="text-red-500/70">✕</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($showModal)
        @teleport('#modal-container')
            <div class="fixed inset-0 w-screen h-screen z-[9999] flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4">
                <div class="bg-slate-900 border border-slate-700 w-full max-w-2xl rounded-[2.5rem] shadow-2xl p-8 relative flex flex-col max-h-[90vh] animate-in zoom-in duration-200">
                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500/50 p-4 rounded-2xl mb-4">
                            <ul class="text-xs text-red-400 list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button wire:click="$set('showModal', false)" class="absolute top-6 right-6 text-slate-500 hover:text-white">✕</button>

                    <h4 class="text-2xl font-bold text-white mb-6">{{ $productId ? 'Редактирование' : 'Создание товара' }}</h4>
                    
                    <div class="flex-1 overflow-y-auto pr-4 custom-scrollbar space-y-6">
                        {{-- Основные поля --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Название</label>
                                <input type="text" wire:model="name" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-3 transition-all outline-none">
                            </div>
                            
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Категория</label>
                                <select wire:model="category_id" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-3 outline-none">
                                    @foreach(Category::all() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Цена (₽)</label>
                                <input type="number" wire:model="price" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-3 outline-none">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">URL Изображения</label>
                            <input type="text" wire:model="image" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-3 transition-all outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Описание</label>
                            <textarea wire:model="description" rows="3" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-3 transition-all outline-none"></textarea>
                        </div>

                        {{-- Динамические характеристики (Specs) --}}
                        <div class="space-y-3 bg-slate-950/50 p-4 rounded-3xl border border-slate-800">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Характеристики (JSON)</label>
                                <button wire:click="addSpec" class="text-cyan-400 text-[10px] font-bold hover:underline">+ Добавить параметр</button>
                            </div>
                            
                            @foreach($specs as $key => $value)
                                <div class="flex gap-2 items-center">
                                    <input type="text" placeholder="Свойство" 
                                           value="{{ $key }}" 
                                           wire:change="$set('specs.{{ $key }}', $event.target.value)" 
                                           class="flex-1 bg-slate-900 border-slate-800 rounded-xl text-xs text-slate-300 px-3 py-2">
                                    <input type="text" placeholder="Значение" 
                                           wire:model="specs.{{ $key }}" 
                                           class="flex-1 bg-slate-900 border-slate-800 rounded-xl text-xs text-white px-3 py-2">
                                    <button wire:click="removeSpec('{{ $key }}')" class="text-red-500/50 hover:text-red-500">✕</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8 pt-4 border-t border-slate-800">
                        <button wire:click="$set('showModal', false)" class="flex-1 px-6 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl font-bold uppercase text-[10px] tracking-widest transition-all">Отмена</button>
                        <button wire:click="save" class="flex-1 px-6 py-4 bg-cyan-600 hover:bg-cyan-500 text-white rounded-2xl font-bold uppercase text-[10px] tracking-widest shadow-lg shadow-cyan-900/30 transition-all active:scale-95">Сохранить</button>
                    </div>
                </div>
            </div>
        @endteleport
    @endif
</div>