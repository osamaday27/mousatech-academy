<div>
  <x-page-header :title="__('My orders')" />
  <div class="mx-auto max-w-4xl px-4 py-10">
  <div class="mt-6 divide-y divide-silver border border-silver">
    @forelse($orders as $o)
      <a wire:key="o-{{ $o->id }}" href="{{ route('orders.show', $o) }}" class="flex items-center justify-between p-4 hover:bg-silver/20">
        <div><div class="font-semibold">{{ $o->number }}</div><div class="text-xs text-steel">{{ $o->created_at->format('Y-m-d') }}</div></div>
        <div class="flex items-center gap-4"><span class="px-2 py-1 text-xs font-semibold {{ $o->badge() }}">{{ __(\Illuminate\Support\Str::headline($o->status)) }}</span><span class="font-bold">{{ \App\Support\Money::fmt($o->total) }}</span></div>
      </a>
    @empty
      <p class="p-8 text-center text-steel">{{ __('You have no orders yet.') }}</p>
    @endforelse
  </div>
</div>
</div>
