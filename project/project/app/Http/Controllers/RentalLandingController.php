<?php
namespace App\Http\Controllers;

use App\Models\RentalPanel;
use App\Services\{PanelRentals, RentalLanding};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalLandingController extends Controller
{
    private function panel(int $id): RentalPanel
    {
        $panel = RentalPanel::where('owner_id', auth()->id())->findOrFail($id);
        abort_unless($panel->allows('branding'), 403);
        return $panel;
    }

    public function index(int $panelId)
    {
        $panel = $this->panel($panelId);
        $draft = $panel->workspace()['landing_draft'] ?? RentalLanding::starter();
        return view('rentals.landing-editor', compact('panel', 'draft'));
    }

    public function store(Request $request, int $panelId)
    {
        $this->panel($panelId);
        $data = $request->validate(['action' => 'required|in:save,preview,publish,restore',
            'html' => 'required_unless:action,restore|nullable|string|max:100000', 'css' => 'nullable|string|max:50000']);
        if ($data['action'] === 'preview') {
            return response(RentalLanding::document($data))->header('Content-Security-Policy', "sandbox; default-src 'none'; style-src 'unsafe-inline'; img-src https:; base-uri 'none'; form-action 'none'")->header('Cache-Control', 'no-store');
        }
        DB::transaction(function () use ($panelId, $data) {
            $panel = RentalPanel::where('owner_id', auth()->id())->whereKey($panelId)->lockForUpdate()->firstOrFail();
            abort_unless($panel->allows('branding'), 403);
            $workspace = $panel->workspace();
            if ($data['action'] === 'restore') {
                unset($workspace['landing_published']); // Keep draft so restoring is reversible.
            } else {
                $workspace['landing_draft'] = ['html' => $data['html'], 'css' => $data['css'] ?? ''];
                if ($data['action'] === 'publish') $workspace['landing_published'] = $workspace['landing_draft'];
            }
            $panel->update(['workspace_settings' => $workspace]);
            PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'landing_'.$data['action'], []);
        });
        return redirect()->route('user.panels.landing', $panelId)->with('success', match ($data['action']) {
            'publish' => 'Landing page published.', 'restore' => 'Default landing page restored. Your draft is retained.', default => 'Draft saved. Your published page is unchanged.',
        });
    }
}
