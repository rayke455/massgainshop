<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="orderFilterLabel">@lang("Order Filter")</h5>
        <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-light fa-arrow-right"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <form action="" method="get" id="myForm">
            <div class="row g-4">
                <div>
                    <label for="order_id" class="form-label">@lang("Order ID")</label>
                    <input type="text" class="form-control" id="order_id" name="order_id"
                           value="{{ @request()->order_id }}" autocomplete="off">
                </div>
                <div>
                    <label for="service" class="form-label">@lang("Service")</label>
                    <input type="text" class="form-control" id="service" name="service"
                           value="{{ @request()->service }}" autocomplete="off">
                </div>
                <div id="formModal">
                    <label class="form-label">@lang("All Status")</label>
                    <select class="modal-select" name="status">
                        <option value="">@lang("All Status")</option>
                        <option
                            value="awaiting" {{ @request()->status == 'awaiting' ? 'selected' : '' }}>@lang("Awaiting")
                        </option>
                        <option
                            value="pending" {{ @request()->status == 'pending' ? 'selected' : '' }}>@lang("Pending")
                        </option>
                        <option value="processing" {{ @request()->status == 'processing' ? 'selected' : '' }}>
                            @lang("Processing")
                        </option>
                        <option
                            value="progress" {{ @request()->status == 'progress' ? 'selected' : '' }}>@lang("In Progress")
                        </option>
                        <option
                            value="completed" {{ @request()->status == 'completed' ? 'selected' : '' }}>@lang("Completed")
                        </option>
                        <option
                            value="partial" {{ @request()->status == 'partial' ? 'selected' : '' }}>@lang("Partial")</option>
                        <option
                            value="canceled" {{ @request()->status == 'canceled' ? 'selected' : '' }}>@lang("Canceled")
                        </option>
                        <option
                            value="refunded" {{ @request()->status == 'refunded' ? 'selected' : '' }}>@lang("Refunded")
                        </option>
                    </select>
                </div>
                <div>
                    <label for="date" class="form-label">@lang("From Date")</label>
                    <input type="date" class="form-control flatpickr" id="date" name="from_date"
                           value="{{ @request()->from_date }}" autocomplete="off">
                </div>
                <div>
                    <label for="date" class="form-label">@lang("To Date")</label>
                    <input type="date" class="form-control flatpickr" id="date" name="to_date"
                           value="{{ @request()->to_date }}" autocomplete="off">
                </div>
                <div class="btn-area">
                    <button type="submit" class="cmn-btn">@lang("Filter")</button>
                </div>
            </div>
        </form>
    </div>
</div>

