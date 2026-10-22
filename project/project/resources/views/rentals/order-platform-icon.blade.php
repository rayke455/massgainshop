@php
    $platformTitle = strtolower($title);
@endphp
@if(str_contains($platformTitle, 'instagram'))
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
@elseif(str_contains($platformTitle, 'facebook'))
<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 22v-9h3l.5-4H14V7c0-1 .4-2 2-2h2V1.5L15 1c-3 0-5 2-5 5v3H7v4h3v9Z"/></svg>
@elseif(str_contains($platformTitle, 'youtube'))
<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 5c3-1 15-1 18 0 2 2 2 12 0 14-3 1-15 1-18 0-2-2-2-12 0-14Zm7 3v8l7-4Z"/></svg>
@elseif(str_contains($platformTitle, 'tiktok'))
<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 2h3c.4 3 2 4 5 5v3c-2 0-4-1-5-2v8a6 6 0 1 1-6-6v3a3 3 0 1 0 3 3Z"/></svg>
@elseif(str_contains($platformTitle, 'telegram'))
<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m22 3-4 19-6-5-3 3 1-6L19 6 7 12 1 10Z"/></svg>
@elseif(str_contains($platformTitle, 'whatsapp'))
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 21l1.5-5A9 9 0 1 1 8 20Z"/><path d="M8 7c-2 4 4 10 8 8l-2-3-2 1-2-2 1-2Z" fill="currentColor" stroke="none"/></svg>
@elseif(str_contains($platformTitle, 'linkedin'))
<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><rect x="3" y="9" width="4" height="12"/><circle cx="5" cy="5" r="2"/><path d="M10 9h4v2c3-4 7-2 7 2v8h-4v-7c0-3-3-3-3 0v7h-4Z"/></svg>
@elseif(str_contains($platformTitle, 'twitter') || $platformTitle === 'x')
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3h5l13 18h-5ZM21 3l-7 8M10 14l-7 7"/></svg>
@else
@include('rentals.control-icon', ['name' => 'provider'])
@endif
