@extends(template().'layouts.user')
@section('title',trans('View Ticket'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="main-wrapper">
            <div class="pagetitle">
                <h3 class="mb-1">@lang("View Ticket")</h3>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">
                                @lang("Home")</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang("View Ticket")</li>
                    </ol>
                </nav>
            </div>

            <div class="message-wrapper">
                <div class="row g-lg-0">
                    <div class="col-lg-12">
                        <form class="form-row" action="{{ route('user.ticket.reply', $ticket->id)}}"
                              method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="inbox-wrapper">
                                <div class="top-bar">
                                    <div class="top-bar-inner d-flex align-items-center">
                                        <div class="top-bar-inner-info me-2">
                                            @if($ticket->status == 0)
                                                <span class="badge text-bg-warning">@lang('Open')</span>
                                            @elseif($ticket->status == 1)
                                                <span class="badge text-bg-success">@lang('Answered')</span>
                                            @elseif($ticket->status == 2)
                                                <span class="badge text-bg-info">@lang('Replied')</span>
                                            @elseif($ticket->status == 3)
                                                <span class="badge text-bg-danger">@lang('Closed')</span>
                                            @endif
                                        </div>
                                        <h6>{{ '#' . $ticket->ticket }} @lang($ticket->subject)</h6>
                                    </div>
                                    <div>
                                        <button type="button" class="delete-btn" data-bs-toggle="modal"
                                                data-bs-target="#ticketCloseModal">
                                            <i class="fa-light fa-xmark me-2"></i> @lang("Close")
                                        </button>
                                    </div>
                                </div>
                                @if(count($ticket->messages) > 0)
                                    <div class="chats">
                                        @foreach($ticket->messages as $item)
                                            @if($item->admin_id == null)
                                                <div class="chat-box this-side">
                                                    <div class="chat-box this-side">
                                                        <div class="text-wrapper d-flex flex-column align-items-end">
                                                            <div class="text">
                                                                <p>
                                                                    @lang($item->message)
                                                                </p>
                                                            </div>
                                                            @if(0 < count($item->attachments))
                                                                <div class="attachment-wrapper mt-2 mb-2">
                                                                    @foreach($item->attachments as $k=> $file)
                                                                        <a
                                                                            class="attachment"
                                                                            href="{{route('user.ticket.download',encrypt($file->id))}}"
                                                                            data-fancybox="gallery">
                                                                            <i class="fa fa-file"></i> @lang('File') {{++$k}}
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                            <small
                                                                class="time"> {{ dateTime($item->created_at)}}</small>
                                                        </div>
                                                        <div class="img">
                                                            <img class="img-fluid"
                                                                 src="{{ getFile(optional(auth()->user())->image_driver, optional(auth()->user())->image) }}"
                                                                 alt="User Image"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="chat-box opposite-side">
                                                    <div class="img">
                                                        <img class="img-fluid"
                                                             src="{{ getFile(optional($item->childPanel)->image_driver, optional($item->childPanel)->image) }}"
                                                             alt=""/>
                                                    </div>
                                                    <div class="text-wrapper">
                                                        <div class="text">
                                                            <p>
                                                                @lang($item->message)
                                                            </p>
                                                        </div>
                                                        <div class="attachment-wrapper">
                                                            @foreach($item->attachments as $k=> $file)
                                                                <a
                                                                    class="attachment"
                                                                    href="{{route('user.ticket.download',encrypt($file->id))}}"
                                                                    data-fancybox="gallery">
                                                                    <i class="fa fa-file"></i> @lang('File') {{++$k}}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                        <small
                                                            class="time">{{dateTime($item->created_at)}}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="typing-area">
                                    <div class="img-preview">
                                        <input type="file" name="attachments[]" class="form-control " multiple=""
                                               placeholder="Upload File">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control ps-3" name="message"
                                               placeholder="@lang("Enter Message")" autocomplete="off"/>
                                        <button class="submit-btn" name="reply_ticket" value="1">
                                            <i class="fal fa-paper-plane"></i>
                                        </button>
                                    </div>
                                    @error('message')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <div class="modal fade" id="ticketCloseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="ticketCloseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="ticketCloseModalLabel">@lang("Close Ticket")</h1>
                    <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
                <form method="post" action="{{ route('user.ticket.reply', $ticket->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>@lang("Do you want to close this ticket?")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" data-bs-dismiss="modal">@lang("No")</button>
                        <button type="submit" class="cmn-btn rounded-1" name="reply_ticket"
                                value="2">@lang("Yes")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

