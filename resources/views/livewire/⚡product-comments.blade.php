<?php

use Livewire\Component;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $productId;
    public $newCommentText = '';
    public $replyingTo = null;
    public $replyText = '';

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function postComment()
    {
        if (!Auth::check()) return redirect()->route('login');
        
        $this->validate(['newCommentText' => 'required|min:2']);

        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $this->productId,
            'content' => $this->newCommentText
        ]);

        $this->newCommentText = '';
    }

    public function postReply($parentId)
    {
        if (!Auth::check()) return redirect()->route('login');

        $this->validate(['replyText' => 'required|min:1']);

        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $this->productId,
            'parent_id' => $parentId,
            'content' => $this->replyText
        ]);

        $this->replyText = '';
        $this->replyingTo = null;
    }

    public function getCommentsProperty()
    {
        return Comment::where('product_id', $this->productId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
    }
};
?>

<div class="mt-16 bg-slate-900 border-t border-slate-800 pt-12">
    <h3 class="text-2xl font-bold text-white mb-8">
        {{ $this->comments->count() }} комментариев
    </h3>

    {{-- Поле ввода основного комментария --}}
    @auth
        <div class="flex gap-4 mb-10">
            <div class="w-10 h-10 rounded-full bg-cyan-600 flex-shrink-0 flex items-center justify-center font-bold text-white">
                {{ Str::upper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <input wire:model="newCommentText" wire:keydown.enter="postComment" type="text" placeholder="Введите комментарий..." 
                    class="w-full bg-transparent border-b border-slate-700 focus:border-cyan-500 outline-none pb-2 text-slate-200 transition-all">
                <div class="flex justify-end mt-2">
                    <button wire:click="postComment" class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2 rounded-full text-sm font-bold text-white transition">Отправить</button>
                </div>
            </div>
        </div>
    @else
        <p class="text-slate-500 mb-10 text-center py-4 bg-slate-800/50 rounded-xl">
            Чтобы оставить комментарий, <a href="{{ route('login') }}" class="text-cyan-400 hover:underline">войдите</a>.
        </p>
    @endauth

    {{-- Список комментариев --}}
    <div class="space-y-8 pb-12">
        @foreach($this->comments as $comment)
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-slate-700 flex-shrink-0 flex items-center justify-center text-sm font-bold text-white border border-slate-600">
                    {{ Str::upper(substr($comment->user->name, 0, 1)) }}
                </div>

                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-bold text-slate-200">{{ $comment->user->name }}</span>
                        <span class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed mb-2">{{ $comment->content }}</p>
                    
                    <button wire:click="$set('replyingTo', {{ $comment->id }})" class="text-xs font-bold text-slate-500 hover:text-white transition">ОТВЕТИТЬ</button>

                    @if($replyingTo === $comment->id)
                        <div class="mt-4 flex gap-3">
                            <div class="flex-1">
                                <input wire:model="replyText" type="text" wire:keydown.enter="postReply({{ $comment->id }})" placeholder="Введите ответ..." 
                                    class="w-full bg-transparent border-b border-slate-700 focus:border-cyan-500 outline-none pb-1 text-sm text-slate-200">
                                <div class="flex justify-end gap-2 mt-2">
                                    <button wire:click="$set('replyingTo', null)" class="text-xs font-bold text-slate-400 p-2">ОТМЕНА</button>
                                    <button wire:click="postReply({{ $comment->id }})" class="bg-cyan-600 hover:bg-cyan-500 px-4 py-1.5 rounded-full text-xs font-bold text-white">ОТВЕТИТЬ</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Ответы --}}
                    @if($comment->replies->count() > 0)
                        <div class="mt-4 space-y-6 border-l border-slate-800 ml-2 pl-6">
                            @foreach($comment->replies as $reply)
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-800 flex-shrink-0 flex items-center justify-center text-xs border border-slate-700 text-cyan-400">
                                        {{ Str::upper(substr($reply->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-bold text-slate-200">{{ $reply->user->name }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-slate-300 text-sm">{{ $reply->content }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>