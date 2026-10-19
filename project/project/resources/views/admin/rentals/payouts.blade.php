@extends('admin.layouts.app')
@section('page_title', 'Reseller payouts')
@section('content')
<div class="content container-fluid">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-2">
        <div><h1 class="page-header-title">Reseller payouts</h1>
            <p class="mb-0 text-muted">Panel owners withdraw their earnings here. {{ $auto ? 'Approved payouts are sent automatically by M-Pesa B2C/B2B.' : 'Automatic M-Pesa payouts are off (add the initiator name and security credential in Panel rentals). Pay manually, then mark as paid.' }}</p></div>
        <a class="btn btn-white" href="{{ route('admin.rentals.index') }}">← Panel rentals</a>
    </div>
    @if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    <div class="card">
        <div class="table-responsive">
            <table class="table table-align-middle mb-0">
                <thead class="thead-light"><tr><th>#</th><th>Reseller</th><th>Amount</th><th>Send to</th><th>Status</th><th style="min-width:320px"></th></tr></thead>
                <tbody>
                @forelse($payouts as $p)
                    <tr>
                        <td>{{ $p->id }}<br><small class="text-muted">{{ $p->created_at->format('d M H:i') }}</small></td>
                        <td>{{ optional($p->user)->username }}</td>
                        <td><strong>{{ $p->payout_currency }} {{ number_format($p->payout_amount ?? $p->amount_kes) }}</strong><br><small class="text-muted">{{ number_format($p->amount, 2) }} {{ basicControl()->base_currency }} · {{ $p->country }}</small></td>
                        <td>{{ ['b2c' => 'M-Pesa phone', 'paybill' => 'Paybill', 'till' => 'Till'][$p->method] }}<br><code>{{ $p->account }}</code>{{ $p->account_ref ? ' acc ' . $p->account_ref : '' }}</td>
                        <td><span class="badge {{ ['paid' => 'bg-soft-success text-success', 'pending' => 'bg-soft-warning text-warning', 'processing' => 'bg-soft-info text-info', 'rejected' => 'bg-soft-secondary text-secondary', 'failed' => 'bg-soft-danger text-danger'][$p->status] ?? '' }}">{{ ucfirst($p->status) }}</span>
                            @if($p->note)<br><small class="text-muted">{{ $p->note }}</small>@endif @if($p->mpesa_ref)<br><small>{{ $p->mpesa_ref }}</small>@endif</td>
                        <td class="text-end">
                            @if(in_array($p->status, ['pending', 'retryable']))
                                @if($auto && $p->country === 'KE')
                                    <form class="d-inline" method="post" action="{{ route('admin.rentals.payout.action', $p->id) }}" onsubmit="return confirm('Send KES {{ number_format($p->amount_kes) }} by M-Pesa now?')">@csrf<input type="hidden" name="action" value="send"><button class="btn btn-success btn-sm">Send by M-Pesa</button></form>
                                @endif
                                <form class="d-inline-flex gap-1" method="post" action="{{ route('admin.rentals.payout.action', $p->id) }}">@csrf<input type="hidden" name="action" value="paid">
                                    <input class="form-control form-control-sm" style="width:130px" name="mpesa_ref" placeholder="Payment receipt" required><button class="btn btn-white btn-sm">Mark paid</button></form>
                                <form class="d-inline" method="post" action="{{ route('admin.rentals.payout.action', $p->id) }}" onsubmit="return confirm('Reject and return the money to the reseller\'s earnings?')">@csrf<input type="hidden" name="action" value="reject"><button class="btn btn-white btn-sm text-danger">Reject</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No payout requests yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($payouts->hasPages())<div class="card-footer">{{ $payouts->links() }}</div>@endif
    </div>
</div>
@endsection
