{{-- Client sidebar: static on desktop, slide-out drawer below 992px (Bootstrap offcanvas-lg). --}}
<aside class="offcanvas-lg offcanvas-start zk-side" tabindex="-1" id="zkSidebar" aria-label="@lang('Main menu')">
    <div class="zk-side__head">
        <a class="zk-side__logo" href="{{ route('user.dashboard') }}">
            <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title }}">
        </a>
        <button type="button" class="zk-icon-btn d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#zkSidebar"
                aria-label="@lang('Close menu')"><i class="fa-regular fa-xmark"></i></button>
    </div>

    <div class="zk-side__body">
        <a href="{{ route('user.order.create') }}" class="zk-btn zk-btn--primary w-100 mb-3 {{ menuActive('user.order.create') }}" @if(request()->routeIs('user.order.create')) aria-current="page" @endif>
            <i class="fa-regular fa-cart-shopping"></i> @lang('Buy services')
        </a>

        <nav class="zk-side__nav">
            <a class="zk-side__link {{ menuActive('user.dashboard') }}" href="{{ route('user.dashboard') }}">
                <i class="fa-regular fa-house"></i><span>@lang('Dashboard')</span>
            </a>

            <p class="zk-side__label">@lang('Orders')</p>
            <a class="zk-side__link {{ menuActive(['user.mass.order', 'user.show.draft.order']) }}" href="{{ route('user.mass.order') }}">
                <i class="fa-regular fa-layer-group"></i><span>@lang('Mass order')</span>
            </a>
            <a class="zk-side__link {{ menuActive('user.order.index') }}" href="{{ route('user.order.index') }}">
                <i class="fa-regular fa-list-check"></i><span>@lang('My orders')</span>
            </a>
            <a class="zk-side__link {{ menuActive('user.show.order.refill') }}" href="{{ route('user.show.order.refill') }}">
                <i class="fa-regular fa-rotate"></i><span>@lang('Refills')</span>
            </a>
            <a class="zk-side__link {{ menuActive('user.show.drip.feed') }}" href="{{ route('user.show.drip.feed') }}">
                <i class="fa-regular fa-droplet"></i><span>@lang('Drip-feed')</span>
            </a>
            <a class="zk-side__link" href="{{ route('services') }}">
                <i class="fa-regular fa-tags"></i><span>@lang('Services & prices')</span>
            </a>

            <p class="zk-side__label">@lang('Funds')</p>
            <a class="zk-side__link {{ menuActive('user.add.fund') }}" href="{{ route('user.add.fund') }}">
                <i class="fa-regular fa-wallet"></i><span>@lang('Add funds')</span>
            </a>
            <a class="zk-side__link {{ menuActive('user.fund.index') }}" href="{{ route('user.fund.index') }}">
                <i class="fa-regular fa-receipt"></i><span>@lang('Deposit history')</span>
            </a>
            <a class="zk-side__link {{ menuActive('user.transaction.history') }}" href="{{ route('user.transaction.history') }}">
                <i class="fa-regular fa-arrow-right-arrow-left"></i><span>@lang('Transactions')</span>
            </a>

            <p class="zk-side__label">@lang('Account')</p>
            <a class="zk-side__link {{ menuActive(['user.ticket.list', 'user.ticket.create', 'user.ticket.view']) }}" href="{{ route('user.ticket.list') }}">
                <i class="fa-regular fa-headset"></i><span>@lang('Support')</span>
            </a>
            <a class="zk-side__link {{ menuActive(['user.referral', 'user.referral.bonus']) }}" href="{{ route('user.referral') }}">
                <i class="fa-regular fa-user-group"></i><span>@lang('Affiliates')</span>
            </a>
            <a class="zk-side__link {{ menuActive('user.api.docs') }}" href="{{ route('user.api.docs') }}">
                <i class="fa-regular fa-code"></i><span>@lang('API')</span>
            </a>
            <a class="zk-side__link {{ menuActive('user.notice') }}" href="{{ route('user.notice') }}">
                <i class="fa-regular fa-bullhorn"></i><span>@lang('Updates')</span>
            </a>
            <a class="zk-side__link {{ menuActive(['user.profile', 'user.password.setting', 'user.two.step.security', 'user.kyc.verification', 'user.notification.settings']) }}" href="{{ route('user.profile') }}">
                <i class="fa-regular fa-gear"></i><span>@lang('Settings')</span>
            </a>

            @php $zkCurrencies = \App\Models\Currency::where('status', 1)->orderBy('sort_by')->get(['code', 'name']); @endphp
            @if($zkCurrencies->count() > 1)
                {{-- The top-bar switcher is hidden on phones, so offer it here too. --}}
                <p class="zk-side__label">@lang('Currency')</p>
                <div class="px-2">
                    <label for="zkSideCurrency" class="visually-hidden">@lang('Display currency')</label>
                    <select id="zkSideCurrency" class="form-select form-select-sm"
                            data-url="{{ route('user.selected.currency') }}">
                        @foreach($zkCurrencies as $c)
                            <option value="{{ $c->code }}" @selected((auth()->user()->currency ?: basicControl()->base_currency) === $c->code)>
                                {{ $c->code }} — {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <script>
                    document.getElementById('zkSideCurrency').addEventListener('change', function () {
                        this.disabled = true;
                        fetch(this.dataset.url + '?currency=' + encodeURIComponent(this.value), {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                            .then(() => window.location.reload());
                    });
                </script>
            @endif

            @if(Module::has('ChildPanel') && Module::isEnabled('ChildPanel'))
                <div class="zk-side__module">
                    @include('childpanel::main_user.light-sidebar-push')
                </div>
            @endif
        </nav>
    </div>
</aside>
