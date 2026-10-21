# SocPanel parity audit — 2026-10-21

Scope: read-only inspection of the signed-in tenant-owner interface for panel 305500, public product information, and local Laravel code. No reference-account changes were saved, no services imported, and no payments initiated. This is not a completed whole-product audit.

## Billing

- Observed owner balance and a warning that a negative balance will cause the panel to freeze soon. Add funds opens a payment-method chooser.
- Public https://socpanel.com/ advertises a $10 first deposit/activation and 5% commission on completed orders, described as a percentage of income. The wording does not establish whether the exact calculation uses revenue or net margin. Do not encode a guessed basis.
- Exact grace duration, ledger settlement timing, refunds, reactivation and platform-super-admin controls have NOT been verified.
- Local `PanelRentals::settings()` exposes `platform_fee`. Existing earning calculations retain a percentage of positive tenant margin. Rental/renewal charges debit the owner's main balance. `RentalPanel::available()` checks active status and expiry only.
- Missing for requested model: separate panel billing balance and ledger; completion-triggered idempotent fees; explicit fee basis and rate snapshot; grace start/deadline; balance warnings; restricted operation after grace; top-up/reactivation; refund/reversal rules. Keep customer deposits, tenant withdrawable earnings and platform billing credit separate to avoid double charging.
- Administrator should configure activation, rate/basis and grace period, as requested. Existing financial records must be preserved; no retrospective charging without an explicit policy.

## Service management

Observed `/services`: Add service, Import services, Direct providers, search, categories and Add category. Rows expose provider/service mapping.

Observed `/services/create`:
- Digital goods or SMM service.
- SMM source: Provider, Manual, BotsAPI.
- Provider mode: provider selection and upstream service ID.
- Manual mode: icon, name, type, category, description, user-friendly description, performer cost, minimum/maximum quantity, additional options and fixed price.

Observed `/services/import`: provider selector, search, selectable service list, already-added indicators, selected-services step and Continue. Import was not submitted; later confirmation/pricing stages remain unverified.

Observed `/common/services`: automatic failed-order cancellation, drip feed, average completion time, last order links, API page, multiple order page, automatic packages, per-1000/per-100/per-1 display and popular-service mark.

Local tenant UI currently offers catalogue pricing/hiding and default markup, not this full create/import workflow. Preserve Mass Panel Shop as default provider and super-admin feature permissions when implementing it.

## Customization

Observed control menu: general settings, providers, payment methods, languages, support/FAQ, integrations, notifications, promos, extra pages, SEO/blog, API and profile.

Observed general settings: custom registration fields, service settings, fast ordering, discounts, referrals, top users, metadata, domain/DNS, order-count and user-spending visibility.

Public product page also advertises themes, colours, text/HTML editing and ten landing pages. These advertised capabilities are not evidence that the local theme editor is implemented or that every reference editor has been inspected.

Local foundations: branding, markup, domain verification, registration fields, pages/FAQ/notices, wallet promos and read-only API. Full theme/landing editor, discounts/referrals and full service management remain gaps.

## Customer and platform access limits

- Tenant public storefront inspected at https://dukalalikes.com/ with guest ordering, login and registration links.
- Customer access was subsequently supplied by the user. Authenticated customer workflows inspected on 2026-10-21; findings below. Owner and customer sessions remain distinct.
- SocPanel's internal platform-super-admin interface is not accessible from this tenant account. Implement Mass Panel Shop's super-admin requirements independently; do not claim they were reverse engineered.

## Implementation order

1. Finalize configurable billing basis and grace policy, then ledger/settlement tests (duplicate callbacks, refunds, tenant isolation, reactivation).
2. Tenant create/import service workflow and tenant-local metadata/price overrides, default platform provider and permission gates.
3. Theme/customization workflow with safe previews and publication boundaries.
4. Inspect authenticated customer workflows and align their UI/functionality.
5. End-to-end tests for platform owner, tenant and customer. UI screenshots alone do not demonstrate billing or functional parity.

## Authenticated customer inspection — follow-up

Verified using the customer tab supplied by the user. No order, payment, message, password change or token rotation was submitted.

- `/order`: branded header with balance/add-funds link, rounded search bar, vertically stacked platform rows with real icons, separators and chevrons. Fixed three-item navigation: My Orders / Create / Menu. No summary-card dashboard on this screen.
- Order steps: platform selection → category → service list with per-1000 pricing and Hot badge → service order form. Inspected Instagram Likes.
- Form: preset quantities (100, 500, 1000, 2500, 5000), custom quantity option, target link, selected amount and calculated price, service ID, speed, cancel-availability indicator, description and Favorite control. Default sample quantity 100 displayed a price of 5.1 Ksh. Submission and insufficient-balance behavior NOT tested.
- `/orders/all`: search plus All / In Progress / Pending / Cancelled / Partial / Completed filters. The supplied account has no orders, so detail, cancel and refill workflows cannot be verified from its history.
- `/pay`: payment-method chooser, Safaricom-Mpesa. Selecting it shows amount (initial value 50 KES), credited amount and a 5% bonus offer around 200 Ksh. Exact minimum, threshold inclusivity, settlement and callbacks are unverified; initial input value is not proof of a minimum. No Add funds submission was made.
- `/menu`: API, support link (custom label), referrals, notifications, profile, additional custom links and legal pages.
- `/profile/settings`: account identity, password/session revocation controls, email/confirmation, masked API token and rotation control, sign out, Google Authenticator attachment. No credentials were changed or copied.
- `/profile/balance`: search and All / Payments / Orders filters; supplied account has no ledger entries.
- `/profile/referrals`: tenant-configured 10% of invited users' payments credited to customer panel balance, explicitly non-withdrawable; invite link and invited/current-month/total-income statistics. This is distinct from the platform's commission on tenant business.
- Custom warning/trending/WhatsApp banners belong to this tenant's customization; they are not proof of mandatory SocPanel defaults. Do not hard-code the reference tenant's brand, contact data or promotional claims into Mass Panel Shop.

### Confirmed local presentation gap

`resources/views/rentals/customer-workspace.blade.php` currently presents summary cards, recent orders and a four-item Home / Orders / Services / Support footer. Non-dashboard preview screens contain placeholder/disabled forms. Live links route into existing user order/payment handlers. Therefore shared visual styling does NOT establish end-to-end customer parity. Implement and test the actual staged catalogue, service form, distinct Menu, history and wallet interfaces rather than marking these placeholders complete.
