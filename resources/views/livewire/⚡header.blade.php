<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

new class extends Component
{
    /**
     * Метод для выхода из системы
     */
    public function logout()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        // Важно: используем обычный редирект для полного сброса состояния
        return redirect()->to('/');
    }
};
?>

<header class="sticky top-0 z-50 w-full bg-slate-900/80 backdrop-blur-md border-b border-slate-700 shadow-lg mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            {{-- Логотип --}}
            <a href="/" wire:navigate class="flex items-center group">
                <span class="text-2xl font-bold tracking-tight text-cyan-400 group-hover:text-cyan-300 transition-colors">
                    TECH<span class="text-white group-hover:text-slate-200">SHOP</span>
                </span>
            </a>

            <nav class="flex items-center gap-6">
                
                <a href="/" wire:navigate class="hidden md:block text-sm font-medium text-slate-300 hover:text-cyan-400 transition-colors">
                    Каталог
                </a>

                <div class="relative flex items-center">
                    <livewire:cart-counter />
                </div>

                <div class="h-6 w-px bg-slate-700 mx-2"></div>

                {{-- Проверка авторизации: здесь происходит магия смены кнопок --}}
                <div class="flex items-center gap-4">
                    @auth
                        {{-- Кнопка профиля (видна только авторизованным) --}}
                        <a href="{{ url('/dashboard') }}" wire:navigate class="flex items-center gap-2 text-sm font-medium text-slate-300 hover:text-white transition-colors group">
                            <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-600 flex items-center justify-center text-cyan-400 group-hover:border-cyan-400 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="hidden sm:block">Кабинет</span>
                        </a>

                        {{-- Кнопка выхода --}}
                        <button wire:click="logout" class="text-sm font-medium text-slate-400 hover:text-red-400 transition-colors flex items-center gap-1" title="Выйти">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    @else
                        {{-- Кнопки входа/регистрации (видны только гостям) --}}
                        <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium text-slate-300 hover:text-cyan-400 transition-colors">
                            Войти
                        </a>
                        
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-cyan-600 rounded-lg hover:bg-cyan-500 shadow-lg shadow-cyan-900/30 transition-all active:scale-95">
                                Регистрация
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </div>
    </div>
</header>