<!-- Balance update Modal -->
<div class="modal fade" id="updateProviderBalanceModal" tabindex="-1" role="dialog"
     aria-labelledby="updateProviderBalanceModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="updateProviderBalanceModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="updateBalanceForm">
                @csrf
                <div class="modal-body">
                    <p>@lang("Do you want to update provider balance?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Balance update Modal -->
