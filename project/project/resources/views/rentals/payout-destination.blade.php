@php
    $destination = ($ownerPreview ?? false) ? null : \Illuminate\Support\Facades\DB::table('rental_payout_destinations')->where('user_id', auth()->id())->first();
    $payoutToken = session('rental_payout_token') ?: (string) \Illuminate\Support\Str::uuid();
    session(['rental_payout_token' => $payoutToken]);
@endphp
<div>
    <h3>Where you receive your earnings</h3>
    <p class="muted">Customer payments are collected by the platform. Your available profit can be paid to the destination below, after admin approval. Customer wallet deposits are not withdrawable tenant earnings.</p>
    <form method="post" action="{{ route('user.panels.destination') }}" id="payoutDestination">@csrf
        <label>Country<select name="country" id="payoutCountry">
            <option value="KE" @selected(old('country', $destination->country ?? 'KE') === 'KE')>Kenya · KES</option>
            <option value="TZ" @selected(old('country', $destination->country ?? '') === 'TZ')>Tanzania · TZS</option>
        </select></label>
        <label>Receive money through<select name="method" id="pmethod">
            <option value="b2c" @selected(old('method', $destination->method ?? 'b2c') === 'b2c')>M-Pesa mobile number</option>
            <option value="paybill" @selected(old('method', $destination->method ?? '') === 'paybill')>Paybill</option>
            <option value="till" @selected(old('method', $destination->method ?? '') === 'till')>Till / Buy Goods</option>
        </select></label>
        <label><span id="payoutAccountLabel">Destination number</span><input name="account" id="payoutAccount" value="{{ old('account', $destination->account ?? '') }}" inputmode="tel" maxlength="30" required></label>
        <label id="refWrap">Paybill account reference<input name="account_ref" value="{{ old('account_ref', $destination->account_ref ?? '') }}" maxlength="13"></label>
        <p class="notice" id="tzPayoutNotice">Tanzania payouts are manually processed by the platform owner until a Tanzania payout provider is connected. Merchant numbers must be verified before payment.</p>
        <button>Save payout destination</button>
    </form>
    @if($destination)
        <form method="post" action="{{ route('user.panels.withdraw') }}">@csrf
            <input type="hidden" name="payout_token" value="{{ $payoutToken }}">
            <h3>Request withdrawal</h3>
            <p>Saved destination: <strong>{{ $destination->country }} · {{ $destination->account }}</strong>{{ $destination->account_ref ? ' / '.$destination->account_ref : '' }}. Save any destination changes before requesting.</p>
            <label>Amount ({{ $base }})<input type="number" name="amount" min="{{ max(0.01, $settings['min_payout']) }}" step="0.01" max="{{ $earnings }}" required></label>
            <small>Converted to {{ $destination->country === 'TZ' ? 'TZS' : 'KES' }} at the configured rate when requested, rounded down to whole shillings. Minimum {{ $settings['min_payout'] }} {{ $base }}.</small>
            <button @disabled($earnings < max(0.01, (float)$settings['min_payout']))>Request payout</button>
        </form>
    @endif
</div>
<script>
(() => {
    const form = document.getElementById('payoutDestination'), country = document.getElementById('payoutCountry'), method = document.getElementById('pmethod');
    const update = () => {
        const paybill = method.value === 'paybill', phone = method.value === 'b2c';
        document.getElementById('refWrap').hidden = !paybill;
        form.elements.account_ref.required = paybill;
        document.getElementById('tzPayoutNotice').hidden = country.value !== 'TZ';
        document.getElementById('payoutAccountLabel').textContent = phone ? 'M-Pesa mobile number' : (paybill ? 'Paybill number' : 'Till / merchant number');
        document.getElementById('payoutAccount').placeholder = phone ? (country.value === 'TZ' ? '+255 7XX XXX XXX' : '+254 7XX XXX XXX') : 'Merchant number';
    };
    country.addEventListener('change', update); method.addEventListener('change', update); update();
})();
</script>
