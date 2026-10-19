@extends('admin.layouts.app')
@section('page_title','Guest orders')
@section('content')
<div class="content container-fluid"><h1>Guest orders</h1><p>Paid orders appear in the main Orders list. Reconcile any review status with M-Pesa and the supplier before resending or refunding. Guest refunds must be returned to the original payment method, not an account wallet.</p>
<div class="card table-responsive"><table class="table"><thead><tr><th>Reference</th><th>Contact</th><th>Service</th><th>Payment</th><th>Status</th></tr></thead><tbody>
@foreach($orders as $g)<tr><td>G{{ $g->id }}<br>{{ $g->created_at }}</td><td>{{ $g->email }}<br>{{ $g->phone }}</td><td>{{ optional($g->service)->service_title }}<br>{{ $g->quantity }} · {{ $g->link }}</td><td>KES {{ $g->amount_kes }}<br>{{ $g->payment_ref }}</td><td>{{ $g->status }}<br>{{ $g->order_id ? 'Order #'.$g->order_id.' · '.optional($g->order)->status : '' }}</td></tr>@endforeach
</tbody></table></div>{{ $orders->links() }}</div>
@endsection
