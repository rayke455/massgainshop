<!-- Order Refill Modal -->
<div class="modal fade" id="orderRefillModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="staticBackdropLabel">@lang("Order Refill Confirm")</h1>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-light fa-xmark"></i>
                </button>
            </div>
            <form action="" method="post" id="refillConfirm">
                @csrf
                @method('put')
                <div class="modal-body">
                    <p>@lang("Do you really want to refill this orders.")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="delete-btn" data-bs-dismiss="modal">@lang("No")</button>
                    <button type="submit" class="cmn-btn">@lang("Yes")</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Refill Modal -->
