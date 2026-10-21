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
@else
@include('rentals.control-icon', ['name' => 'provider'])
@endif
