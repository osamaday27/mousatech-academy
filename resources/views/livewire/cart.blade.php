<div>
  <x-page-header :title="__('Your cart')" />
  <div class="mx-auto max-w-4xl px-4 py-10">
  @if($courses->isEmpty())
    <div class="mt-8 border border-silver p-10 text-center">
      <p class="text-steel">{{ __('Your cart is empty.') }}</p>
      <a href="{{ route('courses.index') }}" class="btn btn-gold mt-5">{{ __('Browse courses') }}</a>
    </div>
  @else
    <div class="mt-8 divide-y divide-silver border border-silver">
      @foreach($courses as $c)
        <div wire:key="cart-{{ $c->id }}" class="flex items-center gap-4 p-4">
          <img src="{{ $c->imageUrl() }}" alt="" class="h-16 w-28 object-cover">
          <div class="flex-1">
            <a href="{{ route('courses.show', $c) }}" class="font-semibold hover:text-gold-dark">{{ $c->tr('title') }}</a>
            <div class="text-sm text-steel">{{ $c->instructor_name }}</div>
          </div>
          <div class="text-end">
            <div class="font-bold text-gold-dark">{{ \App\Support\Money::fmt($c->currentPrice()) }}</div>
            @if($c->hasDiscount())<div class="text-xs text-steel line-through">{{ \App\Support\Money::fmt($c->price) }}</div>@endif
            <button type="button" wire:click="remove({{ $c->id }})" class="mt-1 text-xs text-steel underline">{{ __('Remove') }}</button>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-6 ms-auto max-w-sm space-y-2 border border-silver p-5">
      <div class="flex justify-between text-sm"><span>{{ __('Subtotal') }}</span><span>{{ \App\Support\Money::fmt($q['subtotal']) }}</span></div>
      <div class="flex justify-between border-t border-silver pt-2 text-lg font-bold"><span>{{ __('Total') }}</span><span class="text-gold-dark">{{ \App\Support\Money::fmt($q['total']) }}</span></div>
      <a href="{{ route('checkout') }}" class="btn btn-gold mt-3 w-full">{{ __('Proceed to checkout') }}</a>
      <a href="{{ route('courses.index') }}" class="block pt-1 text-center text-sm text-steel underline">{{ __('Continue shopping') }}</a>
    </div>
  @endif
</div>
</div>
