@extends(template().'layouts.user')
@section('title',trans('KYC Verification Center'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("KYC Verification")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">@lang("Home")</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a></li>
                    <li class="breadcrumb-item active">@lang("KYC Verification")</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between border-0">
                <h4>@lang("KYC Verification")</h4>
            </div>
            <div class="card-body">
                @if(count($kycVerification) > 0)
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
                                        <td data-label="@lang("Sl")">
                                            <span>{{ $loop->index + 1 }}</span>
                                        </td>
                                        <td data-label="@lang("Kyc Type")">
                                            <span>@lang($item->name)</span>
                                        </td>
                                        <td data-label="@lang("Status")">
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
                @else
                    <div class="text-center p-4">
                        <img class="error-image mb-3"
                             src="{{ asset('assets/global/img/oc-error.svg') }}"
                             alt="Image Description" data-hs-theme-appearance="default">
                        <p class="mb-0">@lang("The KYC is not available.")</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection


