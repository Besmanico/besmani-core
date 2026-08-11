# Referral module history

## Existing implementation reviewed on 2026-08-05

The referral feature has a dedicated `referrals` table containing the sender, destination business, registered customer snapshot, optional service, reward, workflow timestamps, and status. Status changes are recorded in `referral_status_histories`; completed rewards are written to `token_ledger`; `referral_partners` is available for aggregate partner statistics.

Access is controlled through `ReferralPolicy` and `ReferralAccessService`. Workflow transitions (`pending` to `accepted`, `completed`, or `cancelled`) run through `ReferralWorkflowService` inside database transactions. Completion creates the configured Besmani COIN ledger entry. 

The Livewire referral page supports personal/business senders, destination business search, registered-customer lookup by phone, outgoing/incoming lists, action confirmation, details, and the BC balance. Before this change, its Service select only displayed General Referral, the backend rejected every non-null `serviceId`, and every referral stored a null `service_id`.

## Destination service implementation 
  
Services belong to the owner of the selected `InfoActivity` business. A user can own multiple businesses, while the legacy service tables associate services with `user_id`, not an `info_activity.id`. The selected business's `activity_id` therefore determines which of that user's service tables is used. This prevents (for example) a user's women-salon services from appearing when their clinic business is selected.

The service storage patterns are heterogeneous:
 
- `ClinicService` is an owned service row and points to the service definition through `clinic()`.
- `WomenServiceSalon` and `MenSalonService` are assignment rows and point to their definitions through `service()`.
- `WomenAcademyCourse` and `MenAcademyCourse` follow the same assignment/definition pattern through `service()`.

`ReferralServiceCatalog` maps the established activity codes (`clinic_beauty`, `women_salon`, `man_salon`, `woman_learn`, and `man_learn`) to the appropriate model, then normalizes its rows into `{key, type, id, title}` options. Clinic rows are restricted to `active = 1`. The selected composite key is re-resolved on submission for the destination business and its owner, preventing a client from submitting another user's service or a service from another one of that user's business types.

The first select option is always General Referral, regardless of whether mapped services exist. If no mapped services exist, General Referral remains usable and an explanatory hint is shown.

Because IDs overlap between the legacy service tables, the `referrals` table now stores:

- `service_id`: the selected owned/assignment row ID;
- `service_type`: its normalized source (`clinic`, `women_salon`, `men_salon`, `women_academy`, or `men_academy`);
- `service_title`: a snapshot used for reliable historical display even if the external catalog title later changes.

Migration: `2026_08_05_120000_add_service_source_to_referrals_table.php`.

## Follow-up rule

Future referral analysis and implementation decisions should be appended to this Markdown file so work history remains available independently of chat history.

## Referral API (2026-08-05)

The authenticated Sanctum API now exposes bootstrap/list data, destination search, destination services, registered-customer lookup, referral creation and details, and workflow actions. The endpoints reuse `ReferralAccessService`, `ReferralPolicy`, `ReferralServiceCatalog`, and `ReferralWorkflowService`, so visibility, ownership, service validation, status transitions, and completion rewards remain consistent with the Livewire panel.

Creation accepts `referring_account` (`personal` or `business:{id}`), `destination_business_id`, `customer_user_id`, optional `service_key` (`type:id`), and optional `note`. All responses use a top-level `data` member. The bootstrap endpoint accepts `dashboard`, plus `incoming` and `outgoing` for providers, and returns up to 50 newest visible referrals together with counts, coin balance, and referring accounts.

## Referral history filters and provider invitations (2026-08-08)

The page-level navigation now contains only Overview and New Referral. The Overview summary cards are interactive list tabs for Incoming, Outgoing, Pending, and Completed (personal accounts use Outgoing, Pending, and Completed). Switching a tab resets its filters and pagination. Referral lists use Livewire pagination with selectable page sizes of 10, 25, or 50 instead of a hard limit of 50.

Incoming referrals can be filtered by sender/provider or business name, customer phone, and created-date range. Outgoing referrals can be filtered by destination provider or business name and created-date range. The same direction-aware party filter remains available on Pending and Completed views. List queries retain the existing access-controlled query scopes before applying these filters.

The Invite Provider/Customer modal now carries the unmatched search phone or email into an editable recipient field and provides a Send invitation action. The backend validates the recipient, includes the inviter's name and the application URL in the message, sends email through Laravel Mail, or sends SMS through the configured `REFERRAL_SMS_ENDPOINT` using `REFERRAL_SMS_TOKEN`. Every attempt is recorded in `referral_invitations` with channel, party, inviter, message, delivery status, sent timestamp, and any failure reason. Migration: `2026_08_08_120000_create_referral_invitations_table.php`.

## Final Referral MVP extension (2026-08-10)

This section supersedes earlier notes where behavior changed. The existing Referral implementation was extended rather than rebuilt.

### Dashboard and workflow

- The regular dashboard remains limited to User and Provider roles; no central Owner/Admin Referral interface was added.
- Provider cards are Incoming, Outgoing, Pending, Completed, and COIN Balance. Personal accounts use Outgoing, Pending, Completed, and My COIN.
- Card colors follow the final soft palette: Incoming blue, Outgoing orange, Pending amber, Completed green, and BC purple.
- Completed counts and history include the normalized legacy statuses `completed`, `complete`, and `settled`.
- Server-side policies continue to enforce receiver Accept, receiver Complete, and referrer Cancel permissions.
- Completion uses a transaction and row lock. The token ledger entry remains idempotent per Referral.
- Cancelled referrals now store `cancelled_at`.

### New Referral interface

- The form contains Referral From, Referral Destination, Customer Details, and Referral Details.
- On desktop the four sections use a compact two-by-two grid so the normal form fits in one viewport. Responsive layouts remain single-column where necessary.
- Provider/Business results use a floating autocomplete and do not push later sections downward.
- Destination search supports Provider name, Business name, email, phone, and location.
- Direct Provider destinations use `provider:{id}`; Business destinations use `business:{id}`.
- Legacy Provider records are identified using the repository's established `service_pr = 1` rule. Destination discovery no longer incorrectly depends on `approved`.
- Phone normalization supports country-code variations plus Persian and Arabic digits. Matching uses a sufficiently distinctive phone suffix for legacy formatted values.
- Customer search supports phone, email, first name, and last name. Suggestions mask phone and email until selection.
- A registered `MainUser` selection is required; typed text alone remains invalid.
- Service selection is required and is limited to referral-enabled services belonging to the selected Provider or Business.
- Reward and Discount are displayed read-only and cannot be submitted or overridden by the frontend.

### Service Referral settings and immutable terms

The legacy Provider services live in multiple tables/connections, so their entities were not duplicated or rewritten. Central settings use `service_referral_settings`, keyed by `service_type` and `service_id`, with Provider and optional Business ownership.

Settings contain:

- `enabled`
- `reward_bc`
- `discount_type` (`none`, `percentage`, or `fixed`)
- `discount_value`
- `discount_currency`

The authenticated settings endpoint is:

`PUT /api/referral-services/{type}/{service}/settings`

It verifies that the authenticated Provider owns the underlying legacy service and, when supplied, the Business.

Referral creation resolves settings on the backend and stores an immutable snapshot:

- `referral_reward_bc`
- `customer_discount_type`
- `customer_discount_value`
- `customer_discount_currency`
- `referral_terms_snapshot_at`

Completion awards `referral_reward_bc` from the Referral snapshot, never the current Service configuration. Existing pending/accepted referrals receive a migration backfill using the former configured completion award.

Migration: `2026_08_10_100000_complete_referral_mvp_schema.php`.

### Invitations and notification abstraction

Opening Invite Provider or Invite Customer now only opens the modal; it performs no database insert, preventing modal-open failures. A secure record is created when Generate Link or a delivery action is requested.

Invitation links use `/join/{64-character-token}`. Only the SHA-256 token hash is stored. Invitations track inviter User, optional Business, invitee type, recipient email/phone, channel, status, sent/opened/accepted timestamps, and expiry. New registrations accept an invitation only when the registered email or normalized phone matches its intended recipient.

Available actions are Generate/Copy Link, Text/SMS, Email, and Share. Copy and local tracking work without external credentials.

External delivery is separated as:

`ReferralInvitationService -> NotificationService -> NotificationProvider -> CustomerIoNotificationProvider`

The former direct Laravel Mail/referral SMS implementation described above is superseded. Customer.io configuration remains server-side and blank in source control:

```env
CUSTOMERIO_APP_API_KEY=
CUSTOMERIO_TRANSACTIONAL_ENDPOINT=
CUSTOMERIO_INVITATION_EMAIL_TRANSACTIONAL_ID=
CUSTOMERIO_INVITATION_SMS_TRANSACTIONAL_ID=
```

Automated email identity defaults are documented as Besmani, `notifications@besmani.com`, with Reply-To `support@besmani.com`. Sender verification, SPF, DKIM, DMARC, Customer.io templates, credentials, and SMS compliance remain manual server/provider configuration.

### Routes added or extended

- `GET /join/{token}`
- `GET /api/referral-customers`
- `GET /api/referral-customers/by-phone`
- `GET /api/referral-destinations`
- `GET /api/referral-destinations/{business}/services`
- `PUT /api/referral-services/{type}/{service}/settings`
- Existing Referral creation, bootstrap, detail, and action routes remain in place.

### Production fixes

- Fixed a server Blade `ParseError: unexpected token "@"` by replacing inline `@php(...)` and attribute-level `@js(...)` usage with compatible Blade/DOM code.
- Invite modals no longer create invitation records while opening.
- Corrected Completed counts for legacy terminal statuses.
- Corrected false "No Provider or Business found" and Customer lookup failures caused by strict approval and phone-format assumptions.
- Standardized the sidebar Referral icon to exchange arrows.

### Verification and deployment

- PHP syntax checks passed for modified PHP files.
- Blade compilation syntax passed.
- Twelve available Unit tests pass with nineteen assertions.
- Source formatting and `git diff --check` passed for the modified Referral files.
- No Customer.io or other external credentials were added.

After deploying changed files to the server, clear Laravel caches:

```bash
php artisan optimize:clear
php artisan view:clear
```

Database migrations and runtime database verification are performed only on the production/cPanel environment, per the project deployment workflow.

## General referrals and invitation copy fallback (2026-08-10)

- Selecting a Provider or Business once again loads Services from its legacy activity-specific model (`ClinicService`, salon assignments, or academy course assignments) and prepends `General Referral` as the first selectable option. Services without an enabled Referral Setting remain selectable with zero BC and no discount.
- A General Referral is stored with no external service ID/type, a `General Referral` title snapshot, zero BC reward, and no customer discount.
- The shared Invite Provider/Invite Customer modal continues to generate secure links for either party. Copy Link now shows confirmation and falls back to the legacy selection/copy mechanism when the Clipboard API is unavailable or the page is not in a secure browser context.

## Immediate invitation links (2026-08-10)

- Invite Provider and Invite Customer now validate the best available recipient and create the secure invitation before opening the modal. The generated URL and Copy Link action are therefore available immediately; the separate Generate Link step was removed.
- Customer invitations prefer a valid email and otherwise use the normalized phone number. The modal recipient is read-only so it cannot drift from the recipient bound to the token.
- The initial invitation ID is retained while the modal is open. Email and SMS delivery reuse that invitation record, message, and token instead of creating a second invitation URL.
- Invitation buttons are disabled while creation is in progress, and creation errors leave the modal closed.
