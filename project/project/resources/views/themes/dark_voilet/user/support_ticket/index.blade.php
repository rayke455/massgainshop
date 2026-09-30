@extends(template().'layouts.user')
@section('title',trans('Support Tickets'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="main-wrapper">
            <div class="pagetitle">
                <h3 class="mb-1">@lang("Support Tickets")</h3>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">
                                @lang("Home")</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang("Support Tickets")</li>
                    </ol>
                </nav>
            </div>

            <div class="card suportTicket">
                <div class="card-header d-flex justify-content-between pb-0 border-0">
                    <h4>@lang("Support Tickets")</h4>
                    <div class="btn-area">
                        <a href="{{ route("user.ticket.create") }}" class="cmn-btn"><i
                                class="fa-light fa-circle-plus"></i>@lang("Create")</a>
                        <button type="button" class="cmn-btn filter-btn ms-2" data-bs-toggle="offcanvas"
                                data-bs-target="#TicketFilter" aria-controls="offcanvasExample">@lang("Filter")<i
                                class="fa-regular fa-filter"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($tickets) > 0)
                        <div class="cmn-table">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang('Sl')</th>
                                        <th scope="col">@lang('Subject')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Last Reply')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($tickets as $key => $ticket)
                                        <tr>
                                            <td data-label="@lang("Sl")">
                                                <span>{{ loopIndex($tickets) + $loop->index }}</span>
                                            </td>
                                            <td data-label="@lang("Subject")">
                                                <span>{{ 'Ticket#' . $ticket->ticket }}</span>
                                                <span>@lang($ticket->subject)</span>
                                            </td>
                                            <td data-label="@lang("Status")">
                                                @if($ticket->status == 0)
                                                    <span class="badge text-bg-warning">@lang('Open')</span>
                                                @elseif($ticket->status == 1)
                                                    <span class="badge text-bg-success">@lang('Answered')</span>
                                                @elseif($ticket->status == 2)
                                                    <span class="badge text-bg-success">@lang('Replied')</span>
                                                @elseif($ticket->status == 3)
                                                    <span class="badge text-bg-danger">@lang('Closed')</span>
                                                @endif
                                            </td>
                                            <td data-label="@lang("Last Reply")">
                                        <span>
                                                {{ diffForHumans($ticket->last_reply) }}
                                        </span>
                                            </td>
                                            <td data-label="@lang("Action")">
                                                <a href="{{ route('user.ticket.view', $ticket->ticket) }}" class="action-btn-primary">
                                                    <i class="fa fa-eye"></i>
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
                            <p class="mb-0">@lang("No tickets available to display.")</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="pagination-section">
                <nav>
                    <ul class="pagination">
                        {{ $tickets->appends($_GET)->links(template().'partials.pagination') }}
                    </ul>
                </nav>
            </div>
        </div>
    </main>

    @include(template(). 'user.support_ticket.components.ticket_filter')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            flatpickr(".flatpickr");
        })
    </script>

@endpush
