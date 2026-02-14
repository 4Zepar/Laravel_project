<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-white mb-6">Ваши лайки</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse(Auth::user()->likedProducts as $product)
                        <div class="bg-slate-800 p-4 rounded-xl border border-slate-700 text-white">
                            <p class="font-bold">{{ $product->name }}</p>
                            <a href="{{ route('product.show', $product->id) }}" class="text-cyan-400 text-sm">Посмотреть</a>
                        </div>
                    @empty
                        <p class="text-slate-400">Вы еще ничего не лайкнули.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
