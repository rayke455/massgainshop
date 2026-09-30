<!-- Active & Deactivate Modal -->
<div class="modal fade" id="activeDeActiveModal" tabindex="-1" role="dialog"
     aria-labelledby="activeDeActiveModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="activeDeActiveModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="statusForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="status" name="status">
                    <p>@lang("Do you want to active this api provider?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Active & Deactivate Modal -->
