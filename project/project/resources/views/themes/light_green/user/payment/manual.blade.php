@extends(template().'layouts.user')
@section('title')
    {{ 'Pay with ' . optional($deposit->gateway)->name ?? 'Manual Transfer' }}
@endsection

@push('style')
    <style>
        img {
            width: 200px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper d-flex align-items-center mt-3">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-8 col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center">@lang("Please follow the instruction below")</h3>
                            <p class="text-center"> @lang("You have requested to deposit")
                                <b>{{ number_format($deposit->amount, 2) }} {{ $deposit->payment_method_currency }}</b>
                                , @lang("Please pay")
                                <b class="text--base">{{ getAmount($deposit->payable_amount) }} {{ $deposit->payment_method_currency }}</b>
                                @lang("for successful payment.")
                            </p>

                            <p class="mb-3">
                                @lang(optional($deposit->gateway)->note)
                            </p>

                            <form action="{{route('user.add.fund.from.submit',$deposit->trx_id)}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @if(optional($deposit->gateway)->parameters)
                                    @foreach($deposit->gateway->parameters as $k => $v)
                                        @if($v->type == "text")
                                            <div class="col-md-12 mb-3">
                                                <label for="{{ trans($v->field_label) }}"
                                                       class="form-label">{{ trans($v->field_label) }} <span
                                                        class="text-danger">
                                                            {{ $v->validation == 'required' ? '*' : '' }}
                                                        </span>
                                                </label>
                                                <input type="text" class="form-control" name="{{ $k }}"
                                                       id="{{ trans($v->field_label) }}" required>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "number")
                                            <div class="col-md-12">
                                                <label for="{{ trans($v->field_label) }}"
                                                       class="form-label">{{ trans($v->field_label) }} <span
                                                        class="text-danger">
                                                            {{ $v->validation == 'required' ? '*' : '' }}
                                                        </span>
                                                </label>
                                                <input type="text" class="form-control" name="{{ $k }}"
                                                       id="{{ trans($v->field_label) }}" required>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "date")
                                            <div class="col-md-12">
                                                <label for="{{ trans($v->field_label) }}"
                                                       class="form-label">{{ trans($v->field_label) }} <span
                                                        class="text-danger">
                                                            {{ $v->validation == 'required' ? '*' : '' }}
                                                        </span>
                                                </label>
                                                <input type="date" class="form-control" name="{{ $k }}"
                                                       id="{{ trans($v->field_label) }}" required>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "textarea")
                                            <div class="col-md-12">
                                                <label for="{{ trans($v->field_label) }}"
                                                       class="form-label">{{ trans($v->field_label) }} <span
                                                        class="text-danger">
                                                            {{ $v->validation == 'required' ? '*' : '' }}
                                                        </span>
                                                </label>
                                                <input type="text" class="form-control" name="{{ $k }}"
                                                       id="{{ trans($v->field_label) }}" required>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "file")

                                            <div class="col-md-12 mb-3">
                                                <label for="{{ trans($v->field_label) }}"
                                                       class="form-label">{{ trans($v->field_label) }} <span
                                                        class="text-danger">
                                                            {{ $v->validation == 'required' ? '*' : '' }}
                                                        </span>
                                                </label>

                                                <div class="d-flex gap-3 align-items-center">
                                                    <div class="image-area">
                                                        <img id="previewImage"
                                                             src="{{ getFile(config('filelocation.default')) }}"
                                                             alt="Choose Image">
                                                    </div>
                                                    <div class="btn-area">
                                                        <div class="btn-area-inner d-flex">
                                                            <div class="cmn-file-input">
                                                                <label for="formFile"
                                                                       class="form-label">@lang("Choose File")</label>
                                                                <input class="form-control" type="file" id="formFile"
                                                                       onchange="previewFile()" accept="image/*"
                                                                       name="{{$k}}">
                                                            </div>
                                                        </div>
                                                        <small>@lang("Allowed JPG, JPEG or PNG. Max size of 5MB")</small>
                                                    </div>
                                                </div>
                                                @error($k)
                                                <span class="text-danger">@lang($message)</span>
                                                @enderror
                                            </div>
                                        @endif
                                    @endforeach

                                @endif
                                <div class="col-md-12 ">
                                    <div class=" form-group">
                                        <button type="submit" class="btn cmn-btn w-100 mt-3">
                                            <span>@lang('Confirm Now')</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict'

        function previewFile() {
            const preview = document.getElementById('previewImage');
            const file = document.querySelector('input[type=file]').files[0];
            const reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    </script>
@endpush
