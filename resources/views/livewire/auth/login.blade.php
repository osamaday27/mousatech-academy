<div class="mx-auto max-w-md px-4 py-14">
  <h1 class="text-3xl font-bold">{{ __('Log in') }}</h1>
  <form wire:submit="login" class="mt-8 space-y-4">
    <div><label class="label" for="identity">{{ __('Email or phone') }}</label><input id="identity" type="text" wire:model="identity" class="field" dir="ltr" autocomplete="username">@error('identity')<span class="err">{{ $message }}</span>@enderror</div>
    <div><label class="label" for="password">{{ __('Password') }}</label><input id="password" type="password" wire:model="password" class="field" dir="ltr" autocomplete="current-password">@error('password')<span class="err">{{ $message }}</span>@enderror</div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="remember"> {{ __('Remember me') }}</label>
    <button class="btn btn-gold w-full" wire:loading.attr="disabled">{{ __('Log in') }}</button>
  </form>
  <p class="mt-6 text-sm text-steel">{{ __('New here?') }} <a href="{{ route('register') }}" class="font-semibold text-ink underline">{{ __('Create an account') }}</a></p>
</div>
