<!-- Multiple Delete Modal -->
<div class="modal fade" id="multipleDeleteModal" tabindex="-1" role="dialog" aria-labelledby="multipleDeleteModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="multipleDeleteModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <p>@lang("Do you want to delete these categories?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary multiple-delete">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Multiple Delete Modal -->
