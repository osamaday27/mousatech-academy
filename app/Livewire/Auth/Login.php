<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public string $identity = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate(['identity' => 'required|string|max:190', 'password' => 'required|string']);

        $key = 'login|'.Str::lower($this->identity).'|'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('identity', __('Too many attempts. Try again in :s seconds.', ['s' => RateLimiter::availableIn($key)]));

            return;
        }

        $field = filter_var($this->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        if (! Auth::attempt([$field => $this->identity, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key, 60);
            $this->addError('identity', __('Invalid credentials'));

            return;
        }

        RateLimiter::clear($key);
        session()->regenerate();

        return $this->redirectIntended(route('my-courses'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
