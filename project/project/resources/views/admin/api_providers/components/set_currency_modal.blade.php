<!-- Set Currency Modal -->
<div class="modal fade" id="setCurrencyModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="setCurrencyModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="setCurrencyModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="currencySet">
                @csrf
                <div class="modal-body">
                    <label for="conversionRateLabel" class="form-label">@lang("Conversion Rate")</label>
                    <div class="input-group">
                        <span class="input-group-text">1 <span class="api_currency ms-1"></span> = </span>
                        <input type="number" class="form-control" id="convRateInput" name="conversion_rate" step="0.00000001"
                               placeholder="@lang('Conversion Rate')" aria-label="@lang("Conversion Rate")">
                        <span class="input-group-text">{{  basicControl()->base_currency ?? 'USD' }}</span>
                    </div>
                    @error('conversion_rate')
                    <span class="invalid-feedback d-block" id="conversionRateError">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                    <button type="submit" class="btn btn-primary">@lang("Save changes")</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Set Currency Modal -->
