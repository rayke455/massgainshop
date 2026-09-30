{{-- Globe language menu for auto-translate. $zkLangClass lets each header style it. --}}
@if(basicControl()->auto_translate ?? false)
    <label class="zk-lang notranslate {{ $zkLangClass ?? '' }}" title="@lang('Language')">
        <i class="fa-regular fa-globe" aria-hidden="true"></i>
        <span class="visually-hidden">@lang('Language')</span>
        <select class="zk-lang-select {{ $zkLangSelectClass ?? '' }}" aria-label="@lang('Language')">
            @foreach(config('zirka_languages') as $code => $name)
                <option value="{{ $code }}">{{ $name }}</option>
            @endforeach
        </select>
        @if(!empty($zkLangChevron))
            <i class="fa-regular fa-chevron-down zk-currency__chev" aria-hidden="true"></i>
        @endif
    </label>
@endif
