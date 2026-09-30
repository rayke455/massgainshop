<div class="modal fade" id="enableModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="enableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="enableModalLabel">@lang("Verify Your OTP")</h1>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-light fa-xmark"></i>
                </button>
            </div>
            <form action="{{route('user.two.step.enable')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" name="key" value="{{ $secret }}">
                        <input type="text" class="form-control" name="code"
                               placeholder="@lang('Enter Google Authenticator Code')" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="delete-btn rounded-1" data-bs-dismiss="modal">@lang("Close")</button>
                    <button type="submit" class="cmn-btn rounded-1">@lang("Verify")</button>
                </div>
            </form>
        </div>
    </div>
</div>
