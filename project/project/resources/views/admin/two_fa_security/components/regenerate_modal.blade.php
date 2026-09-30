<div class="modal fade" id="regenerateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="regenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="regenerateModalLabel">@lang("Regenerate Code")</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.two.step.regenerate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-0">
                        @lang("Are you sure you want to regenerate your Google Authenticator code?")
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                        @lang("Cancel")
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        @lang("Yes, Regenerate")
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
