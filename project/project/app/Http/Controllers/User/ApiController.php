<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function index()
    {
        $data['api_token'] = Auth::user()->api_token;
        if (Auth::check()) {
            return view(template() . 'user.api.index', $data);
        }
        return redirect()->route('apiDocs');
    }

    public function apiGenerate()
    {
        $user = Auth::user();
        $user->api_token = Str::random(20);
        $user->save();

        return back()->with([
            'success' => "Api Token Generated Successfully.",
        ]);
    }

    public function apiKeyView(Request $request)
    {
        $request->validate([
            'password' => 'required|string|max:191',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password. Please try again.');
        }
        session()->put('type', 'text');
        return back()->with([
            'success' => "You have successfully entered your password.",
        ]);
    }
}
