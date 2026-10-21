<?php
namespace App\Http\Controllers;
use App\Models\RentalPanel;
use App\Services\{PanelRentals, RentalPromotions};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RentalPromotionController extends Controller
{
    private function panel(int $panelId): RentalPanel {
        $panel = RentalPanel::where('owner_id', auth()->id())->findOrFail($panelId);
        abort_unless($panel->allows('accounts'), 403);
        return $panel;
    }
    public function index(int $panelId) {
        $panel = $this->panel($panelId);
        $promotions = DB::table('rental_promotions')->where('panel_id', $panel->id)->orderByDesc('id')->paginate(25);
        return view('rentals.promotions', compact('panel', 'promotions'));
    }
    public function store(Request $request, int $panelId) {
        $panel = $this->panel($panelId);
        $request->validate(['code' => 'required|string|max:40']);
        $request->merge(['code' => strtoupper(trim((string)$request->input('code')))]);
        $data = $request->validate([
            'code' => ['required', 'string', 'regex:/^[A-Z0-9_-]{4,40}$/', Rule::unique('rental_promotions')->where('panel_id', $panel->id)],
            'amount' => 'required|numeric|min:0.0001|max:1000000|decimal:0,4',
            'max_uses' => 'required|integer|min:1|max:100000', 'expires_at' => 'nullable|date|after:now',
        ]);
        DB::transaction(function () use ($panel, $data) {
            $id = DB::table('rental_promotions')->insertGetId($data + ['panel_id' => $panel->id, 'created_at' => now(), 'updated_at' => now()]);
            PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'promotion_created', ['id' => $id]);
        });
        return back()->with('success', 'Balance promotion created. Your wallet is charged only when customers redeem it.');
    }
    public function status(Request $request, int $panelId, int $promotionId) {
        $panel = $this->panel($panelId);
        $data = $request->validate(['active' => 'required|boolean']);
        DB::transaction(function () use ($panel, $promotionId, $data) {
            $promo = DB::table('rental_promotions')->where('panel_id', $panel->id)->where('id', $promotionId)->lockForUpdate()->first();
            abort_unless($promo, 404);
            DB::table('rental_promotions')->where('id', $promo->id)->update(['active' => $data['active'], 'updated_at' => now()]);
            PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'promotion_status', ['id' => $promo->id, 'active' => $data['active']]);
        });
        return back()->with('success', 'Promotion updated.');
    }
    public function redeem(Request $request) {
        $panel = PanelRentals::current(); abort_unless($panel, 404);
        $data = $request->validate(['code' => 'required|string|max:40']);
        RentalPromotions::redeem($panel, auth()->user(), $data['code']);
        return back()->with('success', 'Promotion redeemed. Your wallet has been credited.');
    }
}
