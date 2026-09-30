<!-- Info Modal -->
<div class="modal fade" id="orderDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="orderDetailsModalLabel">@lang("Order Details")</h1>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-light fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>@lang("Service:")</strong> <span class="title"></span></p>
                <p><strong>@lang("Link:")</strong> <span class="link"></span></p>
                <p><strong>@lang("Quantity:")</strong> <span class="quantity"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="delete-btn" data-bs-dismiss="modal">@lang("Close")</button>
            </div>
        </div>
    </div>
</div>
<!-- End Info Modal -->
