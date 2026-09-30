<!-- faq_page_start -->
<section class="faq_area">
    <div class="container">
        <div class="row">
            <div class="section_header text-center text-sm-start">
                <h2>@lang(@$faq['single']['title'])</h2>
                <p class="para_text">@lang(@$faq['single']['sub_title'])</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-12">
                <div class="accordion" id="accordionExample">
                    @forelse($faq['multiple'] as $k => $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{$k}}">
                                <button class="accordion-button {{ $k == 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{$k}}"
                                        aria-expanded="true" aria-controls="collapse{{$k}}">
                                    @lang(@$item['title'])
                                </button>
                            </h2>
                            <div id="collapse{{$k}}" class="accordion-collapse collapse {{ $k == 0 ? 'show' : '' }}"
                                 aria-labelledby="heading{{$k}}"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        @lang(@$item['description'])
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
</section>
<!-- faq_page_end -->



