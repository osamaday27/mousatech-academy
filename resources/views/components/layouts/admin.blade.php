<!DOCTYPE html>
@php($rtl = app()->getLocale() === 'ar')
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __('admin.panel') }} - {{ config('app.name') }}</title>
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap{{ $rtl ? '.rtl' : '' }}.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
  <style>
    :root { --navy:#0E1B2E; --navy2:#16263E; --navy3:#1F3556; --gold:#D4AF37; --gold-light:#F5D5A2; --gold-dark:#A8841F; --bs-body-font-family:'Cairo',sans-serif; --bs-body-bg:#F4F6FA; }
    body { font-family:'Cairo',sans-serif; }
    .admin-sidebar { width:260px; min-height:100vh; background:var(--navy); color:#fff; }
    .admin-sidebar .nav-link { color:#c9d1e0; border-radius:.75rem; padding:.7rem 1rem; display:flex; align-items:center; gap:.65rem; }
    .admin-sidebar .nav-link:hover { background:rgba(255,255,255,.08); color:#fff; }
    .admin-sidebar .nav-link.active { background:linear-gradient(135deg,var(--gold-light),var(--gold)); color:var(--navy); font-weight:700; }
    .admin-sidebar .mdi { font-size:1.25rem; }
    .text-gold { color:var(--gold-dark) !important; }
    .btn-gold { background:linear-gradient(135deg,var(--gold-light),var(--gold) 55%,#B8922A); color:var(--navy); border:0; font-weight:600; }
    .btn-gold:hover { filter:brightness(1.06); color:var(--navy); }
    .card { border:0; border-radius:1rem; box-shadow:0 6px 24px rgba(14,27,46,.07); }
    .form-control, .form-select { border-radius:.75rem; }
    .form-control:focus, .form-select:focus { border-color:var(--gold); box-shadow:0 0 0 .2rem rgba(212,175,55,.2); }
    .field-preview { background:#fff8e6; border:1px dashed var(--gold); border-radius:.75rem; padding:.45rem .8rem; font-size:.85rem; color:var(--navy2); display:flex; gap:.5rem; align-items:center; word-break:break-word; }
    .field-preview .mdi { color:var(--gold-dark); }
    .table > :not(caption) > * > * { padding:.85rem .9rem; vertical-align:middle; }
    .table thead th { background:var(--navy); color:#fff; font-weight:600; white-space:nowrap; }
    .table thead th:first-child { border-start-start-radius:.75rem; } .table thead th:last-child { border-start-end-radius:.75rem; }
    .page-link { color:var(--navy2); } .page-item.active .page-link { background:var(--navy); border-color:var(--navy); color:var(--gold-light); }
    .stat-icon { width:52px; height:52px; border-radius:1rem; display:grid; place-items:center; background:rgba(212,175,55,.15); color:var(--gold-dark); font-size:1.7rem; }
  </style>
  @livewireStyles
</head>
<body>
<div class="d-flex">
  <nav id="sidebar" class="admin-sidebar offcanvas-lg offcanvas-start p-3 d-flex flex-column" tabindex="-1">
    <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white mb-4 px-2">
      <img src="{{ asset('images/logo.png') }}" alt="MousaTech" width="46" height="46">
      <span class="fw-bold lh-1">MousaTech<br><small class="text-warning fw-normal">{{ __('admin.panel') }}</small></span>
    </a>
    <ul class="nav flex-column gap-1">
      @php
$items = [
        ['dashboard.view', 'admin.dashboard', 'mdi-view-dashboard-outline', 'admin.nav.dashboard', 'admin.dashboard'],
        ['categories.update', 'admin.categories', 'mdi-shape-outline', 'admin.nav.categories', 'admin.categories'],
        ['courses.update', 'admin.courses', 'mdi-school-outline', 'admin.nav.courses', 'admin.courses*'],
        ['coupons.update', 'admin.coupons', 'mdi-ticket-percent-outline', 'admin.nav.coupons', 'admin.coupons'],
        ['orders.view', 'admin.orders', 'mdi-cart-outline', 'admin.nav.orders', 'admin.orders'],
];
@endphp
      @foreach($items as [$perm, $route, $icon, $label, $match])
        @can($perm)
          <li><a class="nav-link {{ request()->routeIs($match) ? 'active' : '' }}" href="{{ route($route) }}"><i class="mdi {{ $icon }}"></i>{{ __($label) }}</a></li>
        @endcan
      @endforeach
      <li class="mt-3 border-top border-secondary pt-3"><a class="nav-link" href="{{ route('community') }}"><i class="mdi mdi-forum-outline"></i>{{ __('admin.nav.community') }}</a></li>
      <li><a class="nav-link" href="{{ route('home') }}"><i class="mdi mdi-open-in-new"></i>{{ __('admin.nav.view_site') }}</a></li>
    </ul>
  </nav>

  <div class="flex-grow-1 min-vw-0">
    <header class="bg-white border-bottom px-3 px-lg-4 py-2 d-flex align-items-center justify-content-between">
      <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-label="menu"><i class="mdi mdi-menu"></i></button>
      <div class="ms-auto d-flex align-items-center gap-3">
        <a class="btn btn-sm btn-outline-dark" href="{{ route('lang', $rtl ? 'en' : 'ar') }}"><i class="mdi mdi-translate"></i> {{ $rtl ? 'English' : 'العربية' }}</a>
        <span class="small text-muted d-none d-md-inline"><i class="mdi mdi-account-circle-outline"></i> {{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-danger"><i class="mdi mdi-logout"></i> {{ __('admin.logout') }}</button></form>
      </div>
    </header>
    <main class="p-3 p-lg-4">{{ $slot }}</main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@livewireScripts
{{-- ✅ SweetAlert2 delete confirmation (Livewire 3: dispatch replaces the old emit) --}}
<script>
function confirmDelete(id, eventName = 'deleteConfirmed') {
  Swal.fire({
    title: @json(__('admin.confirm.title')),
    text: @json(__('admin.confirm.text')),
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    cancelButtonColor: '#0d6efd',
    confirmButtonText: @json(__('admin.confirm.yes')),
    cancelButtonText: @json(__('admin.confirm.cancel'))
  }).then((result) => {
    if (result.isConfirmed) {
      Livewire.dispatch(eventName, { id: id });
      Swal.fire(@json(__('admin.confirm.done_title')), @json(__('admin.confirm.done_text')), 'success');
    }
  });
}
window.addEventListener('admin-toast', (e) => {
  Swal.fire({ toast: true, position: 'top-end', icon: e.detail.icon || 'success', title: e.detail.message, timer: 2200, showConfirmButton: false });
});
</script>
</body>
</html>
