<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Community extends Component
{
    use WithFileUploads;

    public string $body = '';
    public $image = null;
    public string $sort = 'latest';
    public int $perPage = 10;

    public function publish()
    {
        if (! auth()->check()) {
            return $this->redirect(route('login'));
        }
        $this->validate([
            'body' => 'required|string|max:2000',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
        $key = 'post|'.auth()->id();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('body', __('You are posting too fast. Please wait a moment.'));

            return;
        }
        RateLimiter::hit($key, 60);

        Post::create([
            'user_id' => auth()->id(),
            'body' => trim($this->body),
            'image' => $this->image ? $this->image->store('posts', 'public') : null,
        ]);
        $this->reset('body', 'image');
        $this->sort = 'latest';
    }

    #[On('post-deleted')]
    public function refresh(): void
    {
    }

    public function more(): void
    {
        $this->perPage += 10;
    }

    public function render()
    {
        $q = Post::query();
        if ($this->sort === 'popular') {
            $q->withCount('likes')->orderByDesc('likes_count');
        }
        $ids = $q->orderByDesc('id')->take($this->perPage)->get()->pluck('id');

        return view('livewire.community', ['ids' => $ids, 'hasMore' => Post::count() > $this->perPage]);
    }
}
