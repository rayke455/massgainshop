<!-- Single Delete Modal -->
<div class="modal fade" id="orderResendModal" tabindex="-1" role="dialog" aria-labelledby="orderResendModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="orderResendModalLabel">
                    <i class="bi bi-check2-square"></i> @lang("Confirmation")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="setResendRoute">
                @csrf
                <div class="modal-body">
                    <p>@lang("Are you sure you want to resend this order?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Single Delete Modal -->
