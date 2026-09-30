<!-- Auto Update Currency Modal -->
<div class="modal fade" id="autoUpdateCurrencyModal" tabindex="-1" role="dialog"
     aria-labelledby="autoUpdateCurrencyModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="autoUpdateCurrencyModalLabel">
                    <i class="bi bi-check2-square"></i> @lang("Confirmation")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="autoUpdateCurrency">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="currency" id="currency_code">
                    <p>@lang("Do you want to auto update currency rate?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

