<div class="mx-auto grid max-w-5xl gap-10 px-4 py-10 md:grid-cols-5">
  <div class="md:col-span-3">
    <h1 class="text-3xl font-bold">{{ __('Checkout') }}</h1>
    @error('cart')<div class="mt-4 border border-red-300 bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror

    <h2 class="mt-8 font-semibold">{{ __('Payment method') }}</h2>
    <div class="mt-3 space-y-3">
      <label class="flex cursor-pointer items-start gap-3 border border-silver p-4">
        <input type="radio" wire:model.live="method" value="vodafone" class="mt-1">
        <span><span class="font-semibold">Vodafone Cash</span>
          <span class="block text-sm text-steel">{{ __('Transfer the total, then upload your receipt. We confirm it manually.') }}</span></span>
      </label>
      @if(config('academy.xpay_enabled'))
        <label class="flex cursor-pointer items-start gap-3 border border-silver p-4">
          <input type="radio" wire:model.live="method" value="xpay" class="mt-1">
          <span class="font-semibold">X-Pay</span>
        </label>
      @endif
    </div>

    @if($method === 'vodafone' && $q['total'] > 0)
      <div class="mt-5 border border-gold bg-ink p-5 text-sm text-silver">
        @if(config('academy.vodafone_number'))
          <p>{{ __('Send :amount to this Vodafone Cash number', ['amount' => \App\Support\Money::fmt($q['total'])]) }}:</p>
          <p dir="ltr" class="mt-1 text-xl font-bold text-gold">{{ config('academy.vodafone_number') }}</p>
        @else
          <p>{{ __('Ask us on WhatsApp for the transfer number.') }}
            <a class="text-gold underline" href="https://wa.me/{{ config('academy.whatsapp') }}" target="_blank" rel="noopener">+{{ config('academy.whatsapp') }}</a></p>
        @endif
      </div>
      <div class="mt-5">
        <label class="label" for="proof">{{ __('Transfer receipt (image)') }}</label>
        <input id="proof" type="file" wire:model="proof" accept="image/png,image/jpeg,image/webp" class="field">
        <div wire:loading wire:target="proof" class="mt-1 text-xs text-steel">{{ __('Uploading...') }}</div>
        @error('proof')<span class="err">{{ $message }}</span>@enderror
      </div>
    @endif

    <button type="button" wire:click="place" wire:loading.attr="disabled" wire:target="place,proof" class="btn btn-gold mt-8 w-full md:w-auto">{{ __('Place order') }}</button>
  </div>

  <aside class="h-fit border border-silver p-5 md:col-span-2">
    <h2 class="font-semibold">{{ __('Order summary') }}</h2>
    <div class="mt-3 divide-y divide-silver text-sm">
      @foreach($courses as $c)
        <div wire:key="co-{{ $c->id }}" class="flex justify-between gap-3 py-2"><span>{{ $c->tr('title') }}</span><span class="whitespace-nowrap">{{ \App\Support\Money::fmt($c->currentPrice()) }}</span></div>
      @endforeach
    </div>

    <form wire:submit="apply" class="mt-4 flex gap-2">
      <input type="text" wire:model="coupon" placeholder="{{ __('Coupon code') }}" class="field uppercase" dir="ltr">
      <button class="btn btn-dark">{{ __('Apply') }}</button>
    </form>
    @if($applied && $q['error'])<span class="err">{{ __($q['error']) }}</span>@endif
    @if($q['coupon'])<span class="mt-1 block text-xs font-semibold text-gold-dark">{{ $q['coupon']->code }} {{ __('applied') }}</span>@endif

    <div class="mt-4 space-y-1 border-t border-silver pt-3 text-sm">
      <div class="flex justify-between"><span>{{ __('Subtotal') }}</span><span>{{ \App\Support\Money::fmt($q['subtotal']) }}</span></div>
      @if($q['discount'] > 0)<div class="flex justify-between"><span>{{ __('Discount') }}</span><span>-{{ \App\Support\Money::fmt($q['discount']) }}</span></div>@endif
      <div class="flex justify-between pt-2 text-lg font-bold"><span>{{ __('Total') }}</span><span class="text-gold-dark">{{ \App\Support\Money::fmt($q['total']) }}</span></div>
    </div>
  </aside>
</div>
