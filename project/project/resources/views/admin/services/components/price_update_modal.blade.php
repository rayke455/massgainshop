<!-- Delete Modal -->
<div class="modal fade" id="priceUpdateModal" tabindex="-1" role="dialog" aria-labelledby="priceUpdateModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="priceUpdateModalLabel">
                    <i class="bi bi-check2-square"></i> @lang("Price Update Confirmation")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="priceUpdateRoute">
                @csrf
                <div class="modal-body">
                    <p class="text-dark">@lang("Do you want to price update this service?")</p>

                    <label class="form-label">@lang('Select Percentage Increase')</label>
                    <div class="tom-select-custom">
                        <select class="js-select form-select price_percentage_increase" name="price_percentage_increase" autocomplete="off"
                                data-hs-tom-select-options='{
                                "placeholder": "Select Percentage Increase"
                              }'>
                            <option value="100" selected>@lang('100%')</option>
                            @for($loop = 0; $loop <= 1000; $loop++)
                                <option value="{{ $loop }}">{{ $loop }} %</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary price-update">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Delete Modal -->
