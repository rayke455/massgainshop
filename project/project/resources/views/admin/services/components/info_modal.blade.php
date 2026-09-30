<div class="modal fade" id="addInfoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addInfoModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Service Information")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-sm-3 mb-2 mb-sm-0">
                        <div class="d-flex align-items-center mt-2">
                            <i class="fa-light fa-bookmark nav-icon"></i>
                            <div class="flex-grow-1">@lang("Name")</div>
                        </div>
                    </div>
                    <div class="col-sm mt-2">
                        <span id="name">

                        </span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-3 mb-2 mb-sm-0">
                        <div class="d-flex align-items-center mt-2">
                            <i class="fa-light fa-dollar-sign nav-icon"></i>
                            <div class="flex-grow-1">@lang("Rate Per 1K")</div>
                        </div>
                    </div>
                    <div class="col-sm mt-2">
                        <span id="rate-per">

                        </span>
                        <span>{{ basicControl()->base_currency }}</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-3 mb-2 mb-sm-0">
                        <div class="d-flex align-items-center mt-2">
                            <i class="fa-light fa-arrow-down-arrow-up nav-icon"></i>
                            <div class="flex-grow-1">@lang("Order Limit")</div>
                        </div>
                    </div>
                    <div class="col-sm mt-2">
                        <span id="order-limit">

                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer gap-3">
                <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
            </div>
        </div>
    </div>
</div>

