<!-- Multiple Active DeActive Modal -->
<div class="modal fade" id="multipleActiveDeActiveModal" tabindex="-1" role="dialog"
     aria-labelledby="multipleActiveDeActiveModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="multipleActiveDeActiveModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="statusForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="status" name="status">
                    <p>@lang("Do you want to") <span class="status-text"></span> @lang("these categories?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary active-inactive-multiple">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Multiple Active DeActive Modal -->
