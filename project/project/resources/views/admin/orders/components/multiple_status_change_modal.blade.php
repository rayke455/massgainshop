<div class="modal fade" id="multipleStatusChangeModal" tabindex="-1" role="dialog"
     aria-labelledby="multipleStatusChangeModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="multipleStatusChangeModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <p>@lang("Are you really want to <span class='text-info text-status'>Awaiting</span> this orders?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary statusChangeMultiple">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
