<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class PostCard extends Component
{
    public int $postId;
    public bool $open = false;
    public string $comment = '';
    public bool $editing = false;
    public string $editBody = '';

    public function mount(int $postId): void
    {
        $this->postId = $postId;
    }

    protected function find(): ?Post
    {
        return Post::find($this->postId);
    }

    protected function guest(): bool
    {
        if (auth()->check()) {
            return false;
        }
        $this->redirect(route('login'));

        return true;
    }

    protected function canManage(Post $p): bool
    {
        return auth()->check() && ($p->user_id === auth()->id() || auth()->user()->can('posts.moderate'));
    }

    public function like(): void
    {
        if ($this->guest() || ! ($p = $this->find())) {
            return;
        }
        $w = ['post_id' => $p->id, 'user_id' => auth()->id()];
        try {
            $l = PostLike::where($w)->first();
            $l ? $l->delete() : PostLike::create($w);
        } catch (QueryException) {
            // double click race on the unique index: ignore
        }
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function addComment(): void
    {
        if ($this->guest() || ! $this->find()) {
            return;
        }
        $this->validate(['comment' => 'required|string|max:1000']);
        $key = 'comment|'.auth()->id();
        if (RateLimiter::tooManyAttempts($key, 20)) {
            $this->addError('comment', __('You are posting too fast. Please wait a moment.'));

            return;
        }
        RateLimiter::hit($key, 60);
        Comment::create(['post_id' => $this->postId, 'user_id' => auth()->id(), 'body' => trim($this->comment)]);
        $this->reset('comment');
    }

    public function deleteComment(int $id): void
    {
        $c = Comment::where('post_id', $this->postId)->findOrFail($id);
        abort_unless(auth()->check() && ($c->user_id === auth()->id() || auth()->user()->can('posts.moderate')), 403);
        $c->delete();
    }

    public function startEdit(): void
    {
        $p = $this->find();
        abort_unless($p && auth()->check() && $p->user_id === auth()->id(), 403);
        $this->editBody = $p->body;
        $this->editing = true;
    }

    public function saveEdit(): void
    {
        $p = $this->find();
        abort_unless($p && auth()->check() && $p->user_id === auth()->id(), 403);
        $this->validate(['editBody' => 'required|string|max:2000']);
        $p->update(['body' => trim($this->editBody)]);
        $this->editing = false;
    }

    public function deletePost(): void
    {
        $p = $this->find();
        if (! $p) {
            return;
        }
        abort_unless($this->canManage($p), 403);
        if ($p->image) {
            Storage::disk('public')->delete($p->image);
        }
        $p->delete();
        $this->dispatch('post-deleted');
    }

    public function render()
    {
        $p = Post::with('user')->withCount(['likes', 'comments'])->find($this->postId);
        if (! $p) {
            return view('livewire.empty');
        }

        return view('livewire.post-card', [
            'p' => $p,
            'liked' => auth()->check() && PostLike::where('post_id', $p->id)->where('user_id', auth()->id())->exists(),
            'comments' => $this->open
                ? Comment::with('user')->where('post_id', $p->id)->latest('id')->take(30)->get()->reverse()->values()
                : collect(),
            'manage' => $this->canManage($p),
            'owner' => auth()->check() && $p->user_id === auth()->id(),
        ]);
    }
}
