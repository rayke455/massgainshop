<!-- Import Service Modal -->
<div class="modal fade" id="importServiceModal" tabindex="-1" role="dialog"
     aria-labelledby="importServiceModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="importServiceModalLabel">
                    <i class="bi bi-check2-square"></i> @lang("Confirm Import Services")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.get.api.services') }}" method="post" class="getServiceRoute">
                @csrf
                <div class="modal-body">
                    <label for="apiProviderLabel" class="form-label">@lang("API Provider Name")</label>
                    <div class="tom-select-custom">
                        <select class="js-select form-select" name="api_provider_id" id="api_provider_id" autocomplete="off"
                                data-hs-tom-select-options='{
                                  "placeholder": "Select Provider",
                                  "hideSearch": true
                                }'>
                            <option selected="" disabled>@lang("Select Provider")</option>
                            @foreach($apiProviders as $apiProvider)
                                <option value="{{ $apiProvider->id }}">{{ $apiProvider->api_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('api_provider_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-sm btn-primary">@lang('Get Service')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Import Service Modal -->
