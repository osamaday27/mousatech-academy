<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public User $user;
    public bool $editing = false;
    public string $name = '';
    public string $bio = '';
    public $avatar = null;
    public int $perPage = 10;

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    protected function isOwner(): bool
    {
        return auth()->check() && auth()->id() === $this->user->id;
    }

    public function edit(): void
    {
        abort_unless($this->isOwner(), 403);
        $this->name = $this->user->name;
        $this->bio = (string) $this->user->bio;
        $this->editing = true;
    }

    public function save(): void
    {
        abort_unless($this->isOwner(), 403);
        $this->validate([
            'name' => 'required|string|max:120',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $u = $this->user;
        $u->name = $this->name;
        $u->bio = trim($this->bio) ?: null;
        if ($this->avatar) {
            if ($u->avatar) {
                Storage::disk('public')->delete($u->avatar);
            }
            $u->avatar = $this->avatar->store('avatars', 'public');
        }
        $u->save();
        $this->reset('avatar');
        $this->editing = false;
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
        $own = Post::where('user_id', $this->user->id);

        return view('livewire.profile', [
            'owner' => $this->isOwner(),
            'ids' => (clone $own)->latest('id')->take($this->perPage)->pluck('id'),
            'postsCount' => (clone $own)->count(),
            'likesReceived' => PostLike::whereIn('post_id', Post::where('user_id', $this->user->id)->select('id'))->count(),
            'coursesCount' => $this->user->enrollments()->count(),
            'roles' => $this->user->roles()->pluck('name'),
        ]);
    }
}
