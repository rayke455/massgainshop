<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ menuActive(['user.dashboard']) }}" href="{{ route('user.dashboard') }}">
{{--                <i class="fa-regular fa-grid"></i>--}}
                <i class="fa-light fa-house"></i>
                <span>@lang("dashboard")</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('services') }}">
                <i class="fa-sharp fa-light fa-code-compare"></i>
                <span>@lang("services")</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ menuActive(['user.order.create', 'user.mass.order', 'user.show.draft.order', 'user.draft.mass.order',
                'user.order.index', 'user.show.order.refill', 'user.show.drip.feed'], 3) }}"
               data-bs-target="#order"
               data-bs-toggle="collapse" href="#">
                <i class="fa-light fa-cart-plus"></i><span>@lang("order")</span>
                <i class="fa-regular fa-angle-down ms-auto bi-chevron-down"></i>
            </a>
            <ul id="order" class="nav-content collapse {{ menuActive(['user.order.create', 'user.mass.order', 'user.show.draft.order', 'user.draft.mass.order',
                'user.order.index', 'user.show.order.refill', 'user.show.drip.feed'], 2) }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('user.order.create') }}"
                       class="{{ menuActive(['user.order.create']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("new order")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.mass.order') }}"
                       class="{{ menuActive(['user.mass.order']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("Mass Order")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.show.draft.order') }}"
                       class="{{ menuActive(['user.show.draft.order']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("Draft Mass Order")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.order.index') }}"
                       class="{{ menuActive(['user.order.index']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("All Order")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.show.order.refill') }}"
                       class="{{ menuActive(['user.show.order.refill']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("Refill Order")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.show.drip.feed') }}"
                       class="{{ menuActive(['user.show.drip.feed']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("Drip Feed")</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ menuActive(['user.add.fund']) }}" href="{{ route('user.add.fund') }}">
                <i class="fa-light fa-wallet"></i>
                <span>add fund</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ menuActive(['user.ticket.list', 'user.ticket.create', 'user.ticket.view']) }}"
               href="{{ route('user.ticket.list') }}">
                <i class="fa-light fa-headset"></i>
                <span>@lang("Support Ticket")</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ menuActive(['user.fund.index', 'transactions', 'user.kyc.verification.history'], 3) }}"
               data-bs-target="#account-settings" data-bs-toggle="collapse" href="#">
                <i class="fa-light fa-files"></i><span>@lang("History")</span>
                <i class="fa-regular fa-angle-down ms-auto bi-chevron-down"></i>
            </a>
            <ul id="account-settings"
                class="nav-content collapse {{ menuActive(['user.fund.index', 'user.transaction.history', 'user.kyc.verification.history'], 2) }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('user.fund.index') }}" class="{{ menuActive(['user.fund.index']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("Deposit")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.transaction.history') }}"
                       class="{{ menuActive(['user.transaction.history']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("Transaction")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.kyc.verification.history') }}"
                       class="{{ menuActive(['user.kyc.verification.history']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("KYC")</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ menuActive(['user.notice']) }}" href="{{ route('user.notice') }}">
                <i class="fa-light fa-bullhorn"></i>
                <span>@lang("notice")</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ menuActive(['user.referral', 'user.referral.bonus'], 3) }}"
               data-bs-target="#referral-settings" data-bs-toggle="collapse" href="#">
                <i class="fa-light fa-circle-dollar-to-slot referral-bonus-icon"></i><span>@lang("Referral")</span>
                <i class="fa-regular fa-angle-down ms-auto bi-chevron-down"></i>
            </a>
            <ul id="referral-settings"
                class="nav-content collapse {{ menuActive(['user.referral', 'user.referral.bonus'], 2) }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('user.referral') }}" class="{{ menuActive(['user.referral']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("My Referral")</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.referral.bonus') }}" class="{{ menuActive(['user.referral.bonus']) }}">
                        <i class="fa-regular fa-circle"></i><span>@lang("Referral Bonus")</span>
                    </a>
                </li>
            </ul>
        </li>

        @if(Module::has('ChildPanel') && Module::isEnabled('ChildPanel'))
            @include('childpanel::main_user.dark-sidebar-push')
        @endif

        <li>
            <a class="nav-link {{ menuActive(['user.api.docs']) }}" href="{{ route('user.api.docs') }}">
                <i class="fa-light fa-paperclip"></i><span>@lang("Api Setting")</span>
            </a>
        </li>

        <li>
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fal fa-sign-out-alt api-setting-icon"></i><span>@lang("Sign Out")</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                      class="d-none">
                    @csrf
                </form>
            </a>
        </li>
    </ul>
</aside>
<!-- Sidebar section end -->
