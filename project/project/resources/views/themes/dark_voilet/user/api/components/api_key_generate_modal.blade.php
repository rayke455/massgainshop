<div class="modal fade" id="apiKeyGenerateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="apiKeyGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="apiKeyGenerateModalLabel">@lang("Generate Api Key")</h1>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-light fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('user.keyGenerate') }}" method="post">
                @csrf
                <div class="modal-body">
                    <p>@lang("Would you like to generate a new API key?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn rounded-1"
                            data-bs-dismiss="modal">@lang("Close")</button>
                    <button type="submit" class="cmn-btn rounded-1">@lang("Submit")</button>
                </div>
            </form>
        </div>
    </div>
</div>
