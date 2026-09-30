<div class="modal fade" id="orderSummeryModal" tabindex="-1" role="dialog" aria-labelledby="orderSummeryModalLabel"
     data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header border-0 bg-light pb-4">
                <h4 class="modal-title d-flex align-items-center gap-2" id="orderSummeryModalLabel">
                    <i class="bi bi-clipboard-check text-primary fs-4"></i> @lang("Order Summary")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="border rounded p-3 bg-light-subtle">
                            <h6 class="text-muted mb-1">@lang('Service')</h6>
                            <p class="fw-medium text-dark mb-0" id="modalService"></p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="border rounded p-3">
                            <h6 class="text-muted mb-1">@lang('Link')</h6>
                            <a href="http://smm_matrix.test/user/order" class="text-decoration-none fw-medium text-primary" target="_blank" id="modalLink">

                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h6 class="text-muted mb-1">@lang('Price')</h6>
                            <p class="fw-semibold text-success mb-0" id="modalPrice"></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h6 class="text-muted mb-1">@lang('Quantity')</h6>
                            <p class="fw-semibold mb-0" id="modalQuantity"></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h6 class="text-muted mb-1">@lang('Start Counter')</h6>
                            <p class="fw-semibold text-body mb-0" id="modalStartCounter"></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h6 class="text-muted mb-1">@lang('Remains')</h6>
                            <p class="fw-semibold text-danger mb-0" id="modalRemains"></p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> @lang('Close')
                </button>
            </div>
        </div>
    </div>
</div>
