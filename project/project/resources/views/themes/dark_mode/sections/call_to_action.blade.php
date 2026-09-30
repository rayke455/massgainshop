<section class="learn-more">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 pb-5 pb-lg-0">
                <h3>@lang(@$call_to_action['single']['title'])</h3>
                <h5 class="my-4">
                    @lang(@$call_to_action['single']['sub_title'])
                </h5>
                <a href="{{ @$call_to_action['single']['media']->button_link }}" class="btn-smm">
                    @lang(@$call_to_action['single']['button_name'])
                </a>
            </div>
            <div class="col-lg-6">
                <div class="img-box text-center">
                    <img class="img-fluid"
                         src="{{ getFile(@$call_to_action['single']['media']->image->driver, @$call_to_action['single']['media']->image->path) }}"
                         alt="@lang('call-to-action image')"/>
                </div>
            </div>
        </div>
    </div>
</section>
