<!-- Navbar Vertical -->
<aside
    class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-vertical-aside-initialized navbar-bordered
    {{in_array(session()->get('themeMode'), [null, 'auto'] )?  'navbar-dark bg-dark ' : 'navbar-light bg-white'}}">
    <div class="navbar-vertical-container">
        <div class="navbar-vertical-footer-offset">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="{{ $basicControl->site_title }}">
                <img class="navbar-brand-logo navbar-brand-logo-auto"
                     src="{{ getFile(session()->get('themeMode') == 'auto'?$basicControl->admin_dark_mode_logo_driver : $basicControl->admin_logo_driver, session()->get('themeMode') == 'auto'?$basicControl->admin_dark_mode_logo:$basicControl->admin_logo, true) }}"
                     alt="{{ $basicControl->site_title }} Logo"
                     data-hs-theme-appearance="default">

                <img class="navbar-brand-logo"
                     src="{{ getFile($basicControl->admin_dark_mode_logo_driver, $basicControl->admin_dark_mode_logo, true) }}"
                     alt="{{ $basicControl->site_title }} Logo"
                     data-hs-theme-appearance="dark">

                <img class="navbar-brand-logo-mini"
                     src="{{ getFile($basicControl->favicon_driver, $basicControl->favicon, true) }}"
                     alt="{{ $basicControl->site_title }} Logo"
                     data-hs-theme-appearance="default">
                <img class="navbar-brand-logo-mini"
                     src="{{ getFile($basicControl->favicon_driver, $basicControl->favicon, true) }}"
                     alt="Logo"
                     data-hs-theme-appearance="dark">
            </a>
            <!-- End Logo -->

            <!-- Navbar Vertical Toggle -->
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align"
                   data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                   data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   title="Collapse">
                </i>
                <i
                    class="bi-arrow-bar-right navbar-toggler-full-align"
                    data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                    data-bs-toggle="tooltip"
                    data-bs-placement="right"
                    title="Expand"
                ></i>
            </button>
            <!-- End Navbar Vertical Toggle -->

            <!-- Content -->
            <div class="navbar-vertical-content">
                <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
                    @if(\App\Services\PanelRentals::isSuperAdmin())
                    <a class="nav-link" href="{{ route('admin.rentals.index') }}"><i class="bi-shop nav-icon"></i><span class="nav-link-title">Panel rentals</span></a>
                    <a class="nav-link" href="{{ route('admin.guest.orders') }}"><i class="bi-cart nav-icon"></i><span class="nav-link-title">Guest orders</span></a>
                    @endif
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.dashboard']) }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi-house-door nav-icon"></i>
                            <span class="nav-link-title">@lang("Dashboard")</span>
                        </a>
                    </div>

                    @if(Module::has('ChildPanel') && Module::isEnabled('ChildPanel'))
                        @include('childpanel::main_admin.sidebar-push')
                    @endif

                    <span class="dropdown-header mt-3">@lang('MANAGE API PROVIDER')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.api-provider.index', 'admin.api-provider.create', 'admin.api-provider.edit']) }}"
                           href="{{ route('admin.api-provider.index') }}" data-placement="left">
                            <i class="bi-key nav-icon"></i>
                            <span class="nav-link-title">@lang("API Providers")</span>
                        </a>
                    </div>

                    <span class="dropdown-header mt-3">@lang('Manage Multi Currency')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.currency.index', 'admin.currency.create', 'admin.currency.edit']) }}"
                           href="{{ route('admin.currency.index') }}" data-placement="left">
                            <i class="fa-light fa-wallet nav-icon"></i>
                            <span class="nav-link-title">@lang("Currencies")</span>
                            <small class="d-none">@lang("Multi Currency > Currencies")</small>
                        </a>
                    </div>

                    <span class="dropdown-header mt-3">@lang('Manage Services')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.social-media.index', 'admin.social-media.create', 'admin.social-media.edit']) }}"
                           href="{{ route('admin.social-media.index') }}" data-placement="left">
                            <i class="fa-light fa-square-poll-horizontal nav-icon"></i>
                            <span class="nav-link-title">@lang("Social Media")</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.category.index', 'admin.category.create', 'admin.category.edit']) }}"
                           href="{{ route('admin.category.index') }}" data-placement="left">
                            <i class="fa-light fa-list nav-icon"></i>
                            <span class="nav-link-title">@lang("Category")</span>
                            <small class="d-none">@lang("Manage Services > Category")</small>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.service.index', 'admin.service.create', 'admin.service.edit']) }}"
                           href="{{ route('admin.service.index') }}" data-placement="left">
                            <i class="fa-light fa-ballot-check nav-icon"></i>
                            <span class="nav-link-title">@lang("Services")</span>
                        </a>
                    </div>

                    <span class="dropdown-header mt-3"> @lang("Order Management")</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>

                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.order', 'admin.order.edit'], 3) }}"
                           href="{{ route('admin.order', 'list') }}" data-placement="left">
                            <i class="fa-light fa-cart-shopping nav-icon"></i>
                            <span class="nav-link-title">@lang("Manage Orders")</span>
                        </a>
                    </div>

                    <span class="dropdown-header mt-3">@lang('Transactions')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.transaction']) }}"
                           href="{{ route('admin.transaction') }}" data-placement="left">
                            <i class="bi bi-send nav-icon"></i>
                            <span class="nav-link-title">@lang("Transaction")</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.payment.log']) }}"
                           href="{{ route('admin.payment.log') }}" data-placement="left">
                            <i class="bi bi-credit-card-2-front nav-icon"></i>
                            <span class="nav-link-title">@lang("Payment Log")</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.payment.pending']) }}"
                           href="{{ route('admin.payment.pending') }}" data-placement="left">
                            <i class="bi bi-cash nav-icon"></i>
                            <span class="nav-link-title">@lang("Payment Request")</span>
                        </a>
                    </div>

                    <span class="dropdown-header mt-3"> @lang("Ticket Panel")</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle {{ menuActive(['admin.ticket', 'admin.ticket.search', 'admin.ticket.view'], 3) }}"
                           href="#navbarVerticalTicketMenu"
                           role="button"
                           data-bs-toggle="collapse"
                           data-bs-target="#navbarVerticalTicketMenu"
                           aria-expanded="false"
                           aria-controls="navbarVerticalTicketMenu">
                            <i class="fa-light fa-headset nav-icon"></i>
                            <span class="nav-link-title">@lang("Support Ticket")</span>
                        </a>
                        <div id="navbarVerticalTicketMenu"
                             class="nav-collapse collapse {{ menuActive(['admin.ticket','admin.ticket.search', 'admin.ticket.view'], 2) }}"
                             data-bs-parent="#navbarVerticalTicketMenu">
                            <a class="nav-link {{ request()->is('admin/tickets/all') || menuActive('admin.ticket.view') ? 'active' : '' }}"
                               href="{{ route('admin.ticket', 'all') }}">
                                <span>@lang("All Tickets")</span>
                                <small class="d-none">@lang("Support Ticket > All Tickets")</small>
                            </a>
                            <a class="nav-link {{ request()->is('admin/tickets/answered') ? 'active' : '' }}"
                               href="{{ route('admin.ticket', 'answered') }}">
                                <span>@lang("Answered Ticket")</span>
                                <small class="d-none">@lang("Support Ticket > Answered Ticket")</small>
                            </a>
                            <a class="nav-link {{ request()->is('admin/tickets/replied') ? 'active' : '' }}"
                               href="{{ route('admin.ticket', 'replied') }}"><span>@lang("Replied Ticket")</span>
                                <small class="d-none">@lang("Support Ticket > Replied Ticket")</small>
                            </a>
                            <a class="nav-link {{ request()->is('admin/tickets/closed') ? 'active' : '' }}"
                               href="{{ route('admin.ticket', 'closed') }}"><span>@lang("Closed Ticket")</span>
                                <small class="d-none">@lang("Support Ticket > Closed Ticket")</small>
                            </a>
                        </div>
                    </div>


                    <span class="dropdown-header mt-3"> @lang('Kyc Management')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.kyc.form.list','admin.kyc.edit','admin.kyc.create']) }}"
                           href="{{ route('admin.kyc.form.list') }}" data-placement="left">
                            <i class="bi-stickies nav-icon"></i>
                            <span class="nav-link-title">@lang('KYC Setting')</span>
                            <small class="d-none">@lang("Kyc Management > KYC Setting")</small>
                        </a>
                    </div>

                    <div class="nav-item" {{ menuActive(['admin.kyc.list*','admin.kyc.view'], 3) }}>
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalKycRequestMenu"
                           role="button"
                           data-bs-toggle="collapse" data-bs-target="#navbarVerticalKycRequestMenu"
                           aria-expanded="false"
                           aria-controls="navbarVerticalKycRequestMenu">
                            <i class="bi bi-person-lines-fill nav-icon"></i>
                            <span class="nav-link-title">@lang("KYC Request")</span>
                        </a>
                        <div id="navbarVerticalKycRequestMenu"
                             class="nav-collapse collapse {{ menuActive(['admin.kyc.list*','admin.kyc.view'], 2) }}"
                             data-bs-parent="#navbarVerticalKycRequestMenu">
                            <a class="nav-link {{ Request::is('admin/kyc/pending') ? 'active' : '' }} {{ menuActive(['admin.kyc.view']) }}"
                               href="{{ route('admin.kyc.list', 'pending') }}">
                                <span>@lang('Pending KYC')</span>
                                <small class="d-none">@lang("Kyc Management > Pending KYC")</small>
                            </a>
                            <a class="nav-link {{ Request::is('admin/kyc/approve') ? 'active' : '' }}"
                               href="{{ route('admin.kyc.list', 'approve') }}">
                                <span>@lang('Approved KYC')</span>
                                <small class="d-none">@lang("Kyc Management > Approved KYC")</small>
                            </a>
                            <a class="nav-link {{ Request::is('admin/kyc/rejected') ? 'active' : '' }}"
                               href="{{ route('admin.kyc.list', 'rejected') }}">
                                <span>@lang('Rejected KYC')</span>
                                <small class="d-none">@lang("Kyc Management > Rejected KYC")</small>
                            </a>
                        </div>
                    </div>

                    <span class="dropdown-header mt-3">@lang('Subscribers')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.subscriber.index']) }}"
                           href="{{ route('admin.subscriber.index') }}" data-placement="left">
                            <i class="fa-light fa-mail-bulk nav-icon"></i>
                            <span class="nav-link-title">@lang("Subscriber")</span>
                        </a>
                    </div>

                    <span class="dropdown-header mt-3"> @lang("User Panel")</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle {{ menuActive(['admin.users'], 3) }}"
                           href="#navbarVerticalUserPanelMenu"
                           role="button"
                           data-bs-toggle="collapse"
                           data-bs-target="#navbarVerticalUserPanelMenu"
                           aria-expanded="false"
                           aria-controls="navbarVerticalUserPanelMenu">
                            <i class="bi-people nav-icon"></i>
                            <span class="nav-link-title">@lang('User Management')</span>
                        </a>
                        <div id="navbarVerticalUserPanelMenu"
                             class="nav-collapse collapse {{ menuActive(['admin.mail.all.user','admin.users','admin.users.add','admin.user.edit',
                                                                        'admin.user.view.profile','admin.user.transaction','admin.user.payment'
                                                                        ,'admin.user.kyc.list','admin.send.email',
                                                                        'admin.notice.index', 'admin.notice.create', 'admin.notice.edit', 'admin.user.custom.rate'], 2) }}"
                             data-bs-parent="#navbarVerticalUserPanelMenu">

                            <a class="nav-link {{ menuActive(['admin.users', 'admin.user.custom.rate']) }}"
                               href="{{ route('admin.users') }}">
                                <span>@lang('All User')</span>
                                <small class="d-none">@lang("User Management > All User")</small>
                            </a>
                            <a class="nav-link {{ menuActive(['admin.mail.all.user']) }}"
                               href="{{ route("admin.mail.all.user") }}">
                                <span>@lang('Mail To Users')</span>
                                <small class="d-none">@lang("User Management > Mail To Users")</small>
                            </a>
                        </div>
                    </div>

                    <span class="dropdown-header mt-3">@lang('Growth')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.growth.index']) }}" href="{{ route('admin.growth.index') }}" data-placement="left">
                            <i class="fa-light fa-rocket-launch nav-icon"></i>
                            <span class="nav-link-title">@lang("Coupons, VIP & Reviews")
                                @php $zkPendingReviews = \App\Models\Review::where('status', 0)->count(); @endphp
                                @if($zkPendingReviews)<span class="badge bg-danger rounded-pill ms-1">{{ $zkPendingReviews }}</span>@endif
                            </span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.referral-commission.index', 'admin.referral-commission.create', 'admin.referral-commission.edit']) }}"
                           href="{{ route('admin.referral-commission.index') }}" data-placement="left">
                            <i class="fa-light fa-repeat nav-icon"></i>
                            <span class="nav-link-title">@lang("Referral")</span>
                        </a>
                    </div>


                    <span class="dropdown-header mt-3"> @lang('SETTINGS PANEL')</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>


                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(controlPanelRoutes()) }}"
                           href="{{ route('admin.settings') }}" data-placement="left">
                            <i class="bi bi-gear nav-icon"></i>
                            <span class="nav-link-title">@lang('Control Panel')</span>
                        </a>
                    </div>

                    @if(Module::has('ChildPanel'))
                        <div class="nav-item addon-manager">
                            <a class="nav-link {{ menuActive('admin.addon.manager') }}"
                               href="{{ route('admin.addon.manager') }}" data-placement="left">
                                <i class="bi-box-seam nav-icon"></i>
                                <span class="nav-link-title">@lang('Addon Manager') <span
                                        class="badge bg-info rounded-pill ms-1">Addon</span></span>
                            </a>
                        </div>
                    @endif

                    <div
                        class="nav-item {{ menuActive(['admin.payment.methods', 'admin.edit.payment.methods', 'admin.deposit.manual.index', 'admin.deposit.manual.create', 'admin.deposit.manual.edit'], 3) }}">
                        <a class="nav-link dropdown-toggle"
                           href="#navbarVerticalGatewayMenu"
                           role="button"
                           data-bs-toggle="collapse"
                           data-bs-target="#navbarVerticalGatewayMenu"
                           aria-expanded="false"
                           aria-controls="navbarVerticalGatewayMenu">
                            <i class="bi-briefcase nav-icon"></i>
                            <span class="nav-link-title">@lang('Payment Setting')</span>
                        </a>
                        <div id="navbarVerticalGatewayMenu"
                             class="nav-collapse collapse {{ menuActive(['admin.payment.methods', 'admin.edit.payment.methods', 'admin.deposit.manual.index', 'admin.deposit.manual.create', 'admin.deposit.manual.edit'], 2) }}"
                             data-bs-parent="#navbarVerticalGatewayMenu">

                            <a class="nav-link {{ menuActive(['admin.payment.methods', 'admin.edit.payment.methods',]) }}"
                               href="{{ route('admin.payment.methods') }}">
                                <span>@lang('Payment Gateway')</span>
                                <small class="d-none">@lang('Payment Setting > Payment Gateway')</small>
                            </a>

                            <a class="nav-link {{ menuActive([ 'admin.deposit.manual.index', 'admin.deposit.manual.create', 'admin.deposit.manual.edit']) }}"
                               href="{{ route('admin.deposit.manual.index') }}">
                                <span>@lang('Manual Gateway')</span>
                                <small class="d-none">@lang('Payment Setting > Manual Gateway')</small>
                            </a>
                        </div>
                    </div>


                    <span class="dropdown-header mt-3">@lang("Themes Settings")</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    <div id="navbarVerticalThemeMenu">

                        <div class="nav-item">
                            <a class="nav-link {{ menuActive(['admin.theme.index']) }}"
                               href="{{ route('admin.theme.index') }}"
                               data-placement="left">
                                <i class="fa-light fa-check-square nav-icon"></i>
                                <span class="nav-link-title">@lang('Choose Theme')</span>
                            </a>
                        </div>

                        <div class="nav-item">
                            <a class="nav-link {{ menuActive(['admin.user.panels']) }}"
                               href="{{ route('admin.user.panels') }}"
                               data-placement="left">
                                <i class="fa-light fa-gauge nav-icon"></i>
                                <span class="nav-link-title">@lang('Choose Dashboard')</span>
                            </a>
                        </div>

                        <div class="nav-item">
                            <a class="nav-link {{ menuActive(['admin.page.index','admin.create.page','admin.edit.page']) }}"
                               href="{{ route('admin.page.index', basicControl()->theme) }}"
                               data-placement="left">
                                <i class="fa-light fa-list nav-icon"></i>
                                <span class="nav-link-title">@lang('Pages')</span>
                            </a>
                        </div>

                        <div class="nav-item">
                            <a class="nav-link {{ menuActive(['admin.manage.menu']) }}"
                               href="{{ route('admin.manage.menu') }}" data-placement="left">
                                <i class="bi-folder2-open nav-icon"></i>
                                <span class="nav-link-title">@lang('Manage Menu')</span>
                            </a>
                        </div>
                    </div>

                    @php
                        $segments = request()->segments();
                        $last  = end($segments);

                        $contents = config('contents');
                        $filteredContents = [];

                        foreach ($contents as $key => $value) {
                            if ($key == $basicControl->theme){
                                $filteredContents = $value;
                            }
                        }

                    @endphp
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle {{ menuActive(['admin.manage.content', 'admin.manage.content.multiple', 'admin.content.item.edit*'], 3) }}"
                           href="#navbarVerticalContentsMenu"
                           role="button" data-bs-toggle="collapse"
                           data-bs-target="#navbarVerticalContentsMenu" aria-expanded="false"
                           aria-controls="navbarVerticalContentsMenu">
                            <i class="fa-light fa-pen nav-icon"></i>
                            <span class="nav-link-title">@lang('Manage Content')</span>
                        </a>
                        <div id="navbarVerticalContentsMenu"
                             class="nav-collapse collapse {{ menuActive(['admin.manage.content', 'admin.manage.content.multiple', 'admin.content.item.edit*'], 2) }}"
                             data-bs-parent="#navbarVerticalContentsMenu">
                            @foreach(array_diff(array_keys($filteredContents), ['message','content_media']) as $name)

                                @php
                                    $contentImage = config('contents.'.$basicControl->theme.'.' . $name . '.image');
                                @endphp

                                <div class="contentAll d-flex justify-content-between">
                                    <a class="nav-link {{($last == $name) ? 'active' : '' }} contentTitle"
                                       href="{{ route('admin.manage.content', $name) }}">
                                        <span>@lang(stringToTitle($name))</span>
                                        <small class="d-none">@lang("Manage Content > ".stringToTitle($name))</small>
                                    </a>
                                    <button class="btn btn-white btn-sm sidebarContentImage contentImage"
                                            data-image="{{ json_encode($contentImage) }}"
                                            data-bs-toggle="tooltip" title="Section Style">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle {{ menuActive(['admin.blog-category.index', 'admin.blog-category.create','admin.blog.category.edit',
                                                            'admin.blogs.index', 'admin.blogs.create','admin.blog.edit', 'admin.blog.seo', 'admin.author.index',
                                                            'admin.author.create', 'admin.author.edit'], 3) }}"
                           href="#navbarVerticalBlogMenu"
                           role="button" data-bs-toggle="collapse"
                           data-bs-target="#navbarVerticalBlogMenu" aria-expanded="false"
                           aria-controls="navbarVerticalBlogMenu">
                            <i class="fa-light fa-newspaper nav-icon"></i>
                            <span class="nav-link-title">@lang('Manage Blog')</span>
                        </a>
                        <div id="navbarVerticalBlogMenu"
                             class="nav-collapse collapse {{ menuActive(['admin.blog-category.index', 'admin.blog-category.create','admin.blog.category.edit', 'admin.blogs.index',
                                                           'admin.blogs.create','admin.blog.edit', 'admin.blog.seo', 'admin.author.index', 'admin.author.create', 'admin.author.edit'], 2) }}"
                             data-bs-parent="#navbarVerticalBlogMenu">
                            <a class="nav-link {{ menuActive(['admin.blog-category.index', 'admin.blog-category.create','admin.blog.category.edit']) }}"
                               href="{{ route('admin.blog-category.index') }}">
                                <span>@lang("Category")</span>
                                <small class="d-none">@lang("Manage Blog > Category")</small>
                            </a>
                            <a class="nav-link {{ menuActive(['admin.blogs.index', 'admin.blogs.create','admin.blog.edit', 'admin.blog.seo']) }}"
                               href="{{ route('admin.blogs.index') }}">
                                <span>@lang("Blog")</span>
                                <small class="d-none">@lang("Manage Blog > Blog")</small>
                            </a>
                            <a class="nav-link {{ menuActive(['admin.author.index', 'admin.author.create','admin.author.edit']) }}"
                               href="{{ route('admin.author.index') }}">
                                <span>@lang('Author')</span>
                                <small class="d-none">@lang("Manage Blog > Author")</small>
                            </a>
                        </div>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link {{ menuActive(['admin.notice.index','admin.notice.create','admin.notice.edit']) }}"
                           href="{{ route('admin.notice.index') }}"
                           data-placement="left">
                            <i class="fa-light fa-bullhorn nav-icon"></i>
                            <span class="nav-link-title">@lang('Notice')</span>
                        </a>
                    </div>

                    @foreach(collect(config('generalsettings.settings')) as $key => $setting)
                        <div class="nav-item d-none">
                            <a class="nav-link  {{ isMenuActive($setting['route']) }}"
                               href="{{ getRoute($setting['route'], $setting['route_segment'] ?? null) }}">
                                <i class="{{$setting['icon']}} nav-icon"></i>
                                <span class="nav-link-title">{{ __(getTitle($key.' '.'Settings')) }}</span>
                                <small class="d-none">@lang("Control Panel > ".getTitle($key))</small>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="navbar-vertical-footer">
                    <ul class="navbar-vertical-footer-list">
                        <li class="navbar-vertical-footer-list-item">
                            <span class="dropdown-header">@lang('Version 5.0')</span>
                        </li>
                        <li class="navbar-vertical-footer-list-item">
                            <div class="dropdown dropup">
                                <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                        id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                        data-bs-dropdown-animation></button>
                                <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                     aria-labelledby="selectThemeDropdown">
                                    <a class="dropdown-item" href="javascript:void(0)" data-icon="bi-moon-stars"
                                       data-value="auto">
                                        <i class="bi-moon-stars me-2"></i>
                                        <span class="text-truncate"
                                              title="Auto (system default)">@lang("Default")</span>
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" data-icon="bi-brightness-high"
                                       data-value="default">
                                        <i class="bi-brightness-high me-2"></i>
                                        <span class="text-truncate"
                                              title="Default (light mode)">@lang("Light Mode")</span>
                                    </a>
                                    <a class="dropdown-item active" href="javascript:void(0)" data-icon="bi-moon"
                                       data-value="dark">
                                        <i class="bi-moon me-2"></i>
                                        <span class="text-truncate" title="Dark">@lang("Dark Mode")</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>




