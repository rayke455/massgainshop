<div class="modal fade" id="disableModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="disableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="disableModalLabel">@lang("Please verify your OTP to disable.")</h1>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-light fa-xmark"></i>
                </button>
            </div>
            <form action="{{route('user.two.step.disable')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="input-box pt-2 pb-2">
                        <input type="password" class="form-control" name="password"
                               placeholder="@lang('Enter Your Password')" autocomplete="off">
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
