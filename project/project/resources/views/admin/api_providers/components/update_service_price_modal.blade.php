<!-- Update Service Modal -->
<div class="modal fade" id="updatePriceModal" tabindex="-1" role="dialog"
     aria-labelledby="updatePriceModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="updatePriceModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="updatePriceForm">
                @csrf
                <div class="modal-body">
                    <p>@lang("Do you want to update provider's services price?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Update Service Modal -->
