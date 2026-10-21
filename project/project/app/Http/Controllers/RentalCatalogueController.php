<?php

namespace App\Http\Controllers;

use App\Models\{Currency, Service};
use App\Services\{Growth, PanelRentals};
use Illuminate\Http\Request;

class RentalCatalogueController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $panel = app('rental.panel');
        abort_unless($user && (int) $user->rental_panel_id === (int) $panel->id, 403);
        abort_unless($panel->allows('catalogue'), 403);
        $filters = $request->validate([
            'network' => 'nullable|integer|min:1', 'category' => 'nullable|integer|min:1',
            'serviceId' => 'nullable|integer|min:1', 'q' => 'nullable|string|max:100',
        ]);
        $query = Service::with('category.socialMedia')->userRate()->where('service_status', 1)
            ->whereNotIn('id', PanelRentals::hiddenIds($user))
            ->whereHas('category', fn($q) => $q->where('status', 1)->whereHas('socialMedia', fn($n) => $n->where('status', 1)))
            ->where(fn($q) => $q->whereHas('provider', fn($p) => $p->where('status', 1))->orWhereDoesntHave('provider'));
        $selected = !empty($filters['serviceId']) ? (clone $query)->findOrFail($filters['serviceId']) : null;
        $network = $selected?->category?->social_media_id ?? ($filters['network'] ?? null);
        $category = $selected?->category_id ?? ($filters['category'] ?? null);
        $catalogue = (clone $query)->get();
        $networks = $catalogue->pluck('category.socialMedia')->filter()->unique('id')->sortBy('sort_by');
        $categories = $catalogue->filter(fn($s) => !$network || (int) $s->category->social_media_id === (int) $network)
            ->pluck('category')->unique('id')->sortBy('sort_by');
        $services = (clone $query)
            ->when($network, fn($q) => $q->whereHas('category', fn($c) => $c->where('social_media_id', $network)))
            ->when($category, fn($q) => $q->where('category_id', $category))
            ->when($filters['q'] ?? null, fn($q, $term) => $q->where('service_title', 'like', '%'.$term.'%'))
            ->orderBy('id')->paginate(30)->withQueryString();
        $currency = Currency::where('code', $user->currency)->where('status', 1)->first() ?: PanelRentals::currency($panel);
        $rate = fn($service) => Growth::rateFor($service, $user);
        return view('rentals.customer-catalogue', compact('panel', 'user', 'networks', 'categories', 'services', 'selected', 'network', 'category', 'currency', 'rate'));
    }
}
