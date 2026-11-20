<div class="mx-auto max-w-md px-4 py-14">
  <h1 class="text-3xl font-bold">{{ __('Create an account') }}</h1>
  <form wire:submit="register" class="mt-8 space-y-4">
    <div><label class="label" for="name">{{ __('Full name') }}</label><input id="name" type="text" wire:model="name" class="field" autocomplete="name">@error('name')<span class="err">{{ $message }}</span>@enderror</div>
    <div><label class="label" for="email">{{ __('Email') }}</label><input id="email" type="email" wire:model="email" class="field" dir="ltr" autocomplete="email">@error('email')<span class="err">{{ $message }}</span>@enderror</div>
    <div><label class="label" for="phone">{{ __('Phone') }}</label><input id="phone" type="tel" wire:model="phone" class="field" dir="ltr" autocomplete="tel">@error('phone')<span class="err">{{ $message }}</span>@enderror</div>
    <div><label class="label" for="password">{{ __('Password') }}</label><input id="password" type="password" wire:model="password" class="field" dir="ltr" autocomplete="new-password">@error('password')<span class="err">{{ $message }}</span>@enderror</div>
    <div><label class="label" for="pc">{{ __('Confirm password') }}</label><input id="pc" type="password" wire:model="password_confirmation" class="field" dir="ltr" autocomplete="new-password"></div>
    <label class="flex items-start gap-2 text-sm"><input type="checkbox" wire:model="terms" class="mt-1"> {{ __('I agree to the terms and privacy policy') }}</label>
    @error('terms')<span class="err">{{ $message }}</span>@enderror
    <button class="btn btn-gold w-full" wire:loading.attr="disabled">{{ __('Sign up') }}</button>
  </form>
  <p class="mt-6 text-sm text-steel">{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="font-semibold text-ink underline">{{ __('Log in') }}</a></p>
</div>
