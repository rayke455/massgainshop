<?php

namespace App\Http\Controllers;

use App\Models\RentalPanel;
use App\Services\PanelRentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalSupportController extends Controller
{
    private function panel(?int $panelId): RentalPanel
    {
        if ($panelId !== null) {
            $panel = RentalPanel::where('owner_id', auth()->id())->findOrFail($panelId);
        } else {
            abort_unless(app()->bound('rental.panel'), 404);
            $panel = app('rental.panel');
            abort_unless((int) auth()->user()->rental_panel_id === (int) $panel->id, 403);
        }
        abort_unless($panel->allows('accounts'), 403);
        return $panel;
    }

    public function index(Request $request, ?int $panelId = null)
    {
        $panel = $this->panel($panelId);
        $owner = $panelId !== null;
        $query = DB::table('rental_tickets')->where('panel_id', $panel->id);
        if (!$owner) $query->where('customer_id', auth()->id());
        $selected = null;
        $messages = collect();
        if ($request->filled('ticket')) {
            $request->validate(['ticket' => 'required|integer|min:1']);
            $selected = (clone $query)->where('id', $request->integer('ticket'))->first();
            abort_unless($selected, 404);
            $messages = DB::table('rental_ticket_messages')->where('ticket_id', $selected->id)->orderBy('id')->get();
        }
        $tickets = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();
        return view('rentals.support', compact('panel', 'owner', 'tickets', 'selected', 'messages'));
    }

    public function store(Request $request, ?int $panelId = null)
    {
        $panel = $this->panel($panelId);
        $owner = $panelId !== null;
        $data = $request->validate([
            'ticket' => 'nullable|integer|min:1',
            'subject' => 'required_without:ticket|nullable|string|max:160',
            'body' => 'required|string|max:10000',
            'status' => 'nullable|in:open,closed',
        ]);
        abort_if($owner && empty($data['ticket']), 422);
        $id = DB::transaction(function () use ($panel, $owner, $data) {
            if (!empty($data['ticket'])) {
                $query = DB::table('rental_tickets')->where('panel_id', $panel->id)->where('id', $data['ticket']);
                if (!$owner) $query->where('customer_id', auth()->id());
                $ticket = $query->lockForUpdate()->first();
                abort_unless($ticket, 404);
                $id = $ticket->id;
                DB::table('rental_tickets')->where('id', $id)->update([
                    'status' => ($data['status'] ?? '') === 'closed' ? 'closed' : ($owner ? 'answered' : 'open'),
                    'updated_at' => now(),
                ]);
            } else {
                $id = DB::table('rental_tickets')->insertGetId([
                    'panel_id' => $panel->id, 'customer_id' => auth()->id(),
                    'subject' => $data['subject'], 'status' => 'open', 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            DB::table('rental_ticket_messages')->insert([
                'ticket_id' => $id, 'author_id' => auth()->id(), 'author_role' => $owner ? 'owner' : 'customer',
                'body' => $data['body'], 'created_at' => now(), 'updated_at' => now(),
            ]);
            PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'support_message', ['ticket' => $id]);
            return $id;
        });
        return redirect()->route($owner ? 'user.panels.support' : 'rental.support',
            $owner ? ['panelId' => $panel->id, 'ticket' => $id] : ['ticket' => $id])->with('success', 'Message sent.');
    }
}
