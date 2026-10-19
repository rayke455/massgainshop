# Panel rentals

The rental module is independent of the missing commercial ChildPanel add-on.

## Administration

- Open **Admin → Panel rentals**. Only the admin identified by `RENTAL_SUPER_ADMIN_ID` (default: existing admin ID 1) can manage it.
- Set the rental price in the site's base currency, paid duration in days, subdomain base, and default feature permissions. Enable rentals when ready.
- Existing tenants have independent feature switches and active/suspended/terminated states. Termination blocks access without deleting records. Reactivation does not extend an expired paid period.
- Rental purchases and renewals debit the tenant owner's main wallet. Renewals are manual and have duplicate-submission protection. There is no automatic recurring charge.
- Price, duration, domain base, and default-feature changes apply to future purchases/renewals as shown on the confirmation form. Existing subdomains do not silently move.
- **Customer payments & payouts** (same page): platform fee (% of each sale's profit kept by the platform), minimum payout, and the M-Pesa B2C/B2B initiator name + security credential for automatic payouts.
- **Admin → Panel rentals → Reseller payouts**: approve ("Send by M-Pesa" when B2C/B2B credentials are set), "Mark paid" after paying manually, or reject (money returns to the reseller's earnings).
- Changes are logged in `rental_audit` (the B2C security credential is never logged).

## Tenant operation

- Customers find **Rent a panel** in the main client sidebar, then manage their panels at `/user/panels`.
- **Live preview**: the rent page and the manage page show the storefront exactly as customers will see it, updating as the name, tagline, colour, markup or currency change. Ordering is disabled in previews.
- **Branding**: panel name, tagline, logo upload (PNG/JPG/WebP ≤ 2 MB, stored in `assets/upload/rentals`), brand colour, WhatsApp number and customer currency.
- **Own prices**: a default markup plus an optional price per service (per 1,000, in the panel currency). Prices below the owner's cost are raised to the cost. Services can be hidden.
- The platform's existing service catalogue is the provider. The storefront supports quantity-based Default services.

### Payment modes (per panel)

1. **M-Pesa through the platform Paybill (default).** The customer picks a service, enters an M-Pesa number and receives an STK prompt from the platform's Paybill/Till (C2B, using the M-Pesa gateway settings). After the payment is confirmed (status query; the Daraja callback is verified the same way), the order is placed automatically on the owner's account at the owner's cost, and the profit (customer payment − cost − platform fee) is added to the owner's **earnings wallet**. If no supplier accepts the order, the full payment goes to earnings and the request is marked so the owner can refund or redo it.
2. **Request and confirm (manual).** The original workflow: the owner confirms payment with the customer, buys the matching service through their platform account and links the order ID.

### Earnings

- Earnings are separate from the main wallet. Owners can **withdraw** to an M-Pesa number (B2C) or their Paybill/Till (B2B), or **move** earnings to the main wallet to buy services.
- Payouts are approved by the platform owner. Failed or rejected payouts return to earnings automatically. B2C/B2B results arrive at `/rentals/payout-result` (must be public https).

## Hosting and domains

1. Panels get `slug.<base>` automatically when rented. If the base domain setting is empty, the main site's domain is used (never an IP or localhost). Saving the rental settings also assigns a subdomain to any panel that has none.
2. One-time setup: wildcard DNS `*.<base>` to this server (e.g. Cloudflare `A *` record, proxied, SSL mode Full) and a wildcard server alias / cPanel `*` subdomain pointing to this site. The app does not modify DNS or issue TLS certificates.
3. Custom domains are assigned by the tenant and must have a matching `_zirka-panel` TXT verification record and a CNAME to the panel's subdomain. A verified domain must also route to this app with working HTTPS.
4. Only active, unexpired panels can serve requests. Custom-domain access also requires the feature and successful ownership verification. Tenant hosts cannot use main admin/account routes.
5. Previews work at `/panels/{slug}` without DNS. These are public storefronts subject to the same panel/feature checks.
6. `/set-app-url` (which rewrites `APP_URL`) is restricted to logged-in admins and is no longer triggered automatically by 404s on other hosts, so tenant domains cannot change the site address.

## Currency

Registration uses the visitor's country lookup to select an enabled local currency (TZ → TZS). An explicit visitor currency choice wins; an older automatic choice does not override a newly detected location. The phone's country is a fallback if location is unavailable. Saved account currency can be changed from the dashboard and is not overwritten by automatic detection.

The two client dashboard statistics charts have been removed. Balance and transaction history remain available.
