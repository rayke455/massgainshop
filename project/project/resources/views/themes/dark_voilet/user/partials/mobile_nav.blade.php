<!-- Bottom Mobile Tab nav section start -->
<ul class="nav bottom-nav fixed-bottom d-lg-none">
    <li class="nav-item">
        <a onclick="toggleSideMenu()" class="nav-link toggle-sidebar" aria-current="page">
            <i class="fa-light fa-list"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.add.fund') }}"><i class="fa-light fa-money-bill"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('user.dashboard') }}"><i class="fa-regular fa-house"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link search-bar-toggle" href="{{ route('user.order.create') }}"><i class="fa-light fa-cart-plus"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.profile') }}"><i class="fa-light fa-user"></i>
        </a>
    </li>
</ul>
<!-- Bottom Mobile Tab nav section end -->
