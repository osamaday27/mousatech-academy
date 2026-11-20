<div>
  <h4 class="mb-4"><i class="mdi mdi-cart-outline text-gold"></i> {{ __('admin.nav.orders') }}</h4>
  <div class="card">
    <div class="card-body">
      <div class="row g-2 mb-3">
        <div class="col-md-6"><div class="input-group"><span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span><input type="search" class="form-control" wire:model.live.debounce.300ms="search" placeholder="{{ __('admin.search_order') }}"></div></div>
        <div class="col-md-3"><select class="form-select" wire:model.live="status"><option value="">{{ __('admin.all_statuses') }}</option>@foreach($statuses as $st)<option value="{{ $st }}">{{ __('admin.order_status.'.$st) }}</option>@endforeach</select></div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead><tr><th>{{ __('admin.order') }}</th><th>{{ __('admin.customer') }}</th><th>{{ __('admin.total') }}</th><th>{{ __('admin.method') }}</th><th>{{ __('admin.fields.status') }}</th><th>{{ __('admin.actions') }}</th></tr></thead>
          <tbody>
            @forelse($rows as $o)
              <tr wire:key="o-{{ $o->id }}">
                <td><div class="fw-semibold">{{ $o->number }}</div><small class="text-muted">{{ $o->items->pluck('title')->join(', ') }}</small></td>
                <td>{{ $o->user->name }}<div class="small text-muted" dir="ltr">{{ $o->user->email }}</div></td>
                <td>{{ \App\Support\Money::fmt($o->total) }}</td>
                <td>{{ $o->payment_method }}</td>
                <td><span class="badge {{ in_array($o->status, ['paid']) ? 'text-bg-success' : (in_array($o->status, ['failed', 'cancelled']) ? 'text-bg-secondary' : 'text-bg-warning') }}">{{ __('admin.order_status.'.$o->status) }}</span></td>
                <td class="text-nowrap">
                  @if($o->payment?->proof_path)<a class="btn btn-sm btn-outline-dark" target="_blank" href="{{ route('admin.proof', $o->payment) }}"><i class="mdi mdi-receipt-text-outline"></i> {{ __('admin.receipt') }}</a>@endif
                  @can('payments.verify')
                    @if(in_array($o->status, ['pending', 'awaiting_verification']))
                      <button type="button" class="btn btn-sm btn-gold" wire:click="approve({{ $o->id }})" wire:confirm="{{ __('admin.confirm_approve') }}"><i class="mdi mdi-check"></i> {{ __('admin.approve') }}</button>
                      <button type="button" class="btn btn-sm btn-outline-danger" wire:click="reject({{ $o->id }})" wire:confirm="{{ __('admin.confirm_reject') }}"><i class="mdi mdi-close"></i> {{ __('admin.reject') }}</button>
                    @endif
                  @endcan
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-5"><i class="mdi mdi-inbox-outline fs-1 d-block"></i>{{ __('admin.no_data') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <x-admin.pager :rows="$rows" />
    </div>
  </div>
</div>
