<div class="mx-auto max-w-7xl px-4 py-10">
  <h1 class="text-3xl font-bold">{{ __('Orders') }}</h1>
  @error('order')<div class="mt-3 border border-red-300 bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror
  <div class="mt-6 grid gap-3 sm:grid-cols-3">
    <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('Order number, name or email') }}" class="field sm:col-span-2">
    <select wire:model.live="status" class="field">
      <option value="">{{ __('All statuses') }}</option>
      @foreach($statuses as $s)<option value="{{ $s }}">{{ __(\Illuminate\Support\Str::headline($s)) }}</option>@endforeach
    </select>
  </div>

  <div class="mt-6 overflow-x-auto border border-silver">
    <table class="w-full min-w-[720px] text-sm">
      <thead class="bg-ink text-start text-white"><tr>
        @foreach(['Order', 'Customer', 'Total', 'Method', 'Status', ''] as $h)<th class="p-3 text-start font-medium">{{ __($h) }}</th>@endforeach
      </tr></thead>
      <tbody class="divide-y divide-silver">
        @forelse($orders as $o)
          <tr wire:key="ao-{{ $o->id }}">
            <td class="p-3 font-semibold">{{ $o->number }}<div class="text-xs font-normal text-steel">{{ $o->items->pluck('title')->join(', ') }}</div></td>
            <td class="p-3">{{ $o->user->name }}<div class="text-xs text-steel">{{ $o->user->email }}</div></td>
            <td class="p-3">{{ \App\Support\Money::fmt($o->total) }}</td>
            <td class="p-3">{{ $o->payment_method }}</td>
            <td class="p-3"><span class="px-2 py-0.5 text-xs {{ $o->badge() }}">{{ __(\Illuminate\Support\Str::headline($o->status)) }}</span></td>
            <td class="space-x-2 p-3 text-end rtl:space-x-reverse">
              @if($o->payment?->proof_path)<a href="{{ route('admin.proof', $o->payment) }}" target="_blank" class="underline">{{ __('Receipt') }}</a>@endif
              @can('payments.verify')
                @if(in_array($o->status, ['pending', 'awaiting_verification']))
                  <button type="button" wire:click="approve({{ $o->id }})" wire:confirm="{{ __('Confirm this payment?') }}" class="btn btn-gold !px-3 !py-1">{{ __('Approve') }}</button>
                  <button type="button" wire:click="reject({{ $o->id }})" wire:confirm="{{ __('Reject this payment?') }}" class="btn btn-line !px-3 !py-1">{{ __('Reject') }}</button>
                @endif
              @endcan
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="p-8 text-center text-steel">{{ __('No orders found.') }}</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-6">{{ $orders->links() }}</div>
</div>
