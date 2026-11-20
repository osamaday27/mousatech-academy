<div>
  <h4 class="mb-4"><i class="mdi mdi-view-dashboard-outline text-gold"></i> {{ __('admin.nav.dashboard') }}</h4>
  <div class="row g-3">
    @php
$stats = [
      ['mdi-cash-multiple', 'admin.stats.revenue', \App\Support\Money::fmt($revenue), $money],
      ['mdi-account-group-outline', 'admin.stats.students', $students, true],
      ['mdi-school-outline', 'admin.stats.courses', $courses, true],
      ['mdi-cart-outline', 'admin.stats.orders', $orders, $money],
      ['mdi-forum-outline', 'admin.stats.posts', $posts, true],
];
@endphp
    @foreach($stats as [$icon, $label, $val, $show])
      @if($show)
        <div class="col-6 col-xl"><div class="card h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon"><i class="mdi {{ $icon }}"></i></div><div><div class="text-muted small">{{ __($label) }}</div><div class="fs-4 fw-bold">{{ $val }}</div></div></div></div></div>
      @endif
    @endforeach
  </div>

  @if($money)
    @if($pending > 0)
      <a href="{{ route('admin.orders') }}" class="alert alert-warning d-flex align-items-center gap-2 mt-4 text-decoration-none"><i class="mdi mdi-alert-outline fs-5"></i> {{ __('admin.pending_alert', ['n' => $pending]) }}</a>
    @endif
    <div class="card mt-4">
      <div class="card-header bg-white fw-bold py-3"><i class="mdi mdi-clock-outline text-gold"></i> {{ __('admin.recent_orders') }}</div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead><tr><th>{{ __('admin.order') }}</th><th>{{ __('admin.customer') }}</th><th>{{ __('admin.total') }}</th><th>{{ __('admin.fields.status') }}</th></tr></thead>
          <tbody>
            @forelse($recent as $o)
              <tr wire:key="ro-{{ $o->id }}"><td class="fw-semibold">{{ $o->number }}</td><td>{{ $o->user->name }}</td><td>{{ \App\Support\Money::fmt($o->total) }}</td><td><span class="badge text-bg-light border">{{ __('admin.order_status.'.$o->status) }}</span></td></tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted py-4">{{ __('admin.no_data') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @endif
</div>
