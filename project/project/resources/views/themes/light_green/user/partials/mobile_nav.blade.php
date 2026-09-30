<!-- Bottom tab bar (phones/tablets) -->
<nav class="zk-tabbar d-lg-none" aria-label="@lang('Quick navigation')">
    <a class="zk-tabbar__item {{ menuActive('user.dashboard') }}" href="{{ route('user.dashboard') }}">
        <i class="fa-regular fa-house"></i><span>@lang('Home')</span>
    </a>
    <a class="zk-tabbar__item {{ menuActive('user.order.index') }}" href="{{ route('user.order.index') }}">
        <i class="fa-regular fa-list-check"></i><span>@lang('Orders')</span>
    </a>
    <a class="zk-tabbar__item zk-tabbar__item--main {{ menuActive('user.order.create') }}" href="{{ route('user.order.create') }}">
        <span class="zk-tabbar__fab"><i class="fa-solid fa-cart-shopping"></i></span><span>@lang('Buy')</span>
    </a>
    <a class="zk-tabbar__item {{ menuActive('user.add.fund') }}" href="{{ route('user.add.fund') }}">
        <i class="fa-regular fa-wallet"></i><span>@lang('Add funds')</span>
    </a>
    <a class="zk-tabbar__item" href="#zkSidebar" data-bs-toggle="offcanvas" role="button" aria-controls="zkSidebar">
        <i class="fa-regular fa-bars"></i><span>@lang('Menu')</span>
    </a>
</nav>
