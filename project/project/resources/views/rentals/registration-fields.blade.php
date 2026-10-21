@foreach(\App\Services\RentalRegistration::fields(\App\Services\PanelRentals::current()) as $key => $field)
    <div class="zk-field">
        <label for="rental-{{ $key }}">{{ $field['label'] }}{{ $field['required'] ? ' *' : '' }}</label>
        <input id="rental-{{ $key }}" name="rental_fields[{{ $key }}]" type="text" class="form-control" maxlength="200"
            value="{{ old('rental_fields.'.$key) }}" @required($field['required']) aria-describedby="rental-error-{{ $key }}">
        @error('rental_fields.'.$key)<span id="rental-error-{{ $key }}" class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
@endforeach
