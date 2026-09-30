<!-- Status Change Modal  -->
<div class="modal fade" id="statusChangeModal" tabindex="-1" role="dialog" aria-labelledby="statusChangeModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="statusChangeModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="setRoute">
                @csrf
                <div class="modal-body">
                    <div class="tom-select-custom">
                        <select class="js-select form-select" name="status" autocomplete="off"
                                data-hs-tom-select-options='{
                                  "hideSearch": true
                                }'>
                            <option value="awaiting">@lang('Awaiting')</option>
                            <option value="pending">@lang('Pending')</option>
                            <option value="processing">@lang('Processing')</option>
                            <option value="progress">@lang('In progress')</option>
                            <option value="completed">@lang('Completed')</option>
                            <option value="partial">@lang('Partial')</option>
                            <option value="canceled">@lang('Canceled')</option>
                            <option value="refunded">@lang('Refunded')</option>
                        </select>
                    </div>
                    <p class="mb-0 mt-3">@lang("Do you want to change this status.")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Status Change Modal -->
