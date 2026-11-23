<div class="mx-auto max-w-3xl px-4 py-10">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">{{ __('Order') }} {{ $order->number }}</h1>
    <span class="px-3 py-1 text-xs font-semibold {{ $order->badge() }}">{{ __(\Illuminate\Support\Str::headline($order->status)) }}</span>
  </div>

  @if($order->status === 'awaiting_verification')
    <p class="mt-4 border border-gold bg-mist p-4 text-sm text-ink">{{ __('We received your receipt. Your courses unlock as soon as we confirm the transfer.') }}</p>
  @elseif($order->status === 'paid')
    <p class="mt-4 border border-gold bg-mist p-4 text-sm text-ink">{{ __('Payment confirmed. Your courses are ready.') }} <a class="underline" href="{{ route('my-courses') }}">{{ __('My courses') }}</a></p>
  @elseif($order->status === 'failed')
    <p class="mt-4 border border-silver p-4 text-sm">{{ __('This payment was not accepted. Contact support on WhatsApp if you think this is a mistake.') }}</p>
  @endif

  <div class="mt-6 divide-y divide-silver border border-silver text-sm">
    @foreach($items as $i)<div class="flex justify-between p-3"><span>{{ $i->title }}</span><span>{{ \App\Support\Money::fmt($i->price) }}</span></div>@endforeach
    @if($order->discount > 0)<div class="flex justify-between p-3"><span>{{ __('Discount') }} {{ $order->coupon_code }}</span><span>-{{ \App\Support\Money::fmt($order->discount) }}</span></div>@endif
    <div class="flex justify-between p-3 text-base font-bold"><span>{{ __('Total') }}</span><span class="text-ink">{{ \App\Support\Money::fmt($order->total) }}</span></div>
  </div>
</div>
