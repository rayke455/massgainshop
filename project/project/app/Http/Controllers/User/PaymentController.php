<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Traits\GetChildPanel;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Facades\App\Services\BasicService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Modules\ChildPanel\App\Models\ChildPanelGateway;


class PaymentController extends Controller
{

    use Upload, Notify, GetChildPanel;

    public function depositConfirm($trx_id)
    {
        try {
            $childPanel = $this->childPanel();
            $deposit = Deposit::with('user', 'depositable', 'gateway')->where(['trx_id' => $trx_id, 'status' => 0])->first();
            throw_if(!$deposit, 'Invalid Payment Request.');

            if ($deposit->child_panel_id && $childPanel) {
                $gateway = ChildPanelGateway::with('gateway')
                    ->where('child_panel_id', $deposit->child_panel_id)
                    ->where('gateway_id', $deposit->payment_method_id)->first();
            } else {
                $gateway = Gateway::findOrFail($deposit->payment_method_id);
            }

            throw_if(!$gateway, 'Invalid Payment Request.');

            if ($deposit->child_panel_id ? 999 < $gateway->gateway_id : 999 < $gateway->id) {
                if ($childPanel) {
                    return view(childPanelTemplate() . 'user.payment.manual', compact('deposit', 'gateway'));
                }
                return view(template() . 'user.payment.manual', compact('deposit'));
            }

            if ($deposit->child_panel_id && $childPanel) {
                $gatewayObj = 'App\\Services\\Gateway\\' . $gateway->gateway->code . '\\Payment';
            } else {
                $gatewayObj = 'App\\Services\\Gateway\\' . $gateway->code . '\\Payment';
            }

            $data = $gatewayObj::prepareData($deposit, $gateway);

            $data = json_decode($data);

            if (isset($data->error)) {
                return back()->with('error', $data->message);
            }

            if (isset($data->redirect)) {
                return redirect($data->redirect_url);
            }

            $page_title = 'Payment Confirm';
            if ($childPanel) {
                return view(childPanelTemplate() . $data->view, compact('data', 'page_title', 'deposit'));
            }
            return view(template() . $data->view, compact('data', 'page_title', 'deposit'));
        } catch (Exception $exception) {
            session()->flash('error', 'Something went wrong. Please try again.');
            return back()->with('error', $exception->getMessage());
        }
    }

    public function gatewayIpn(Request $request, $code, $trx = null, $type = null)
    {

        $childPanel = $this->childPanel();
        if (isset($request->m_orderid)) {
            $trx = $request->m_orderid;
        }

        if ($code == 'coinbasecommerce') {

            if ($childPanel) {
                $gateway = ChildPanelGateway::with('gateway')
                    ->where('child_panel_id', $childPanel->id)
                    ->whereHas('gateway', function ($query) use ($code) {
                        $query->where('code', $code);
                    })->first();
            } else {
                $gateway = Gateway::where('code', $code)->first();
            }

            $postdata = file_get_contents("php://input");
            $res = json_decode($postdata);

            if (isset($res->event)) {
                $deposit = Deposit::with('user')->where('trx_id', $res->event->data->metadata->trx)->orderBy('id', 'DESC')->first();
                $sentSign = $request->header('X-Cc-Webhook-Signature');
                $sig = hash_hmac('sha256', $postdata, $gateway->parameters->secret);

                if ($sentSign == $sig) {
                    if ($res->event->type == 'charge:confirmed' && $deposit->status == 0) {
                        BasicService::preparePaymentUpgradation($deposit);
                    }
                }
            }
            session()->flash('success', 'You request has been processing.');
            return redirect()->route('success');
        }

        try {
            if ($childPanel) {
                $gateway = ChildPanelGateway::with('gateway')
                    ->where('child_panel_id', $childPanel->id)
                    ->whereHas('gateway', function ($query) use ($code) {
                        $query->where('code', $code);
                    })->first();
            } else {
                $gateway = Gateway::where('code', $code)->first();
            }

            if (!$gateway) {
                throw new Exception('Invalid Payment Gateway.');
            }
            if (isset($trx)) {
                $deposit = Deposit::with('user')->where('trx_id', $trx)->first();
                if (!$deposit) throw new Exception('Invalid Payment Request.');
            }

            $gatewayObj = 'App\\Services\\Gateway\\' . $code . '\\Payment';
            $data = $gatewayObj::ipn($request, $gateway, $deposit ?? null, $trx ?? null, $type ?? null);

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
        if (isset($data['redirect'])) {
            return redirect($data['redirect'])->with($data['status'], $data['msg']);
        }
    }


    public function fromSubmit(Request $request, $trx_id)
    {
        $data = Deposit::where('trx_id', $trx_id)->orderBy('id', 'DESC')->with(['gateway', 'user'])->first();
        if (!$data || $data->status != 0) {
            return redirect()->back()->with('error', 'Invalid Request');
        }

        $params = optional($data->gateway)->parameters;
        $reqData = $request->except('_token', '_method');
        $rules = [];
        if ($params !== null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                if ($cus->type === 'file') {
                    $rules[$key][] = 'image';
                    $rules[$key][] = 'mimes:jpeg,jpg,png';
                    $rules[$key][] = 'max:2048';
                } elseif ($cus->type === 'text') {
                    $rules[$key][] = 'max:191';
                } elseif ($cus->type === 'number') {
                    $rules[$key][] = 'integer';
                } elseif ($cus->type === 'textarea') {
                    $rules[$key][] = 'min:3';
                    $rules[$key][] = 'max:300';
                }
            }
        }

        $validator = Validator::make($reqData, $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $reqField = [];
        if ($params != null) {
            foreach ($request->except('_token', '_method', 'type') as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k == $inKey) {
                        if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                            try {
                                $file = $this->fileUpload($request[$inKey], config('filelocation.deposit.path'), null, null, 'webp', 70);
                                $reqField[$inKey] = [
                                    'field_name' => $inVal->field_name,
                                    'field_value' => $file['path'],
                                    'field_driver' => $file['driver'],
                                    'validation' => $inVal->validation,
                                    'type' => $inVal->type,
                                ];
                            } catch (\Exception $exp) {
                                session()->flash('error', 'Could not upload your ' . $inKey);
                                return back()->withInput();
                            }
                        } else {
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'validation' => $inVal->validation,
                                'field_value' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
        }

        $data->update([
            'information' => $reqField,
            'created_at' => Carbon::now(),
            'status' => 2,
        ]);

        $msg = [
            'username' => optional($data->user)->username,
            'amount' => currencyPosition($data->payable_amount_in_base_currency),
            'gateway' => optional($data->gateway)->name
        ];

        $childPanel = $this->childPanel();
        $route = $childPanel
            ? route('child.panel.user.payment', $data->user_id)
            : route('admin.user.payment', $data->user_id);

        $action = [
            "name" => optional($data->user)->firstname . ' ' . optional($data->user)->lastname,
            "image" => getFile(optional($data->user)->image_driver, optional($data->user)->image),
            "link" => $route,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminPushNotification('PAYMENT_REQUEST', $msg, $action);
        $this->adminFirebasePushNotification('PAYMENT_REQUEST', $msg, $action);
        $this->adminMail('PAYMENT_REQUEST', $msg);

        session()->flash('success', 'You request has been taken.');

        if ($data->type == 0) {
            return redirect()->route('child.panel.user.add.fund');
        }
        return redirect()->route('user.fund.index');
    }

    public function success()
    {
        $childPanel = $this->childPanel();
        if ($childPanel) {
            return view('childpanel::success');
        }
        return view('success');
    }

    public function failed()
    {
        $childPanel = $this->childPanel();
        if ($childPanel) {
            return view('childpanel::failed');
        }
        return view('failed');
    }
}
