<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\KycVerification;
use Illuminate\Http\Request;

class KycVerificationController extends Controller
{
    use KycVerification;

    public function index()
    {
        $kycVerification = $this->getKycVerification();
        return view(template() . 'user.verification_center.index', compact('kycVerification'));
    }

    public function kycForm($id)
    {
        $data = $this->getKycForm($id);
        return view(template() . 'user.verification_center.kyc_form', $data);
    }

    public function verificationSubmit(Request $request)
    {
        return $this->kycFormSubmit($request, 'user.kyc.verification');
    }

    public function history(Request $request)
    {
        $data = $this->searchUserKyc($request);
        return view(template() . 'user.verification_center.history', $data);
    }
}
