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
