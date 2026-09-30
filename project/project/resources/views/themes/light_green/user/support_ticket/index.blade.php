@extends(template().'layouts.user')
@section('title',trans('Support Ticket'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("Support Tickets")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i
                                class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Support Tickets")</li>
                </ul>
            </div>

            <div class="card mt-50">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0">@lang("Support Tickets")</h4>
                    <div class="btn-area">
                        <a href="{{ route('user.ticket.create') }}" class="cmn-btn"><i
                                class="fa-light fa-layer-plus"></i>@lang("Create")</a>
                        <button type="button" class="cmn-btn ms-2 rounded-1" data-bs-toggle="offcanvas"
                                data-bs-target="#TicketFilter" aria-controls="offcanvasExample">@lang("Filter")<i
                                class="fa-regular fa-filter"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($tickets) > 0)
                        <div class="cmn-table">
                            <div class="table-responsive">
                                <table class="table user-ticket-table table-striped align-middle">
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
                                            <td data-label="Sl">
                                                <span>{{ loopIndex($tickets) + $loop->index }}</span>
                                            </td>
                                            <td data-label="Subject">
                                                <span>{{ 'Ticket#' . $ticket->ticket }}</span>
                                                <span>@lang($ticket->subject)</span>
                                            </td>
                                            <td data-label="Status">
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
                                            <td data-label="Last Reply">
                                        <span>
                                                {{ diffForHumans($ticket->last_reply) }}
                                        </span>
                                            </td>
                                            <td data-label="Action">
                                                <a href="{{ route('user.ticket.view', $ticket->ticket) }}"
                                                   class="action-btn-primary">
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
    </div>

    @include(template(). 'user.support_ticket.components.ticket_filter');

@endsection

@push('script')
    <script>
        "use strict";
        $('.flatpickr').flatpickr()
    </script>
@endpush
