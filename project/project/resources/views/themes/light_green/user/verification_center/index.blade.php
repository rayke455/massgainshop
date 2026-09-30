@extends(template().'layouts.user')
@section('title',trans('KYC Verification Center'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("Verification Center")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Dashboard")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Verification Center")</li>
                </ul>
            </div>

            <div class="card mt-50">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0">@lang("Verification Center")</h4>
                </div>
                <div class="card-body">
                    <div class="cmn-table">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">@lang("Sl")</th>
                                    <th scope="col">@lang("Kyc Type")</th>
                                    <th scope="col">@lang("Status")</th>
                                    <th scope="col">@lang("Action")</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($kycVerification as $item)
                                    <tr>
                                        <td data-label="Sl">
                                            <span>{{ $loop->index + 1 }}</span>
                                        </td>
                                        <td data-label="Kyc Type">
                                            <span>@lang($item->name)</span>
                                        </td>
                                        <td data-label="Status">
                                            @if($item->last_submission_status == 'null')
                                                <span class="badge text-bg-primary">@lang("Required")</span>
                                            @elseif($item->last_submission_status == 0)
                                                <span class="badge text-bg-warning">@lang("Pending")</span>
                                            @elseif($item->last_submission_status == 1)
                                                <span class="badge text-bg-success">@lang("Approved")</span>
                                            @elseif($item->last_submission_status == 2)
                                                <span class="badge text-bg-primary">@lang("Required")</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang("Action")">
                                            <a href="{{ route('user.kyc.verification.form', $item->id) }}"
                                               class="action-btn-primary">
                                                <i class="fa-light fa-file-user"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(count($kycVerification) < 1)
                        <div class="empty_state text-center pb-2">
                            <img src="{{ asset(template(true). 'img/empty_data.png') }}" alt="empty data">
                            <h4>@lang("kyc verification not available.")</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


