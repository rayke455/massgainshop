<div class="modal fade" id="enableModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="enableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header bg-light border-bottom-0 pb-3">
                <h4 class="modal-title text-success fw-semibold" id="enableModalLabel">
                    <i class="fa-light fa-shield"></i> @lang("Enable Two-Factor Authentication")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.two.step.enable') }}" method="POST">
                @csrf
                <input type="hidden" name="key" value="{{ $secret }}">

                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="mb-3">
                        <label for="code" class="form-label fw-semibold">@lang("Google Authenticator Code")</label>
                        <input type="text" class="form-control form-control-lg" id="code" name="code"
                               placeholder="@lang('Enter 6-digit code')" autocomplete="off" required>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        @lang("Cancel")
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check-circle me-1"></i> @lang("Verify & Enable")
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
