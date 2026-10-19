{{-- Floating WhatsApp chat button (number set in Admin → Growth → Settings). --}}
@php $zkPanel = \App\Services\PanelRentals::current(); $zkWa = $zkPanel ? $zkPanel->whatsapp : \App\Services\Growth::get('whatsapp.number'); @endphp
@if($zkWa)
    <a class="zk-wa notranslate" href="https://wa.me/{{ $zkWa }}?text={{ rawurlencode(\App\Services\Growth::get('whatsapp.message', '')) }}"
       target="_blank" rel="noopener" aria-label="@lang('Chat with us on WhatsApp')" title="@lang('Chat with us on WhatsApp')">
        <svg viewBox="0 0 32 32" width="28" height="28" aria-hidden="true"><path fill="#fff" d="M16.04 3C9.4 3 4 8.4 4 15.04c0 2.12.55 4.18 1.6 6L4 29l8.17-1.57a12 12 0 0 0 3.87.64h.01C22.68 28.07 28 22.67 28 16.03 28 9.4 22.68 3 16.04 3zm0 22.9a9.9 9.9 0 0 1-5.05-1.38l-.36-.21-4.85.93.95-4.73-.24-.38a9.86 9.86 0 0 1-1.52-5.1c0-5.46 4.45-9.9 9.92-9.9 5.46 0 9.9 4.44 9.9 9.9 0 5.47-4.44 9.87-9.75 9.87zm5.44-7.4c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.47 1.07 2.88 1.21 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.27.49 1.7.63.72.23 1.37.2 1.88.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35z"/></svg>
    </a>
    <style>
        .zk-wa { position: fixed; right: 20px; bottom: 20px; z-index: 1045; width: 56px; height: 56px; border-radius: 50%; background: #25D366;
            display: grid; place-items: center; box-shadow: 0 12px 28px -10px rgba(37, 211, 102, .8); transition: transform .2s; }
        .zk-wa:hover { transform: scale(1.07); }
        @media (max-width: 991px) { body.zk-app .zk-wa { bottom: 84px; } } /* above the dashboard tab bar */
    </style>
@endif
