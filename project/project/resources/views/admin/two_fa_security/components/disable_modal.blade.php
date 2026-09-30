<div class="modal fade" id="disableModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="disableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-light border-bottom-0">
                <h4 class="modal-title text-primary fw-semibold mb-3" id="disableModalLabel">
                    <i class="fa fa-lock me-2"></i> @lang("Verify to Disable 2FA")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.two.step.disable') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3 pb-1">
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">@lang("Enter Your Password")</label>
                        <input type="password" class="form-control form-control-lg" name="password" id="password"
                               placeholder="@lang('Password')" autocomplete="off" required>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        @lang("Cancel")
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-check-circle me-1"></i> @lang("Verify & Disable")
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
