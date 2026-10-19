{{-- On a rented panel's domain: use the panel's own brand colour everywhere. --}}
@php $zkPanel = \App\Services\PanelRentals::current(); @endphp
@if($zkPanel && preg_match('/^#[0-9a-fA-F]{6}$/', (string) $zkPanel->color))
    <style>:root { --primary-color: {{ $zkPanel->color }} !important; --zk-primary: {{ $zkPanel->color }}; }</style>
    <script>document.documentElement.style.setProperty('--primary-color', '{{ $zkPanel->color }}');</script>
@endif
