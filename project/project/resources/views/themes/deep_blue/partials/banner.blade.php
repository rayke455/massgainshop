@if (Request::is('/') == false)
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header-text text-center">
                        <h3>@lang($pageSeo['page_title'] ?? null)</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif


