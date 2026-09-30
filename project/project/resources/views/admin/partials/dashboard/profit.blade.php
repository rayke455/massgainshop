{{-- Profit overview: what customers paid vs what providers charged. --}}
@php
    $zkPeriods = ['today' => __('Today'), 'month' => __('This month'), 'all' => __('All time')];
@endphp
<div class="card mb-3 mb-lg-5">
    <div class="card-header card-header-content-sm-between">
        <h4 class="card-header-title mb-2 mb-sm-0"><i class="bi-graph-up-arrow me-1 text-success"></i> @lang('Your profit')</h4>
        <a href="{{ route('admin.service.index') }}" class="btn btn-white btn-sm">@lang('Profit per service') <i class="bi-arrow-right ms-1"></i></a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($zkPeriods as $key => $label)
                @php $p = $profit[$key]; @endphp
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <span class="text-muted small text-uppercase fw-semibold">{{ $label }}</span>
                        <div class="h2 mb-1 {{ $p['profit'] < 0 ? 'text-danger' : 'text-success' }}">{{ currencyPosition($p['profit']) }}</div>
                        <div class="small text-body">
                            {{ number_format($p['orders']) }} @lang('orders') ·
                            @lang('sales') {{ currencyPosition($p['revenue']) }} ·
                            @lang('provider cost') {{ currencyPosition($p['cost']) }}
                        </div>
                        @if($p['margin'] !== null)
                            <span class="badge bg-soft-success text-success mt-2">{{ number_format($p['margin'], 0) }}% @lang('of sales is profit')</span>
                        @endif
                        @if($p['estimated'])
                            <span class="badge bg-soft-secondary text-secondary mt-2" title="@lang('Orders placed before cost tracking use the current provider price')">{{ $p['estimated'] }} @lang('estimated')</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if(count($profit['top']))
            <h5 class="mt-4 mb-2">@lang('Most profitable services this month')</h5>
            <ul class="list-group list-group-flush">
                @foreach($profit['top'] as $row)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-truncate me-3">{{ \Illuminate\Support\Str::limit($row->service_title, 70) }}
                            <small class="text-muted">· {{ $row->orders }} @lang('orders')</small></span>
                        <span class="fw-semibold {{ $row->profit < 0 ? 'text-danger' : 'text-success' }}">{{ $row->profit >= 0 ? '+' : '' }}{{ currencyPosition($row->profit) }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
