@extends(template().'layouts.user')
@section('title',trans('Create Ticket'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="main-wrapper">
            <div class="pagetitle">
                <h3 class="mb-1">@lang("Create Ticket")</h3>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">
                                @lang("Home")</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang("Create Ticket")</li>
                    </ol>
                </nav>
            </div>

            <div class="section">
                <div class="row">
                    <form action="{{route('user.ticket.store')}}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="account-settings-profile-section">
                            <div class="card">
                                <div class="card-body pt-4">
                                    <div class="row">
                                        <div class="col-md-8 mx-auto">
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <label for="subject" class="form-label">@lang("Subject")</label>
                                                    <input type="text" class="form-control" name="subject" id="subject"
                                                           placeholder="@lang("Enter Subject")"
                                                           value="{{ old('subject') }}" autocomplete="off">
                                                    @error('subject')
                                                    <div class="error text-danger">@lang($message) </div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="message" class="form-label">@lang("Message")</label>
                                                    <textarea class="form-control" id="message" name="message"
                                                              rows="5"
                                                              placeholder="@lang("Enter Message")">{{ old('message') }}</textarea>
                                                    @error('message')
                                                    <div class="error text-danger">@lang($message) </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="input-field">
                                                        <div class="input-images-1" ></div>
                                                    </div>
                                                </div>
                                                <div class="btn-area">
                                                    <button type="submit" class="cmn-btn">@lang("submit")</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @push('style')
        <style>
            .image-uploader {
                height: 15rem;
                border: .125rem dashed rgba(231,234,243,.7);
                border-radius: 10px;
                position: relative;
                overflow: auto;
            }

            .input-images-1{
                padding-top: .5rem !important;
            }
        </style>
    @endpush

@endsection

@push('js-lib')
    <script src="{{ asset('assets/global/js/image-uploader.js') }}"></script>
@endpush
@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.css') }}">
@endpush

@push('script')
    <script>
        $(document).ready(function (){
            $('.input-images-1').imageUploader();
        })
    </script>
@endpush
