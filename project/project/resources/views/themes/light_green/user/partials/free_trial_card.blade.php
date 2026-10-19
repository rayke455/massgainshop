{{-- Free trial: one free order for new customers (set up in Admin → Growth → Settings). --}}
@php
    $zkTrialOn = \App\Services\Growth::get('trial.enabled');
    $zkTrialService = $zkTrialOn ? \App\Models\Service::where('service_status', 1)->find(\App\Services\Growth::get('trial.service_id')) : null;
    $zkTrialUsed = $zkTrialService ? \Illuminate\Support\Facades\DB::table('free_trials')->where('user_id', auth()->id())->exists() : true;
@endphp
@if($zkTrialService && !$zkTrialUsed)
    @php $zkQty = max((int) $zkTrialService->min_amount, (int) \App\Services\Growth::get('trial.quantity', 100)); @endphp
    <div class="zk-trial mb-4">
        <div class="zk-trial__icon"><i class="fa-solid fa-gift"></i></div>
        <div class="zk-trial__body">
            <strong>@lang('Try us for free:') {{ number_format($zkQty) }} × {{ \Illuminate\Support\Str::limit($zkTrialService->service_title, 60) }}</strong>
            <span>@lang('Paste the link and we deliver it free, once per customer.')</span>
            <form method="post" action="{{ route('user.free.trial') }}" class="zk-trial__form">
                @csrf
                <label for="zkTrialLink" class="visually-hidden">@lang('Link')</label>
                <input type="url" class="form-control" id="zkTrialLink" name="link" required maxlength="255" placeholder="https://instagram.com/p/...">
                <button class="zk-btn zk-btn--primary" type="submit">@lang('Claim free trial')</button>
            </form>
        </div>
    </div>
@endif
