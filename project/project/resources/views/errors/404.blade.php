@php
    $theme = getTheme() ?: 'light_green';
@endphp
@if(view()->exists("themes.{$theme}.errors.404"))
    @include("themes.{$theme}.errors.404")
@else
    @include("themes.light_green.errors.404")
@endif
