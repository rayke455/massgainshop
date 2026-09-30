<section class="home-section">
    <div class="overlay h-100">
        <div class="container h-100">
            <div class="row h-100 align-items-center gy-5 g-lg-4">
                <div class="col-lg-6">
                    <div class="text-box">
                        <h2>@lang(@$hero['single']['title'])</h2>
                        <p>@lang(@$hero['single']['short_description'])</p>
                        <a href="{{ @$hero['single']['media']->button_link }}" class="btn-smm">
                            @lang(@$hero['single']['button_name'])
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="img-box">
                        <img src="{{ getFile(@$hero['single']['media']->image->driver, @$hero['single']['media']->image->path) }}"
                             alt="@lang('Hero Img')" class="img-fluid"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

