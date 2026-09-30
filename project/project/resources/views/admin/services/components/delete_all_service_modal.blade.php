<!-- Delete Modal -->
<div class="modal fade" id="deleteAllServiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteAllServiceModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteAllServiceModalLabel">
                    <i class="bi bi-check2-square"></i> @lang("Delete All Services")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="allServicedDeleteRoute">
                @csrf
                @method("delete")
                <div class="modal-body">
                    <p>@lang("Are you sure you want to delete all services from this?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Delete Modal -->
