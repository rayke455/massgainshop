@extends(template().'layouts.user')
@section('title',trans('Referral'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="main-wrapper">
            <div class="pagetitle">
                <h3 class="mb-1">@lang("Referral")</h3>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                                @lang("Home")</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">@lang("Dashboard")</li>
                        <li class="breadcrumb-item active" aria-current="page">@lang("Referral")</li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5>@lang('Referral Link')</h5>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="@lang('Sponsor URL')"
                               aria-label="@lang('Sponsor URL')" id="sponsorURL"
                               value="{{route('register.sponsor',[optional(auth()->user())->username])}}"
                               aria-describedby="basic-addon">
                        <span class="input-group-text" onclick="copyFunction()" id="basic-addon"><i class="fa-light fa-copy"></i></span>
                    </div>
                </div>
            </div>

            <div class="card mt-30" id="referral-card">
                <div class="card-header">
                    <h5>@lang('Referrals List')</h5>
                </div>
                <div class="card-body">
                    @if(0 < count($directReferralUsers))
                        <div class="cmn-table skltbs-panel">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang('Username')</th>
                                        <th scope="col">@lang('Level')</th>
                                        <th scope="col">@lang('Email')</th>
                                        <th scope="col">@lang('Phone Number')</th>
                                        <th scope="col">@lang('Joined At')</th>
                                    </tr>
                                    </thead>
                                    <tbody class="block-statistics">
                                    @forelse($directReferralUsers as $user)
                                        <tr id="user-{{ $user->id }}" data-level="0" data-loaded="false">
                                            <td data-label="@lang('Username')">
                                                <a href="javascript:void(0)"
                                                   class="{{ count(getDirectReferralUsers($user->id)) > 0 ? 'nextDirectReferral' : '' }}"
                                                   data-id="{{ $user->id }}">
                                                    @if(count(getDirectReferralUsers($user->id)) > 0)
                                                        <i class="far fa-circle-down color-primary"></i>
                                                    @endif
                                                    @lang($user->username)
                                                </a>
                                            <td data-label="@lang('Level')">
                                                @lang('Level 1')
                                            </td>
                                            <td data-label="@lang('Email')" class="">{{$user->email}}</td>
                                            <td data-label="@lang('Phone Number')">
                                                {{$user->phone}}
                                            </td>
                                            <td data-label="@lang('Joined At')">
                                                {{dateTime($user->created_at)}}
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <th colspan="100%" class="text-center text-dark">
                                                <div class="no_data_iamge text-center">
                                                    <img class="no_image_size"
                                                         src="{{ asset('assets/global/img/oc-error-light.svg') }}">
                                                </div>
                                                <p class="text-center emptyMessage">@lang('Referral history is empty here!.')</p>
                                            </th>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    @endif

                    @if(count($directReferralUsers) < 1)
                        <div class="text-center p-4">
                            <img class="error-image mb-3"
                                 src="{{ asset('assets/global/img/oc-error.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default">
                            <p class="mb-0">@lang("No referrals available to display.")</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
@endsection

@push('script')
    <script>
        'use strict'
        $(document).on('click', '.nextDirectReferral', function () {
            let _this = $(this);
            let parentRow = _this.closest('tr');

            if (parentRow.data('loaded')) {
                return;
            }

            getDirectReferralUser(_this);
        });

        function getDirectReferralUser(_this) {

            Notiflix.Block.standard('.block-statistics');

            let userId = _this.data('id');
            let parentRow = _this.closest('tr');
            let currentLevel = parseInt(parentRow.data('level')) + 1;
            let downLabel = currentLevel + 1;

            setTimeout(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('user.myGetDirectReferralUser') }}",
                    method: 'POST',
                    data: {
                        userId: userId,
                    },
                    success: function (response) {

                        Notiflix.Block.remove('.block-statistics');
                        let directReferralUsers = response.data;

                        let referralData = '';

                        directReferralUsers.forEach(function (directReferralUser) {
                            referralData += `
                            <tr id="user-${directReferralUser.id}" data-level="${currentLevel}">
                                <td data-label="@lang('Username')" style="padding-left: ${currentLevel * 35}px;">
                                    <a class="${directReferralUser.count_direct_referral > 0 ? 'nextDirectReferral' : ''}" href="javascript:void(0)" style="border-bottom: none !important;" data-id="${directReferralUser.id}">
                                        ${directReferralUser.count_direct_referral > 0 ? ' <i class="far fa-circle-down color-primary"></i>' : ''}
                                        ${directReferralUser.username}
                                    </a>
                                </td>

                                <td data-label="@lang('Level')">
                                     <span class="text-light">Level ${downLabel}</span>
                                </td>

                                <td data-label="@lang('Email')">
                                    ${directReferralUser.email ? directReferralUser.email : '-'}
                                </td>
                                <td data-label="@lang('Phone Number')">
                                     ${directReferralUser.phone ?? '-'}
                                </td>

                                <td data-label="Joined At">
                                    ${directReferralUser.joined_at}
                                </td>
                                </tr>`;
                        });

                        parentRow.data('loaded', true);

                        $(`#user-${userId}`).after(referralData);
                    },
                    error: function (xhr, status, error) {
                    }
                });
            }, 100);
        }

        function copyFunction() {
            var copyText = document.getElementById("sponsorURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }

    </script>
@endpush


