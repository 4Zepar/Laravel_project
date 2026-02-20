<?php
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    public $userId, $name, $email, $password;
    public $showModal = false;

    public function openModal($id = null) {
        $this->resetErrorBag();
        if ($id) {
            $user = User::find($id);
            $this->userId = $id;
            $this->name = $user->name;
            $this->email = $user->email;
        } else {
            $this->reset(['userId', 'name', 'email', 'password']);
        }
        $this->showModal = true;
    }

    public function save() {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);
        
        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'password']);
    }

    public function delete($id) {
        if ($id !== auth()->id()) User::destroy($id);
    }
}; ?>

{{-- Весь твой основной контент остается как есть --}}
<div class="bg-slate-800 border border-slate-700/50 rounded-3xl p-6 h-full flex flex-col shadow-xl backdrop-blur-md">
    <div class="flex justify-between items-center mb-4 flex-none">
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Пользователи</h3>
        <button wire:click="openModal()" class="bg-cyan-600 hover:bg-cyan-500 text-[10px] px-3 py-1.5 rounded-xl font-bold text-white transition-all active:scale-95 shadow-lg shadow-cyan-900/20">
            + ДОБАВИТЬ
        </button>
    </div>
    
    <div class="flex-1 overflow-y-auto custom-scrollbar pr-2">
        <table class="w-full text-sm text-left text-slate-300">
            <thead>
                <tr class="text-slate-400 border-b border-slate-700">
                    <th class="pb-4 font-medium uppercase text-[10px]">Данные</th>
                    <th class="pb-4 font-medium text-right uppercase text-[10px]">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @foreach(App\Models\User::latest()->get() as $user)
                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-3">
                            <div class="font-bold text-white leading-tight">{{ $user->name }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">{{ $user->email }}</div>
                        </td>
                        <td class="py-3 text-right space-x-2">
                            <button wire:click="openModal({{ $user->id }})" class="text-cyan-400">✎</button>
                            @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})" class="text-red-500/70">✕</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- МАГИЯ ТЕЛЕПОРТА --}}
    @if($showModal)
    @teleport('#modal-container')
        {{-- Заменил backdrop-blur-md на backdrop-blur-sm и сделал фон чуть прозрачнее --}}
        <div class="fixed inset-0 w-screen h-screen z-[9999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
            
            {{-- Само окно модалки --}}
            <div class="bg-slate-900 border border-slate-700 w-full max-w-md rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-8 relative animate-in fade-in zoom-in duration-200">
                
                <button wire:click="$set('showModal', false)" class="absolute top-6 right-6 text-slate-500 hover:text-white transition-colors text-2xl">
                    ✕
                </button>

                <h4 class="text-2xl font-bold text-white mb-8">{{ $userId ? 'Правка пользователя' : 'Новый пользователь' }}</h4>
                
                <div class="space-y-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1 tracking-wider">Имя</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-4 transition-all outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1 tracking-wider">Email</label>
                        <input type="email" wire:model="email" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-4 transition-all outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1 tracking-wider">Пароль</label>
                        <input type="password" wire:model="password" class="w-full bg-slate-950 border-slate-800 rounded-2xl text-white focus:ring-2 focus:ring-cyan-500 px-5 py-4 transition-all outline-none placeholder:text-slate-700" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex gap-4 mt-10">
                    <button wire:click="$set('showModal', false)" class="flex-1 px-6 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl font-bold uppercase text-xs tracking-widest transition-all">
                        Отмена
                    </button>
                    <button wire:click="save" class="flex-1 px-6 py-4 bg-cyan-600 hover:bg-cyan-500 text-white rounded-2xl font-bold uppercase text-xs tracking-widest shadow-lg shadow-cyan-900/30 transition-all active:scale-95">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    @endteleport
@endif
</div>