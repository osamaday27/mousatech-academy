<div class="mx-auto max-w-7xl px-4 py-10">
  <div class="flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-3xl font-bold">{{ __('Dashboard') }}</h1>
    <div class="flex gap-2 text-sm">
      @can('orders.view')<a href="{{ route('admin.orders') }}" class="btn btn-dark">{{ __('Orders') }}</a>@endcan
      @can('courses.update')<a href="{{ route('admin.courses') }}" class="btn btn-gold">{{ __('Courses') }}</a>@endcan
    </div>
  </div>

  <div class="mt-8 grid gap-px border border-silver bg-silver sm:grid-cols-2 lg:grid-cols-4">
    @if($canMoney)
      <div class="bg-white p-6"><div class="text-sm text-steel">{{ __('Total revenue') }}</div><div class="mt-2 text-2xl font-bold text-gold-dark">{{ \App\Support\Money::fmt($revenue) }}</div></div>
    @endif
    <div class="bg-white p-6"><div class="text-sm text-steel">{{ __('Students') }}</div><div class="mt-2 text-2xl font-bold">{{ $students }}</div></div>
    <div class="bg-white p-6"><div class="text-sm text-steel">{{ __('Courses') }}</div><div class="mt-2 text-2xl font-bold">{{ $courses }}</div></div>
    @if($canMoney)
      <div class="bg-white p-6"><div class="text-sm text-steel">{{ __('Orders') }}</div><div class="mt-2 text-2xl font-bold">{{ $orders }}</div></div>
    @endif
  </div>

  @if($canMoney)
    @if($pending > 0)<a href="{{ route('admin.orders', ['status' => 'awaiting_verification']) }}" class="mt-6 block border border-gold bg-ink p-4 text-sm text-gold">{{ $pending }} {{ __('payments are waiting for your review') }}</a>@endif
    <h2 class="mt-10 text-xl font-bold">{{ __('Recent orders') }}</h2>
    <div class="mt-3 divide-y divide-silver border border-silver text-sm">
      @forelse($recent as $o)
        <div wire:key="r-{{ $o->id }}" class="flex items-center justify-between p-3">
          <span>{{ $o->number }} · {{ $o->user->name }}</span>
          <span class="flex items-center gap-3"><span class="px-2 py-0.5 text-xs {{ $o->badge() }}">{{ __(\Illuminate\Support\Str::headline($o->status)) }}</span>{{ \App\Support\Money::fmt($o->total) }}</span>
        </div>
      @empty
        <p class="p-6 text-center text-steel">{{ __('No orders yet.') }}</p>
      @endforelse
    </div>
  @endif
</div>
