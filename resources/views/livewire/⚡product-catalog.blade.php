<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;
    public $showLikedOnly = false;
    
    public $priceMin = 0;
    public $priceMax = 150000;
    public $maxPossiblePrice = 150000;

    public $filterSpecs = []; 
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    #[On('like-toggled')]
    public function refreshCatalog()
    {

    }

    public function toggleCategory($categoryId = null)
    {
        if ($this->selectedCategory == $categoryId) {
            $this->selectedCategory = null;
            $this->filterSpecs = [];
        } else {
            $this->selectedCategory = $categoryId;
            $this->filterSpecs = [];
        }
        $this->resetPage();
    }

    public function toggleSpec($key, $value)
    {
        if (isset($this->filterSpecs[$key]) && $this->filterSpecs[$key] == $value) {
            unset($this->filterSpecs[$key]);
        } else {
            $this->filterSpecs[$key] = $value;
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['selectedCategory', 'showLikedOnly', 'filterSpecs', 'search']);
        $this->priceMin = 0;
        $this->priceMax = 150000;
        $this->resetPage();
    }

    public function getProductsProperty()
    {
        $query = Product::query();

        if ($this->search) $query->where('name', 'like', '%' . $this->search . '%');
        if ($this->selectedCategory) $query->where('category_id', $this->selectedCategory);
        
        if ($this->showLikedOnly && Auth::check()) {
            $query->whereHas('likedUsers', function(Builder $q) {
                $q->where('users.id', Auth::id());
            });
        }

        $query->whereBetween('price', [(int)$this->priceMin, (int)$this->priceMax]);

        foreach ($this->filterSpecs as $key => $value) {
            if (!empty($value)) $query->where("specs->{$key}", $value);
        }

        if ($this->sortField === 'likes') {
            $query->withCount('likedUsers')->orderBy('liked_users_count', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->paginate(9);
    }

    public function getAvailableAttributesProperty()
    {
        if (!$this->selectedCategory) return [];

        $products = Product::where('category_id', $this->selectedCategory)
            ->whereNotNull('specs')
            ->select('specs')
            ->get();

        $attributes = [];
        foreach ($products as $product) {
            if (empty($product->specs)) continue;
            foreach ($product->specs as $key => $value) {
                $attributes[$key][] = $value;
            }
        }
        foreach ($attributes as $key => $values) {
            $attributes[$key] = array_unique($values);
            sort($attributes[$key]);
        }
        return $attributes;
    }
};
?>


<div class="py-8 w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-8">
    
        <aside class="w-full lg:w-1/5 space-y-6 flex-shrink-0">
            
            @if($selectedCategory || $showLikedOnly || !empty($filterSpecs))
                <button wire:click="resetFilters" class="w-full py-2 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-lg text-sm font-bold transition border border-red-500/20">
                    Сбросить фильтры
                </button>
            @endif

            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Поиск..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 pl-10 text-white focus:border-cyan-500 outline-none transition shadow-lg">
                <svg class="w-5 h-5 text-slate-500 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            @auth
            <div class="bg-slate-800 p-5 rounded-2xl border border-slate-700 shadow-lg">
                <label class="flex items-center justify-between cursor-pointer group select-none">
                    <span class="text-slate-200 font-semibold group-hover:text-cyan-400 transition">Только избранное</span>
                    <div class="relative">
                        <input wire:model.live="showLikedOnly" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </div>
                </label>
            </div>
            @endauth

            <div class="bg-slate-800 p-5 rounded-2xl border border-slate-700 shadow-lg">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">Категории</h3>
                <div class="space-y-1">
                    <div wire:click="$set('selectedCategory', null)" class="cursor-pointer p-2 rounded-lg transition flex items-center gap-3 {{ is_null($selectedCategory) ? 'bg-cyan-900/30 text-cyan-400' : 'hover:bg-slate-700 text-slate-400' }}">
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center {{ is_null($selectedCategory) ? 'border-cyan-500' : 'border-slate-600' }}">
                            @if(is_null($selectedCategory)) <div class="w-2 h-2 rounded-full bg-cyan-500"></div> @endif
                        </div>
                        <span class="{{ is_null($selectedCategory) ? 'font-bold' : '' }}">Все категории</span>
                    </div>

                    @foreach(\App\Models\Category::all() as $category)
                        <div wire:click="toggleCategory({{ $category->id }})" class="cursor-pointer p-2 rounded-lg transition flex items-center gap-3 {{ $selectedCategory == $category->id ? 'bg-cyan-900/30 text-cyan-400' : 'hover:bg-slate-700 text-slate-400' }}">
                            <div class="w-4 h-4 rounded-full border flex items-center justify-center {{ $selectedCategory == $category->id ? 'border-cyan-500' : 'border-slate-600' }}">
                                @if($selectedCategory == $category->id) <div class="w-2 h-2 rounded-full bg-cyan-500"></div> @endif
                            </div>
                            <span class="{{ $selectedCategory == $category->id ? 'font-bold' : '' }}">{{ $category->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>


<div class="bg-slate-800 p-5 rounded-2xl border border-slate-700 shadow-lg">
    <h3 class="text-white font-bold mb-4">Цена (₽)</h3>
    
    <div class="flex gap-2 items-center mb-4">
        <input 
            wire:model.live.debounce.500ms="priceMin" 
            type="number" 
            id="price_min_input"
            class="w-full bg-slate-900 border border-slate-600 rounded-lg px-2 py-1 text-white text-sm focus:border-cyan-500 outline-none text-center"
        >
        <span class="text-slate-500">-</span>
        
        <input 
            wire:model.live="priceMax" 
            type="number" 
            id="price_max_input"
            class="w-full bg-slate-900 border border-slate-600 rounded-lg px-2 py-1 text-white text-sm focus:border-cyan-500 outline-none text-center"
        >
    </div>

    <div class="relative pt-1">
        <input 
            type="range" 
            wire:model.live="priceMax" 
            min="0" 
            max="{{ $maxPossiblePrice }}" 
            step="1000" 
            oninput="document.getElementById('price_max_input').value = this.value"
            class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-cyan-500"
        >
    
    </div>
</div>

            @if($selectedCategory && count($this->availableAttributes) > 0)
                <div class="bg-slate-800 p-5 rounded-2xl border border-slate-700 shadow-lg animate-fade-in">
                    <h3 class="text-white font-bold mb-4 border-b border-slate-700 pb-2">Характеристики</h3>
                    @foreach($this->availableAttributes as $name => $values)
                        <div class="mb-5 last:mb-0">
                            <h4 class="text-cyan-400 text-xs font-bold uppercase tracking-wider mb-2">{{ $name }}</h4>
                            <div class="space-y-1 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($values as $val)
                                    @php $isActive = isset($filterSpecs[$name]) && $filterSpecs[$name] == $val; @endphp
                                    <div wire:click="toggleSpec('{{ $name }}', '{{ $val }}')" class="cursor-pointer flex items-center space-x-2 p-1.5 rounded transition {{ $isActive ? 'bg-cyan-900/20' : 'hover:bg-slate-700/30' }}">
                                        <div class="w-4 h-4 rounded border flex items-center justify-center {{ $isActive ? 'border-cyan-500 bg-cyan-500' : 'border-slate-600' }}">
                                            @if($isActive) <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg> @endif
                                        </div>
                                        <span class="text-sm {{ $isActive ? 'text-white font-medium' : 'text-slate-400' }}">{{ $val }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </aside>

        <main class="w-full lg:w-3/4">
            <div class="flex flex-wrap gap-4 justify-between items-center mb-6 bg-slate-800 p-4 rounded-2xl border border-slate-700 shadow-lg">
                <span class="text-slate-400 text-sm">Найдено: <span class="text-white font-bold">{{ $this->products->total() }}</span></span>
                <div class="flex gap-2">
                    <select wire:model.live="sortField" class="bg-slate-900 border border-slate-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-2 outline-none">
                        <option value="created_at">Новинки</option>
                        <option value="price">Цена</option>
                    </select>
                    @if($sortField === 'price')
                        <select wire:model.live="sortDirection" class="bg-slate-900 border border-slate-600 text-white text-sm rounded-lg focus:ring-cyan-500 block p-2 outline-none">
                            <option value="asc">Сначала дешевые</option>
                            <option value="desc">Сначала дорогие</option>
                        </select>
                    @endif
                </div>
            </div>

            <div wire:loading class="w-full mb-6">
                 <div class="bg-cyan-900/20 border border-cyan-500/30 text-cyan-400 px-4 py-3 rounded-xl flex items-center justify-center gap-3 animate-pulse">
                    Обновление списка...
                </div>
            </div>

            @if($this->products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($this->products as $product)
                        <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden hover:border-cyan-500/50 transition-all group flex flex-col h-full shadow-lg">
                            <div class="relative h-48 bg-slate-900 shrink-0 overflow-hidden">
                                <img src="{{ $product->image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-3 right-3 bg-slate-900/90 text-cyan-400 text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded-md border border-cyan-500/30 backdrop-blur-sm">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            <div class="p-5 flex flex-col flex-grow">
                                <div class="flex-grow">
                                    <a href="{{ route('product.show', $product->id) }}">
                                        <h3 class="text-lg font-bold mb-2 group-hover:text-cyan-400 transition-colors line-clamp-1">{{ $product->name }}</h3>
                                    </a>
                                    @if(!empty($product->specs))
                                        <div class="space-y-1 mb-4">
                                            @foreach(array_slice($product->specs, 0, 2) as $key => $val)
                                                <div class="flex justify-between text-xs text-slate-400">
                                                    <span>{{ $key }}</span>
                                                    <span class="text-slate-200">{{ $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-slate-500 text-sm line-clamp-2">{{ $product->description }}</p>
                                    @endif
                                </div>
                                <div class="pt-4 border-t border-slate-700/50 flex items-center justify-between gap-2 mt-auto">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-slate-500 uppercase font-semibold">Цена</span>
                                        <span class="text-xl font-black text-white whitespace-nowrap">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <livewire:like-button :product-id="$product->id" :key="'like-'.$product->id" />
                                        <livewire:cart-button :product-id="$product->id" :key="'cart-btn-'.$product->id" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $this->products->links() }}</div>
            @else
                <div class="text-center py-20 bg-slate-800 rounded-3xl border border-dashed border-slate-700">
                    <h3 class="text-lg font-medium text-white">Ничего не найдено</h3>
                </div>
            @endif
        </main>
    </div>
</div>