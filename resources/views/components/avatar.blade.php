@props(['user', 'size' => 'h-10 w-10'])
@if($user->avatarUrl())
  <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" loading="lazy" class="{{ $size }} shrink-0 object-cover">
@else
  <span class="{{ $size }} inline-flex shrink-0 items-center justify-center bg-gold font-bold text-ink" aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
@endif
