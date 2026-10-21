<?php
namespace App\Http\Controllers;

use App\Models\RentalContent;
use App\Models\RentalPanel;
use App\Services\PanelRentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RentalContentController extends Controller
{
    private function panel(int $panelId): RentalPanel
    {
        $panel = RentalPanel::where('owner_id', auth()->id())->findOrFail($panelId);
        abort_unless($panel->allows('branding'), 403);
        return $panel;
    }

    public function index(Request $request, int $panelId)
    {
        $panel = $this->panel($panelId);
        $request->validate(['edit' => 'nullable|integer|min:1']);
        $selected = $request->filled('edit') ? RentalContent::where('panel_id', $panel->id)->findOrFail($request->integer('edit')) : null;
        $entries = RentalContent::where('panel_id', $panel->id)->orderBy('position')->orderBy('id')->paginate(25);
        return view('rentals.content-manager', compact('panel', 'selected', 'entries'));
    }

    public function store(Request $request, int $panelId)
    {
        $panel = $this->panel($panelId);
        $request->validate(['id' => 'nullable|integer|min:1']);
        $entry = $request->filled('id') ? RentalContent::where('panel_id', $panel->id)->findOrFail($request->integer('id')) : new RentalContent();
        $data = $request->validate([
            'kind' => ['required', Rule::in(['page', 'link', 'faq', 'notice'])],
            'slug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('rental_content')->where('panel_id', $panel->id)->ignore($entry->id)],
            'title' => 'required|string|max:160',
            'body' => 'required_unless:kind,link|nullable|string|max:20000',
            'url' => ['required_if:kind,link', 'nullable', 'url', 'max:2048', 'regex:#^https?://#i'],
            'published' => 'required|boolean', 'position' => 'required|integer|min:0|max:10000',
        ]);
        if ($data['kind'] !== 'link') $data['url'] = null;
        DB::transaction(function () use ($panel, $entry, $data) {
            // Serialize creates so the per-panel content limit cannot be raced.
            RentalPanel::whereKey($panel->id)->lockForUpdate()->firstOrFail();
            abort_if(!$entry->exists && RentalContent::where('panel_id', $panel->id)->count() >= 100, 422, 'A panel may have up to 100 content entries.');
            $entry->fill($data);
            $entry->panel_id = $panel->id;
            $entry->save();
            PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'content_saved', ['id' => $entry->id, 'kind' => $entry->kind, 'published' => $entry->published]);
        });
        return redirect()->route('user.panels.content', ['panelId' => $panel->id, 'edit' => $entry->id])->with('success', 'Content saved.');
    }

    public function page(RentalPanel $panel, string $slug)
    {
        abort_unless($panel->allows('branding'), 404);
        $entry = RentalContent::visibleFor($panel, ['page'])->where('slug', $slug)->firstOrFail();
        return response()->view('rentals.content-page', ['panel' => $panel, 'title' => $entry->title, 'body' => $entry->body]);
    }

    public function publicPage(string $slug, string $pageSlug)
    {
        return $this->page(RentalPanel::where('slug', $slug)->firstOrFail(), $pageSlug);
    }

    public function publicFaq(string $slug)
    {
        return $this->faq(RentalPanel::where('slug', $slug)->firstOrFail());
    }

    public function faq(RentalPanel $panel)
    {
        abort_unless($panel->allows('branding'), 404);
        return response()->view('rentals.faq', ['panel' => $panel, 'entries' => RentalContent::visibleFor($panel, ['faq'])->get()]);
    }
}
