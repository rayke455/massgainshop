<!-- Faq section start -->
<section class="faq-section">
    <div class="container">
        <div class="row gy-4 gy-md-5 gx-sm-5 align-items-center">
            <div class="col-md-6">
                <div class="left-side">
                    <span class="section-subtitle">@lang(@$faq['single']['title'])</span>
                    <h2 class="section-title">
                        @lang(@$faq['single']['heading'])
                    </h2>
                    <p class="cmn-para-text mx-auto mt-20">@lang(strip_tags(@$faq['single']['description']))</p>
                    <div class="btn-area mt-30">
                        <a href="{{ @$faq['single']['media']->button_link }}"
                           class="cmn-btn">@lang(@$faq['single']['button_name'])</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="faq-content">
                    <div class="accordion" id="faqAccordion">
                        @forelse($faq['multiple'] as $key => $item)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$key}}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{$key}}" aria-expanded="true"
                                            aria-controls="collapse{{$key}}">
                                        @lang(@$item['question'])
                                    </button>
                                </h2>
                                <div id="collapse{{$key}}"
                                     class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                     aria-labelledby="heading{{$key}}"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <p>
                                                @lang(strip_tags(@$item['answer']))
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Faq section end -->
