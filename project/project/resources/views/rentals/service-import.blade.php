@extends('rentals.layout')
@section('title', 'Import services')
@include('rentals.owner-subpage')
@section('nav')<a href="{{ route('user.panels.show', $panel->id) }}#services">Back to services</a>@endsection
@section('content')
<h1>Import services</h1><p>Provider: <strong>Mass Panel Shop</strong>. Imports affect only {{ $panel->name }}.</p>
<form method="get"><label for="service-search">Find a service</label><input id="service-search" type="search" name="q" value="{{ request('q') }}" maxlength="100"><button>Search</button></form>
<form method="post" action="{{ route('user.panels.services.import.store', $panel->id) }}">@csrf
<label for="catalogue-mode">Customer catalogue</label><select name="mode" id="catalogue-mode"><option value="all" @selected(($panel->workspace()['catalogue_mode'] ?? 'all') === 'all')>All available platform services</option><option value="selected" @selected(($panel->workspace()['catalogue_mode'] ?? 'all') === 'selected')>Only services I have imported</option></select>
<p class="muted">Selecting “only imported” excludes other services from new orders. Existing orders are preserved. Imports are additive across pages; hiding a service is available in Your prices.</p>
@if($panel->allows('markup'))<label for="import-markup">Markup on selected services (%)</label><input id="import-markup" name="markup" type="number" min="0" max="1000" step="0.01" placeholder="Leave empty to retain existing prices"><small>Calculated above your platform service cost, in {{ $currency->code }}.</small>@endif
<label class="inline"><input id="select-visible" type="checkbox" style="width:auto"> Select services on this page</label>
<div class="table-wrap"><table><thead><tr><th>Select</th><th>Service</th><th>Your cost / unit</th><th>Imported</th></tr></thead><tbody>
@forelse($services as $service)
<tr><td><input class="import-service" type="checkbox" name="services[]" value="{{ $service->id }}" aria-label="Select service {{ $service->id }}" style="width:auto"></td><td>{{ \App\Services\SmartImport::cleanName($service->service_title) }}<small>#{{ $service->id }} · {{ $service->category->category_title }}</small></td><td>{{ number_format(\App\Services\PanelRentals::costPerK($service,$panel) * $currency->conversion_rate, 4) }} {{ $currency->code }}<small>{{ \App\Services\PanelRentals::quantityBased($service) ? 'per 1,000' : 'per item' }}</small></td><td>{{ in_array((int)$service->id, $panel->workspace()['imported_service_ids'] ?? [], true) ? 'Yes' : 'Not selected' }}</td></tr>
@empty<tr><td colspan="4">No services match this search.</td></tr>@endforelse
</tbody></table></div><button>Import selected & save catalogue</button>
</form>{{ $services->links() }}
<script>document.getElementById('select-visible').addEventListener('change',function(){document.querySelectorAll('.import-service').forEach(input=>input.checked=this.checked);});</script>
@endsection
