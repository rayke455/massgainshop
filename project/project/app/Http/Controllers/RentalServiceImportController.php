<?php
namespace App\Http\Controllers;

use App\Models\{RentalPanel, Service};
use App\Services\PanelRentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalServiceImportController extends Controller
{
    private function owned(int $id): RentalPanel
    {
        $panel = RentalPanel::where('owner_id', auth()->id())->findOrFail($id);
        abort_unless($panel->allows('catalogue'), 403);
        return $panel;
    }

    private function available()
    {
        return Service::where('service_status', 1)
            ->whereHas('category', fn($q) => $q->where('status', 1))
            ->where(fn($q) => $q->whereHas('provider', fn($p) => $p->where('status', 1))->orWhereDoesntHave('provider'));
    }

    public function index(Request $request, int $id)
    {
        $panel = $this->owned($id);
        $data = $request->validate(['q' => 'nullable|string|max:100']);
        $services = $this->available()->with('category')
            ->when($data['q'] ?? null, fn($q, $term) => $q->where('service_title', 'like', '%'.$term.'%'))
            ->orderBy('category_id')->orderBy('id')->paginate(40)->withQueryString();
        $currency = PanelRentals::currency($panel);
        return view('rentals.service-import', compact('panel', 'services', 'currency'));
    }

    public function store(Request $request, int $id)
    {
        $panel = $this->owned($id);
        $data = $request->validate([
            'mode' => 'required|in:all,selected', 'services' => 'nullable|array|max:200',
            'services.*' => 'required|integer|distinct|min:1', 'markup' => 'nullable|numeric|min:0|max:1000',
        ]);
        if (isset($data['markup'])) abort_unless($panel->allows('markup'), 403);
        $ids = array_map('intval', $data['services'] ?? []);
        $services = $this->available()->whereIn('id', $ids)->get();
        abort_unless($services->count() === count($ids), 422, 'A selected service is no longer available. Refresh and select again.');
        DB::transaction(function () use ($panel, $data, $ids, $services) {
            $locked = RentalPanel::whereKey($panel->id)->lockForUpdate()->firstOrFail();
            $settings = $locked->workspace();
            $settings['catalogue_mode'] = $data['mode'];
            $settings['imported_service_ids'] = array_values(array_unique(array_merge($settings['imported_service_ids'] ?? [], $ids)));
            $locked->workspace_settings = $settings;
            $locked->save();
            foreach ($services as $service) {
                $values = ['hidden' => false, 'updated_at' => now()];
                if (isset($data['markup'])) {
                    $cur = PanelRentals::currency($locked);
                    $values['price'] = round(PanelRentals::costPerK($service, $locked) * (float) $cur->conversion_rate * (1 + (float) $data['markup'] / 100), 4);
                }
                DB::table('rental_panel_prices')->updateOrInsert(['panel_id' => $locked->id, 'service_id' => $service->id], $values);
            }
            PanelRentals::audit($locked->id, 'user:'.auth()->id(), 'services_imported', ['service_ids' => $ids, 'mode' => $data['mode'], 'markup' => $data['markup'] ?? null]);
        });
        return back()->with('success', count($ids).' services imported. Catalogue settings saved.');
    }
}
