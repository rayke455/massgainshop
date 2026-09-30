<div class="modal fade" id="regenerateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="regenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="regenerateModalLabel">@lang("Regenerate Code")</h1>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-light fa-xmark"></i>
                </button>
            </div>
            <form action="{{route('user.two.step.regenerate')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang("Would you like to re-generate the authenticator code?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="delete-btn rounded-1" data-bs-dismiss="modal">@lang("No")</button>
                    <button type="submit" class="cmn-btn rounded-1">@lang("Yes")</button>
                </div>
            </form>
        </div>
    </div>
</div>
