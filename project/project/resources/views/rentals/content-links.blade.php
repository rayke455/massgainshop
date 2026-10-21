@if($panel->exists && $panel->allows('branding'))
@php
    $contentBase = \App\Services\PanelRentals::currentId() === $panel->id ? '' : url('/panels/'.$panel->slug);
@endphp
<nav aria-label="Panel information" class="inline" style="display:flex;flex-wrap:wrap;gap:16px;margin:20px 0">
@foreach(\App\Models\RentalContent::visibleFor($panel, ['page', 'link'])->get() as $contentLink)
<a href="{{ $contentLink->kind === 'link' ? $contentLink->url : $contentBase.'/pages/'.$contentLink->slug }}" @if($contentLink->kind === 'link') rel="noopener noreferrer" @endif>{{ $contentLink->title }}</a>
@endforeach
@if(\App\Models\RentalContent::visibleFor($panel, ['faq'])->exists())<a href="{{ $contentBase }}/faq">FAQ</a>@endif
</nav>
@endif
