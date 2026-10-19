# Guest checkout and tenant payouts

## Guest ordering

The main site's **Order without account** action opens `/order-without-account`. Standard quantity-based services are supported. Customers enter a link, quantity, email and Kenyan M-Pesa number, review the server-validated KES total, and authorize the prompt on their own phone. No login or user account is created.

Private UUID links show status without contact details. Save/bookmark the link; email delivery is not implemented. Payment status is verified against Safaricom before creating an order. Duplicate submits reuse the original checkout. Confirmed payments create one standard order with no user wallet. Supplier requests are sent once; unclear outcomes stay in review rather than automatically retrying against a backup supplier.

Super admin → **Guest orders** lists contact details, payment references and linked orders. Review supplier responses before manually processing uncertain orders. Refunds are manual to the original payment method; no automatic wallet refund is created for guests. Live payment and supplier acceptance have not been exercised by the automated tests. A public HTTPS callback URL and valid gateway configuration are required for reliable live operation. The scheduled provider-status updater must run for live delivery updates.

Guest checkout currently supports Kenya M-Pesa only. Tanzania collection for signed-in customers already uses the separate Snippe integration; Tanzania guest checkout is not connected.

## Tenant payout destinations

Manage panel → Earnings lets owners save a country, mobile/Paybill/Till destination and Paybill account reference. Destination validation checks format, not account ownership. Admin must verify recipients before payment. Withdrawals snapshot the destination and country currency rate, reserve earnings, and require super-admin approval. Amounts are rounded down to whole local shillings. The platform must account for provider payout fees separately.

Kenya uses the existing Safaricom B2C/B2B connection. Tanzania destinations and TZS payout requests are supported for **manual processing only** until an approved Tanzania payout adapter is connected. Never send Tanzania destinations to Safaricom Kenya.

Payouts have locked state transitions and per-attempt callback secrets. Confirmed failures refund earnings once. Uncertain submissions and receipt mismatches remain reserved in `review`; do not resend them. Old `failed` rows are deliberately blocked because the previous implementation could already have returned their earnings. They require reconciliation before any further disbursement. Production callback access logs must redact query tokens, and Safaricom callback ingress restrictions should also be configured at the hosting layer.
