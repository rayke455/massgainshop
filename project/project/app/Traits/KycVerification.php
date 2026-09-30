<?php

namespace App\Traits;

use App\Models\Kyc;
use App\Models\UserKyc;
use Illuminate\Support\Facades\Validator;

trait KycVerification
{
    use Upload;

    public function getKycVerification()
    {
        return Kyc::orderBy('id', 'asc')
            ->where('status', 1)
            ->get()->map(function ($item) {
                $lastSubmissionStatus = UserKyc::where('kyc_id', $item->id)
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($lastSubmissionStatus) {
                    $item->last_submission_status = $lastSubmissionStatus->status;
                } else {
                    $item->last_submission_status = 'null';
                }
                return $item;
            });
    }

    public function getKycForm($id)
    {
        $data['kyc'] = Kyc::findOrFail($id);
        $data['userKyc'] = UserKyc::where('user_id', auth()->id())->where('kyc_id', $id)->where('status', '!=', 2)->first();
        return $data;
    }

    public function kycFormSubmit($request, $redirectRoute)
    {
        $kyc = Kyc::where('id', $request->type)->where('status', 1)->firstOrFail();

        $params = $kyc->input_form;
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


        $params = $kyc->input_form;
        $validator = Validator::make($reqData, $rules);
        if ($validator->fails()) {
            $validator->errors()->add('kyc', 'Your unique error message for the kyc field');
            return back()->withErrors($validator)->withInput();
        }

        $reqField = [];
        foreach ($request->except('_token', '_method', 'type') as $k => $v) {
            foreach ($params as $inKey => $inVal) {
                if ($k == $inKey) {
                    if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                        try {
                            $file = $this->fileUpload($request[$inKey], config('filelocation.kyc.path'), null, null,'webp', 90);
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'field_label' => $inVal->field_label,
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
                            'field_label' => $inVal->field_label,
                            'validation' => $inVal->validation,
                            'field_value' => $v,
                            'type' => $inVal->type,
                        ];
                    }
                }
            }
        }

        UserKyc::create([
            'user_id' => auth()->id(),
            'kyc_id' => $kyc->id,
            'kyc_type' => $kyc->name,
            'kyc_info' => $reqField
        ]);

        return redirect()->route($redirectRoute)->with('success', 'KYC Sent Successfully.');

    }


    public function searchUserKyc($request)
    {
        $search = $request->all();
        $data['userKyc'] = UserKyc::with(['user', 'kyc'])->where('user_id', auth()->id())
            ->when(!empty($search['kyc_type']), function ($query) use ($search) {
                return $query->where('kyc_type', 'like', '%' . $search['kyc_type'] . '%');
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereBetween('created_at', [$search['from_date'], $search['to_date']]);
            })
            ->when(isset($search['from_date']) && !isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', isset($search['from_date']));
            })
            ->when(!isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', $search['to_date']);
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return $data;


    }
}
