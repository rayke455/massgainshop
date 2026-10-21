# Rental-platform implementation ledger

## Evidence and scope

Reference: https://socpanel.com/ and the user's signed-in control/settings pages.
Observed menus include general settings, providers, payments, languages, support,
integrations, notifications, promos, pages, SEO and API. General settings expose
registration, services, fast orders, discounts, referrals and domains.
Menu presence alone does not establish backend behaviour.

REA 6.7.0 successfully runs through the official `rea-agents` package.
Its `analyze-web-bundle` command requires a loopback CDP endpoint and target ID.
The in-app browser does not expose that connection here. No REA capture of the
authenticated SocPanel application has been performed. No private code recovered.

## Implemented in this local iteration

- Tenant-only dark control navigation with feature-aware links.
- Existing settings, payouts, pricing, domains and guest requests grouped into
  navigable sections; hash links retain browser navigation and pagination.
- Registered-customer order listing, status filter and independent pagination,
  scoped by rental panel ID.
- Searchable, paginated tenant customers; grouped search clauses cannot bypass
  panel scoping.
- Rental onboarding saves validated preview tagline/colour when branding is enabled.
- Explicit DNS ownership versus hosting/certificate distinction.

## Remaining work (not claimed complete)

- Observe authenticated reference workflows with a supported REA browser connection.
- Full customer/order lifecycle controls with audited authorization.
- Tenant promotions, referrals and arbitrary content pages (basic SEO, support,
  announcements and terms/privacy are implemented below).
- Tenant API credentials, permissions, isolation and rate limits.
- Provisioning and verified HTTPS readiness for custom domains.
- Rental activation/trial/billing lifecycle and reconciled commission rules.
- Automated Tanzania payments/payouts (existing payout flow is manual there).
- Mobile/desktop browser verification, end-to-end payment tests and deployment.

Keep platform pricing configurable. Do not copy reference fees into production,
create provider accounts, charge live payments or replace customer data as part of
UI research. Preserve platform-super-admin control of tenant capabilities.

## 20 October: customer workspace and owner operations

Implemented:

- Shared `rentals.customer-workspace` view for the signed-in customer dashboard
  and public customer preview; owner controls no longer appear as customer actions.
- Preview navigation opens labelled sample orders, services, funds and support screens.
  These samples do not place orders or send payments.
- Charcoal surfaces, blue active controls, green balance controls, purple order cards,
  monochrome SVG icons and responsive navigation.
- Customer currency conversion uses the selected currency and its conversion rate.
- Tenant support conversations with customer/owner authorization, panel scoping,
  replies, close/reopen status and audit entries. Platform support remains separate.
- Owner settings for registration, order/spend visibility, announcements, SEO and
  plain-text terms/privacy pages. Closed registration rejects direct POST requests.
- Owners can suspend/restore customers belonging to their panel; suspended social
  login accounts cannot bypass the tenant host guard.
- Tenant customer help links repaired; existing light-green account layouts receive
  tenant-scoped dark styling.
- Additive migration `2026_10_20_000001_add_rental_workspace.php` applied locally.

Verification: initial focused run passed 34 tests / 88 assertions; subsequent changes
add coverage for suspension, selected currency, escaped public pages and rendered
support conversations. See final execution output for the latest result.

Still not equivalent to the full reference product: order discounts,
affiliate commissions, tenant API order creation,
leaderboard, provider management, email/SMS notification
configuration, and automated Tanzania payouts. Existing provider integration and
main-site features do not imply these tenant-specific workflows are implemented.
Custom-domain HTTPS/DNS and real payment delivery still require live verification.

## 21 October: tenant registration questions

- Owners can configure up to five labelled text questions with required/optional
  validation, gated by the platform's accounts feature.
- Tenant registration saves answers on the customer with a snapshot of each
  question label. Owners see these in their panel's customer list; generic user
  serialization excludes the answers.
- Unexpected question keys, arrays and oversized responses are rejected, and
  labels/answers are escaped when rendered.
- Tenant signup uses tenant policy links and does not advertise unsupported social
  sign-in. Main-site registration remains separate.
- Additive migration `2026_10_21_000001_add_rental_registration_answers.php`
  applied locally. No live deployment performed.

Verification: 40 tests passed / 100 assertions across RentalRegistrationTest,
RentalWorkspaceTest, PanelRentalsTest, SignupPhoneTest and SignupCurrencyTest.
Includes persisted customer answers and owner settings, panel isolation, support,
suspension, currency and phone regression tests. Blade template cache compiled
successfully. Browser visual verification of these new fields is still pending.

## 21 October: content, balance promotions, API isolation and owner preview

- Inspected the signed-in reference's Extra pages and Promo codes screens.
  Reference pages offer custom HTML and external links; our implementation
  intentionally uses escaped plain text, not arbitrary executable HTML.
- Tenant owners can create, edit, publish and unpublish pages, external HTTP(S)
  links, FAQ answers and in-app announcements. Content is scoped by panel, audited,
  capped at 100 entries and ordered. Both tenant hosts and public storefront paths
  serve published pages; drafts stay private.
- Balance promo codes transfer existing funds from the tenant owner's platform
  wallet to the customer's wallet. Includes two ledger entries, expiry, activation
  caps, disable/enable, one use per customer and transaction/row-lock protection.
  Order-discount promo codes are not implemented.
- Separate tenant read-only API supports balance, catalogue and own order status.
  Keys are hashed, rotate/revoke, expire in 90 days and are scoped to the tenant
  host/customer. Main-site legacy API rejects tenant tokens. API order creation
  and refill remain unimplemented.
- Additive migrations 000002, 000003 and 000004 applied locally. No live payments,
  external account changes or deployment performed.
- Owner preview now renders actual owner templates with sample data and disabled
  form controls; content and promotion preview screens are linked separately.
- Browser QA exposed a Blade compilation error caused by mixing inline @php
  directives with multiline blocks. Converted affected owner/signup directives
  to explicit blocks and added RentalOwnerPreviewTest for real route rendering.

Verification before the preview fix: 50 focused tests / 118 assertions passed.
Content and promotion preview rendering passed; control preview initially failed.
Post-fix RentalOwnerPreviewTest passed: 2 tests / 7 assertions, including actual
control, content and promotion route rendering. Do not treat compilation alone
as rendered-page verification or claim complete SocPanel parity.

Follow-up regression: 60 focused tests / 142 assertions passed, including signup
phone/currency, tenant ownership, content, promotions, API and owner-preview routes.
Owner preview links now target preview screens rather than tenant ID 0; support
also has a read-only owner preview. No live deployment performed.
