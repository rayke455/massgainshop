@extends(template().'layouts.user')
@section('title',trans('Mass Order'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("mass order")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Mass Order")</li>
                </ol>
            </nav>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>@lang("mass order")</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <form action="{{ route('user.draft.order.store') }}" method="post">
                                @csrf
                                <div class="col-12">
                                    <label for="description"
                                           class="form-label">@lang("One order per line in format")</label>
                                    <textarea class="form-control" name="mass_order" id="description" rows="12"
                                              placeholder="service_id | quantity | link">{{ old('mass_order') }}</textarea>
                                </div>
                                @error('mass_order')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="cmn-btn mt-20">@lang("continue")</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>@lang(@$howToMassOrder->description->heading)</h4>
                    </div>
                    <div class="card-body">
                        {!! __(@$howToMassOrder->description->description) !!}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


