<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReferralCommissionController extends Controller
{
    public function index()
    {
        $data['referrals'] = Referral::get();
        return view('admin.referral_commission.index', $data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $rules = [
            'level' => 'required|array',
            'level.*' => 'required|string',
            'percent' => 'required|array',
            'percent.*' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with('error', 'provided the input data correctly.')->withInput();
        }

        try {
            Referral::where('commission_type', $request->commission_type)->delete();
            for ($i = 0; $i < count($request->level); $i++) {
                Referral::create([
                    'commission_type' => $request->commission_type,
                    'level' => $request->level[$i],
                    'percent' => $request->percent[$i]
                ]);
            }
            return back()->with('success', 'Level Bonus Has been Updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function configure(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'deposit_commission' => 'nullable|in:0,1'
        ]);

        basicControl()->update([
            'deposit_commission' => $request->deposit_commission
        ]);
        return back()->with('success', 'Deposit updated successfully.');

    }
}
