<section id="work-with">
    <div class="container">
        <div class="heading-container">
            <h3 class="slogan">@lang(@$payment_partner['single']['title'])</h3>
        </div>
        <div class="workwith justify-content-start">
            @forelse($payment_partner['multiple'] as $item)
                <img src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}" class="m-2"
                     alt="Partner Img">
            @empty
            @endforelse
        </div>
    </div>
</section>

