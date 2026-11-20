<a href="{{ route('cart') }}" class="relative inline-flex" aria-label="{{ __('Cart') }}">
  <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 4h2l2.4 11h10.2L20 7H6.2"/><circle cx="9" cy="20" r="1.2"/><circle cx="17" cy="20" r="1.2"/></svg>
  @if($n > 0)<span class="absolute -top-2 -end-2 min-w-[1.1rem] bg-gold px-1 text-center text-[10px] font-bold text-ink">{{ $n }}</span>@endif
</a>
