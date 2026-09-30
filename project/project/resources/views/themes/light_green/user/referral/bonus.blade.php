@extends(template().'layouts.user')
@section('title',trans('Referral'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("Referral Bonus")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Dashboard")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Referral Bonus")</li>
                </ul>
            </div>

            <div class="card mt-50">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0">@lang("Referral Bonus")</h4>
                </div>
                <div class="card-body">
                    @if(count($referralBonus) > 0)
                    <div class="cmn-table">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('Sl')</th>
                                    <th scope="col">@lang('Bonus From')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Remarks')</th>
                                    <th scope="col">@lang('Time')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($referralBonus as $bonus)
                                    <tr>
                                        <td data-label="@lang('Sl')">
                                            {{ loopIndex($referralBonus) + $loop->index }}
                                        </td>
                                        <td data-label="@lang('Bonus From')">
                                            @lang(optional($bonus->bonusBy)->firstname." ".optional($bonus->bonusBy)->lastname)
                                        </td>
                                        <td data-label="@lang('Amount')">
                                            <span class="font-weight-bold text-success">
                                                {{ currencyPosition($bonus->amount) }}
                                            </span>
                                        </td>
                                        <td data-label="@lang('Remarks')">
                                            @lang($bonus->remarks)
                                        </td>
                                        <td data-label="@lang('Time')">
                                            {{ dateTime($bonus->created_at) }}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <div class="text-center p-4">
                            <img class="error-image mb-3"
                                 src="{{ asset('assets/global/img/oc-error.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default">
                            <p class="mb-0">@lang("No referral bonus available to display.")</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


