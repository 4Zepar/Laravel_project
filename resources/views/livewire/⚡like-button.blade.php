<?php

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $productId;
    public $isLiked;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->updateLikeStatus();
    }

    // Метод для синхронизации статуса
    public function updateLikeStatus()
    {
        if (!Auth::check()) {
            $this->isLiked = false;
            return;
        }
        
        $this->isLiked = Auth::user()->likedProducts()->where('product_id', $this->productId)->exists();
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $user = Auth::user();
        
        if ($this->isLiked) {
            $user->likedProducts()->detach($this->productId);
            $this->isLiked = false;
        } else {
            $user->likedProducts()->attach($this->productId);
            $this->isLiked = true;
        }

        // Принудительно уведомляем систему, что данные изменились
        $this->dispatch('like-toggled'); 
    }
};
?>

<div class="flex-1">
    <button 
        wire:click.stop="toggleLike" 
        wire:loading.attr="disabled"
        class="p-2 rounded-xl bg-slate-700/50 hover:bg-slate-700 hover:text-red-500 transition-all disabled:opacity-50 {{ $isLiked ? 'text-red-500' : 'text-slate-400' }}"
    >
        <svg 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-6 w-6" 
            fill="{{ $isLiked ? 'currentColor' : 'none' }}" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</div>