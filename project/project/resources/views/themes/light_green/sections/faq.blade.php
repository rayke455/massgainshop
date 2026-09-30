<!-- Faq section start -->
<section class="zk-section" id="faq">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-5">
                <div class="zk-faq__intro zk-reveal">
                    <span class="zk-eyebrow">@lang(@$faq['single']['title'] ?: 'FAQ')</span>
                    <h2 class="zk-title">{!! zkHighlight(__(@$faq['single']['heading'] ?: 'Questions? We have [answers]')) !!}</h2>
                    <p class="zk-lead">@lang(strip_tags(@$faq['single']['description']))</p>
                    <div class="zk-faq__help">
                        <span class="zk-icon"><i class="fa-regular fa-headset"></i></span>
                        <div>
                            <strong>@lang('Still stuck?')</strong>
                            <span>@lang('Our team replies fast, usually within minutes.')</span>
                        </div>
                        <a href="{{ @$faq['single']['media']->button_link ?: route('page', 'contact') }}" class="zk-btn zk-btn--primary zk-btn--sm">
                            @lang(@$faq['single']['button_name'] ?: 'Contact us')
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="zk-faq zk-reveal" id="zkFaq">
                    @forelse($faq['multiple'] as $key => $item)
                        <details class="zk-faq__item" @if($loop->first) open @endif>
                            <summary>
                                <span>@lang(@$item['question'])</span>
                                <i class="fa-regular fa-plus" aria-hidden="true"></i>
                            </summary>
                            <div class="zk-faq__answer">
                                <p>@lang(strip_tags(@$item['answer']))</p>
                            </div>
                        </details>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Faq section end -->
