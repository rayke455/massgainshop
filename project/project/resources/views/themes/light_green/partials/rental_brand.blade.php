{{-- On a rented panel's domain: use the panel's own brand colour everywhere. --}}
@php $zkPanel = \App\Services\PanelRentals::current(); @endphp
@if($zkPanel && preg_match('/^#[0-9a-fA-F]{6}$/', (string) $zkPanel->color))
    <style>:root { --primary-color: {{ $zkPanel->color }} !important; --zk-primary: {{ $zkPanel->color }}; }</style>
    <script>document.documentElement.style.setProperty('--primary-color', '{{ $zkPanel->color }}');</script>
@endif
@if($zkPanel && request()->is('user/*'))
<style>
body.zk-app{--zk-bg:#171f26;--zk-surface:#1d2832;--zk-border:#2b3843;--zk-text:#e5eaf0;--zk-muted:#9baab9;--zk-primary:#1599f6;background:#171f26!important;color:#e5eaf0!important}
.zk-app .zk-side,.zk-app .zk-topbar,.zk-app .zk-tabbar,.zk-app .card,.zk-app .modal-content,.zk-app .dropdown-menu{background:#1d2832!important;color:#e5eaf0;border-color:#2b3843!important}
.zk-app .zk-main,.zk-app .zk-shell{background:#171f26!important;color:#e5eaf0}
.zk-app .zk-side__link,.zk-app .dropdown-item,.zk-app label,.zk-app .form-label,.zk-app .card h1,.zk-app .card h2,.zk-app .card h3,.zk-app .card h4{color:#e5eaf0}
.zk-app .zk-side__link.active,.zk-app .zk-side__link:hover,.zk-app .zk-tabbar__item.active{color:#20a5ff!important;background:#253748!important}
.zk-app input:not([type=checkbox]),.zk-app select,.zk-app textarea,.zk-app .select2-selection{background:#14212b!important;color:#e5eaf0!important;border-color:#3a4b59!important}
.zk-app .text-muted,.zk-app small{color:#9baab9!important}.zk-app .table{--bs-table-bg:#1d2832;--bs-table-color:#e5eaf0;--bs-table-border-color:#2b3843}.zk-app .zk-balance{background:#193d35;border-color:#28584a}.zk-app .zk-balance__add{color:#35cc84}
.zk-app .zk-topbar__logo img,.zk-app .zk-side__logo img{max-width:180px;max-height:38px;object-fit:contain}.zk-app .zk-topbar__logo{min-width:0;overflow:hidden}
</style>
@endif
