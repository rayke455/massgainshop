<!-- FAQ -->
<section class="faq-section faq-page">
    <div class="container">
        <div class="row g-4 gy-5 justify-content-center align-items-center">
            <div class="col-lg-12">
                <div class="accordion" id="accordionExample">
                    @forelse($faq['multiple'] as $key => $item)
                        <div class="accordion-item">
                            <h5 class="accordion-header" id="heading{{$key}}">
                                <button
                                    class="accordion-button {{ $key == 0 ? '' : 'collapsed' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{$key}}"
                                    aria-expanded="true"
                                    aria-controls="collapse{{$key}}"
                                >
                                    @lang(@$item['title'])
                                </button>
                            </h5>
                            <div
                                id="collapse{{$key}}"
                                class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                aria-labelledby="heading{{$key}}"
                                data-bs-parent="#accordionExample"
                            >
                                <div class="accordion-body">
                                    @lang(@$item['description'])
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
<!-- /FAQ -->
