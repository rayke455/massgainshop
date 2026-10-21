<?php
namespace App\Http\Controllers;
use App\Models\{User, Order};
use App\Services\PanelRentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalApiController extends Controller
{
    private function panel() {
        $panel = PanelRentals::current();
        abort_unless($panel && $panel->allows('accounts'), 404);
        return $panel;
    }
    public function settings() {
        $panel = $this->panel();
        abort_unless((int)auth()->user()->rental_panel_id === (int)$panel->id,403);
        $token = DB::table('rental_api_tokens')->where('panel_id',$panel->id)->where('user_id',auth()->id())->first(['expires_at']);
        return response()->view('rentals.api',compact('panel','token'))->header('Cache-Control','no-store, private')->header('Referrer-Policy','no-referrer');
    }
    public function rotate(Request $request) {
        $panel = $this->panel();
        abort_unless((int)auth()->user()->rental_panel_id === (int)$panel->id,403);
        $request->validate(['operation'=>'required|in:rotate,revoke']);
        $secret = bin2hex(random_bytes(32));
        DB::transaction(function () use ($panel,$request,$secret) {
            User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            DB::table('rental_api_tokens')->where('user_id',auth()->id())->delete();
            if ($request->operation === 'rotate') DB::table('rental_api_tokens')->insert([
                'panel_id'=>$panel->id,'user_id'=>auth()->id(),'token_hash'=>hash('sha256',$secret),
                'expires_at'=>now()->addDays(90),'created_at'=>now(),'updated_at'=>now(),
            ]);
            PanelRentals::audit($panel->id,'user:'.auth()->id(),'api_token_'.$request->operation);
        });
        return redirect()->route('rental.api.settings')->with('success',$request->operation === 'rotate' ? 'New key created. The old key no longer works.' : 'API access revoked.')
            ->with('rental_api_secret',$request->operation === 'rotate' ? $secret : null);
    }
    public function dispatch(Request $request) {
        $panel = $this->panel();
        $secret = $request->bearerToken();
        abort_unless(is_string($secret) && preg_match('/^[a-f0-9]{64}$/D',$secret),401);
        $token = DB::table('rental_api_tokens')->where('panel_id',$panel->id)->where('token_hash',hash('sha256',$secret))->where('expires_at','>',now())->first();
        abort_unless($token,401);
        $user = User::where('rental_panel_id',$panel->id)->where('status',1)->find($token->user_id);
        abort_unless($user,401);
        $data = $request->validate(['action'=>'required|in:balance,services,status','order'=>'required_if:action,status|integer|min:1']);
        $currency = basicControl()->base_currency;
        if ($data['action'] === 'balance') return response()->json(['balance'=>$user->balance,'currency'=>$currency]);
        if ($data['action'] === 'services') {
            abort_unless($panel->allows('catalogue'),403);
            return response()->json(PanelRentals::catalogue($panel)->map(fn($service)=>[
                'service'=>$service->id,'name'=>$service->service_title,'type'=>$service->service_type,
                'rate'=>PanelRentals::customerRate($service,$user),'currency'=>$currency,
                'min'=>$service->min_amount,'max'=>$service->max_amount,
            ])->values());
        }
        $order = Order::where('user_id',$user->id)->where('rental_panel_id',$panel->id)->findOrFail($data['order']);
        return response()->json(['order'=>$order->id,'status'=>$order->status,'charge'=>$order->price,'currency'=>$currency,
            'start_count'=>$order->start_counter,'remains'=>$order->remains]);
    }
}
