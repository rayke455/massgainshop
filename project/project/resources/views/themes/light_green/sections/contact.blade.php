<!-- Contact section start -->
<section class="contact-section">
    <div class="container">
        <div class="contact-inner">
            <div class="row g-4">
                <div class="col-xl-5 col-lg-6">
                    <div class="contact-area">
                        <div class="section-header mb-0">
                            <h3>@lang(@$contact['single']['heading'])</h3>
                        </div>
                        <p class="para_text">@lang(@$contact['single']['short_description'])</p>
                        <div class="contact-item-list">
                            <div class="item">
                                <div class="icon-area">
                                    <i class="fa-light fa-phone"></i>
                                </div>
                                <div class="content-area">
                                    <h6 class="mb-0">@lang("Phone:")</h6>
                                    <p class="mb-0">@lang(@$contact['single']['phone'])</p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="icon-area">
                                    <i class="fa-light fa-envelope"></i>
                                </div>
                                <div class="content-area">
                                    <h6 class="mb-0">@lang("Email:")</h6>
                                    <p class="mb-0">@lang(@$contact['single']['email'])</p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="icon-area">
                                    <i class="fa-light fa-location-dot"></i>
                                </div>
                                <div class="content-area">
                                    <h6 class="mb-0">@lang("Address:")</h6>
                                    <p class="mb-0">@lang(@$contact['single']['address'])</p>
                                </div>
                            </div>
                        </div>
                        <div class="social-area">
                            <h5>@lang("Socials")</h5>
                            <ul class="d-flex mt-20">
                                @forelse($contact['multiple'] as $item)
                                    <li><a href="{{ @$item['media']->link }}"><i class="{{ @$item['media']->icon }}"></i></a>
                                    </li>
                                @empty
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-6">
                    <div class="contact-message-area">
                        <div class="contact-header">
                            <h3 class="section-title">@lang(@$contact['single']['form_heading'])</h3>
                            <p>@lang(@$contact['single']['form_short_description'])
                            </p>
                        </div>
                        <form action="{{ route('contact.send') }} " method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <input type="text" class="form-control" name="name" placeholder="@lang("Your Name")"
                                           autocomplete="off">
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <input type="email" class="form-control" name="email"
                                           placeholder="@lang("E-mail Address")"
                                           autocomplete="off">
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-12">
                                    <input type="text" class="form-control" name="subject"
                                           placeholder="@lang("Your Subject")"
                                           autocomplete="off">
                                    @error('subject')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb  -3 col-12">
                                        <textarea class="form-control" name="message"
                                                  rows="5"
                                                  placeholder="@lang("Your Massage")"></textarea>
                                    @error('message')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="btn-area d-flex justify-content-end">
                                <button type="submit" class="cmn-btn w-100">@lang("Send a massage")</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact section end -->
