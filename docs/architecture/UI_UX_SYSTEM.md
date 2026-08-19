# BESMANI UI/UX SYSTEM & SHARED EXPERIENCE ARCHITECTURE

**Version:** 1.0  
**Baseline date:** August 19, 2026  
**Audience:** BESMANI team, Codex/AI coding agents, designers, frontend/backend developers, reviewers  
**Status:** UI/UX architecture companion to `BESMANI_MASTER_CONTEXT.md`

> This document converts the approved BESMANI product/UI decisions and the Vascular/Beauty dashboard discussion into an implementation contract. It does not require pixel-for-pixel duplication of current screenshots. It defines what must be shared, configurable, vertical-specific, and protected so the team does not rebuild the same page multiple times.

## 1. Core UI Principle

BESMANI should behave as **one platform with multiple specialized experiences**, not a set of independent websites.

```text
Shared BESMANI Design System
        +
Shared Application Shells
        +
Shared Core Pages / Flows
        +
Configurable Vertical Modules
        +
Business-Specific Content / Branding
```

**Build shared functional pages once in BESMANI Core and reuse/configure them in Beauty, Vascular/Clinic, and future verticals.** Do not copy and separately maintain Profile, My Page, Referrals, Appointments, Messages, Notifications, Settings, account switching, or common business-management screens without a documented reason.

At the same time, **do not force every vertical into an identical dashboard or workflow.** Beauty, Clinic/Vascular, Marketplace, Academy, and future verticals may display different contextual modules around the shared Core.

## 2. Four UI Ownership Classes

Every page/widget/component/flow must be classified before implementation.

### A. CORE SHARED
One canonical implementation owned by Core: login/account identity, Profile, My Page shell, Referrals, Messages, Notifications, Settings, appointment shell/lifecycle, account/business switcher, common search/autocomplete, common cards/lists/tables/modals, and common loading/error/permission states.

### B. CORE CONFIGURABLE
One Core implementation driven by context/configuration: Dashboard shell, appointment detail, business-profile editor, My Page sections, service management, event/promotion cards, navigation groups, CTA labels/actions.

### C. VERTICAL-SPECIFIC
Genuinely domain-specific UX: Beauty AI Style/Try-On and portfolio; Clinic care team and EMR/eligibility integrations; Marketplace inventory/orders; Academy class/course modules.

### D. PROTECTED DOMAIN
Sensitive/regulated UX behind stronger trust boundaries: clinical records, prescriptions, sensitive insurance payloads, PHI documents, PHI-bearing clinical messages.

## 3. Experience Surfaces

### Public / Discovery
Home, provider/business profiles, service/product/course detail, locations, media/gallery, reviews, editorial content, events/promotions, search/discovery, booking/auth CTAs. Optimize for SEO, discovery, trust, conversion, accessibility, and mobile.

### Authenticated Application
Dashboard, Profile, My Page, Appointments, Referrals, Network, Messages, Notifications, Orders/Payments where relevant, Business Management, AI tools, Settings, and role/business/vertical modules.

## 4. Shared Dashboard Architecture

Approved direction:

```text
Dashboard
├── Shared Core Section
└── Contextual / Vertical Section
```

Render from `current_user`, active business/location/vertical, membership role, permissions, feature entitlements, subscription/features, and compliance context. Do not create completely separate copied dashboards per site.

Shared candidates: identity/profile summary, next appointments, referral summary, messages, notifications, network activity, quick actions, business/account switcher, relevant commerce status.

Beauty-specific: AI Style/Final Look, portfolio, customer style-intent preparation, beauty services, promotions, before/after workflow.

Clinic/Vascular-specific: care team, operational clinic appointment context, eligibility/insurance integration status, EMR/EHR entry, clinic workflow widgets, protected medical modules only when authorized.

Marketplace-specific: products, orders, inventory, fulfillment. Academy-specific: courses, capacity, schedules, registrations.

## 5. Navigation Architecture

Shared authenticated items may include Dashboard, My Page, Profile, Appointments, Referrals, Network, Messages, Notifications, Orders/Payments when enabled, Settings. Do not show irrelevant modules simply because Core supports them.

Vertical-specific items are inserted as contextual groups rather than replacing Core navigation.

### Vascular public navigation decisions
- `About` is doctor-focused; do not automatically rename it “Meet Our Team.”
- Preferred public auth/action labels: **Sign up**, **Login**, **Scheduler**.
- Use `Sign up` consistently.
- After authentication, the sign-up position may become username/account identity and Login becomes Logout according to approved behavior.
- Avoid unnecessary use of `patient` in generic shared labels.
- Existing BESMANI accounts should be reused across verticals rather than appearing to require a duplicate account.

## 6. Identity / Authentication UX

Approved login field label: **Phone number or email address**.

Use one BESMANI identity. When an existing user enters another vertical, reuse canonical identity and request only missing vertical-specific fields/consents. Avoid duplicate-account dead ends and silent duplicate identities.

Authenticated navigation should consistently show account identity, Logout, and relevant Dashboard/My Page while preserving vertical context/branding.

## 7. My Page

My Page is a Core personal hub, not merely Profile.

Customer modules may include profile/preferences, appointments, referrals, network, favorites, saved/final AI looks, relevant media, purchases/services, events/offers, BC activity where appropriate.

Provider/business modules may include business/network relationships, incoming/outgoing referrals, appointments, customer preparation, Final Look/intent attached to appointment, events/promotions, business tools, BC activity.

Network UI should prioritize: **summary -> searchable/filterable relationship list -> referral activity/outcomes -> optional graph**. Do not default to an MLM-style tree.

## 8. Referral UI — Locked MVP

Referral is a Core shared flow. User and Provider use the regular Referral dashboard; Admin/Owner referral configuration belongs in BESMANI Admin.

Canonical creation flow:
1. Referral From
2. Referral Destination
3. Customer Details
4. Referral Details

`Referral From` explains who is sending the referral.

Referral Destination must use a real searchable **Combobox/Autocomplete with floating results**, keyboard accessibility, and a compact selected state. Do not use a page of static destination cards.

Customer Details supports phone/email lookup, name, registered-account matching and safe invitation/registration without silent duplicate accounts.

Referral Details may contain service, notes, reward, customer discount, terms/status context.

Every referral is visible to: sender/referrer, destination Provider/Business, and referred registered Customer/User. The customer sees the referral even though they did not create it.

Referral-linked appointment action becomes available only after destination acceptance.

Use customer/client for Beauty rather than patient; use domain-specific medical terminology only when genuinely required.

## 9. Appointments / Scheduler

Appointment UX is a Core primitive with vertical extension slots. Design for pending/requested, accepted/confirmed, scheduled, completed, cancelled, and rescheduled where supported.

Appointment list/card should expose service, business, location, provider/staff when relevant, date/time/timezone, status, referral indicator, relevant actions.

Appointment detail common shell: who/where/when, service, status, permitted generic notes, communications, referral source, monetary snapshot where appropriate.

Beauty extension: Final Look, reference media, customer style/preferences, preparation notes, supply/preparation indicator.

Clinic extension: care team, eligibility/insurance entry, authorized clinical-system link. Generic Core components must not expose protected clinical content without explicit authorization.

## 10. Beauty AI Style / Try-On

Strategic workflow:

```text
Discover/Book
 -> AI Style / Intent
 -> Capture or Upload
 -> Generate / Explore / Select
 -> Save Final Look
 -> Attach to Appointment
 -> Provider Reviews Before Visit
 -> Service
 -> Optional Before/After / Share
```

Customer supports camera capture, selfie/photo upload, video when useful, category/style selection, alternatives, save/finalize, and sharing with a specific appointment/provider.

Provider upcoming-appointment view should surface the authorized Final Look prominently. Provider may see only media/intent authorized by that appointment/business relationship, not the customer's entire private My Page/media history.

Provider/in-location before/after capture may be supported with explicit consent/visibility, ownership/access, and retention/deletion rules.

Define empty/failure/permission states: no media, generation failed, draft but no Final Look, Final Look not shared, provider lacks permission, media deleted/expired.

## 11. Events / Promotions UX

Events/promotions are shared platform capabilities. Eligible offers may appear in public discovery, business/profile/service pages, My Page, booking, dashboard, and notifications.

Promotion cards should show concise occasion/title, offer, business/service, valid date/time, eligibility, CTA, and terms when necessary.

Avoid turning dashboards into ad feeds. Rank by relevance, eligibility, geography, active vertical, service/appointment context and expiry.

Recurring programs such as weekly Kids Day must be recurrence rules, not manually cloned cards.

## 12. Public Vascular / Clinic Website Decisions

Vascular is a reference for the Clinic vertical, not an isolated architecture.

Public site combines doctor/practice trust, services, educational content, booking/scheduler, auth/account entry, locations/media/reviews where applicable.

Approved About semantics: doctor-focused (`About Dr. Reza`).

Approved semantic label: `Practice Philosophy` for the relevant three-image/philosophy section. This content should be editable through administration rather than hard-coded.

The editorial/content area must support educational topics, health/wellness, pre/post-procedure care, practice/business content and future content types without a narrow hard-coded taxonomy.

Clinic/Vascular should favor larger readable text, good contrast, obvious buttons, simple navigation, minimal cognitive load, clear appointment actions, keyboard accessibility and visible focus states. Accessibility is a Core standard.

## 13. Shared Business/Profile UX

Core business-profile shell may include name/logo/image, summary/about, contacts, locations, hours, service offerings, staff/providers, reviews, media, booking CTA, referral capability, promotions/events.

Vertical extension slots:
- Beauty: portfolio, beauty categories, AI-style compatibility, before/after.
- Clinic: physician/practice credentials, specialty, clinical-system links, insurance/eligibility info, protected-domain entry.
- Academy: courses, instructors, capacity/schedule.
- Store: products and fulfillment/shop info.

## 14. Shared Design System

One reusable system should define typography, spacing, radius, elevation, icons, buttons, links, inputs, autocomplete, selects, checkbox/radio, tabs, status chips, cards, tables, lists, dialogs/modals, drawers, popovers, toasts/alerts, skeleton/loading, empty states, pagination, breadcrumbs and mobile navigation.

Vertical themes may override controlled tokens, not component behavior.

## 15. Page Shells

Recommended reusable shells:
- `PublicSiteShell`
- `AuthenticatedAppShell`
- `AdminShell`
- `BusinessManagementShell`
- `ProtectedMedicalShell`

Shells define header, navigation, responsive container, account/notifications, breadcrumbs/page heading and access boundary. Do not create a shell per individual provider/business.

## 16. Component Composition Rule

```text
Shared Page Shell
+ Core Domain Components
+ Context Configuration
+ Vertical Extension Slots
+ Business Content
```

Example:

```text
AppointmentDetailPage
├── AppointmentSummary         [CORE]
├── StatusActions              [CORE CONFIGURABLE]
├── ReferralContext            [CORE]
├── Messages                   [CORE]
├── BeautyFinalLook            [BEAUTY EXTENSION]
└── ClinicalSystemLaunch       [CLINIC EXTENSION]
```

Prefer this over duplicated Beauty/Vascular/Travel/Pet appointment pages.

## 17. Role / Context Rules

Visibility must not be decided only from route/site. The same page may render differently for Customer/User, Provider, Business Owner/Admin, Staff, platform Admin, Owner/Super Admin.

Backend policies remain authoritative. Unrelated providers cannot view appointments/referrals/customer media; medical access uses protected-domain rules.

## 18. Responsive / Mobile Rules

Mobile is first-class. Primary tasks must work without horizontal scrolling; tables may degrade to cards; popovers must work on touch; appointment/referral CTAs remain reachable; media capture is mobile-friendly; safe areas and mobile keyboard behavior are considered. Do not build desktop-only dashboards and shrink later.

## 19. Accessibility Rules

Use semantic structure, keyboard navigation, visible focus, real labels, associated errors, adequate contrast, correct dialog/popover focus management, accessible labels for icon-only controls, reduced-motion consideration, non-color-only status meaning, and adequate touch targets.

## 20. Loading / Empty / Error States

Every shared module defines loading, empty, first-use, permission denied, server/network error, stale integration, and partial-data states. Avoid blank cards.

Examples: `No referrals yet -> Send a referral`; `No upcoming appointments -> Find a service`; `No Final Look selected -> Create a look`.

## 21. Notifications / Messaging

Core owns generic notification/messaging UX across in-app/email/SMS/push. Show notification center, read/unread, category, preferences and deep links. Do not expose delivery vendor names such as Customer.io to ordinary users. PHI-bearing communication follows protected-domain rules.

## 22. Search / Discovery

Long-term search spans providers/businesses, services, products, courses, locations, promotions and content. Structured filters remain important even with AI search: category, distance/location, availability, price, specialty/service, reputation, promotions, verified/claimed, network/referral context. Paid placement must not silently corrupt trust-sensitive ranking.

## 23. Business Claim UX

Claim flow clearly identifies the business, verifies identity/authority, attaches canonical user to existing business, avoids duplicate business creation and continues to activation/profile/services. Authorized claimed businesses then manage permitted content/features.

## 24. Admin UX Boundary

BESMANI Admin remains central for users, businesses, permissions, service catalog, referral configuration, events/promotions, moderation, settings, analytics/audit. Vertical admin capabilities should normally appear as modules rather than disconnected admin products. Sensitive medical administration may require protected shell/permissions.

## 25. Content / SEO UX

Separate visible title/card summary from meta title/meta description, structured content, image/alt text and canonical URL. Do not force awkward SEO copy into visible cards merely to avoid another field.

## 26. Figma Policy

Use Figma for net-new cross-platform flows, high-risk redesigns, design-system components/tokens, responsive variants, interaction prototypes and visual ambiguity that would cause expensive rework.

Figma is **not required** to redraw every already-working page. For existing Vascular/Beauty screens: preserve proven behavior, classify components, extract shared patterns, redesign only known problem areas. Product requirements + implementation contracts are the behavior source of truth; Figma is a design artifact, not DB/business logic.

## 27. Required Shared Component Registry

Maintain a registry such as:

```text
AppShell                     Core                 All authenticated apps
PublicSiteShell              Core/configurable    Public vertical sites
AccountMenu                  Core                 All
ProfilePage                  Core                 All
MyPage                       Core/configurable    All
AppointmentList              Core                 Bookable verticals
AppointmentDetail            Core/configurable    All
ReferralPage                 Core                 User/Provider
ReferralCombobox             Core                 Referral flows
MessageCenter                Core                 Eligible contexts
NotificationCenter           Core                 All
BusinessProfileShell         Core/configurable    Business verticals
AIStyleWorkspace             Beauty               Beauty
FinalLookAppointmentPanel    Beauty extension     Appointment detail
CareTeamPanel                Clinic extension     Clinic
EMRIntegrationPanel          Clinic extension     Clinic
ProductInventory             Marketplace          Marketplace
CourseManagement             Academy              Academy
```

The real registry should later match implemented component names.

## 28. UI State Ownership

Frontend UI is never the authority for security/lifecycle. Hidden button != permission; client role != authorization; route ID != ownership; status chip != backend state source.

Frontend should consume explicit capabilities where practical: `can_edit`, `can_accept`, `can_cancel`, `can_book`, `can_view_style`, `can_view_clinical_record`.

## 29. API / UI Contract Direction

Shared pages consume canonical contracts such as `/me`, `/me/profile`, `/me/appointments`, `/me/referrals`, `/businesses/{business}`, `/businesses/{business}/services`, `/businesses/{business}/appointments`. UI may differ by vertical but shared semantics remain stable. Beauty UI must not depend forever on raw legacy table shapes.

## 30. Migration of Existing UI

Do not rewrite everything at once. Use a strangler approach: identify shared/vertical pieces -> preserve current route/behavior -> introduce shared component/service -> migrate one module -> regression test -> reuse in second vertical -> retire duplicate implementation.

Priority: identity/account shell; navigation/app shell; Profile/My Page; Referral; Appointments; Messages/Notifications; Business/Profile management; vertical extensions.

## 31. Regression Checklist

For every shared-page migration verify role visibility, business membership scope, active business/location, mobile, keyboard, empty/error states, direct links, unauthorized deep links, Beauty context, Vascular/Clinic context, legacy compatibility, PHI isolation, and no duplicate user/business creation.

## 32. Screenshot / Existing UI Usage

Vascular public-site and dashboard screenshots reviewed during product discussions are **reference implementations**, not independent architecture specifications. Preserve useful information architecture, user expectations, clinic-specific needs and proven patterns. Do not infer from a screenshot that a page must be copied into Core, a current layout is permanently locked, a medical widget belongs in Beauty, or a visual card represents a separate database domain. Locked product/security requirements win over screenshot artifacts.

## 33. Do-Not-Redo Decisions

- Shared functional pages are implemented once in Core when truly shared.
- Vertical dashboards use shared + contextual modules; they are not identical.
- Identity is canonical/shared over time; verticals should not create duplicate accounts.
- Referral is Core with locked four-section flow and three-party visibility.
- My Page is Core and includes Network.
- Network is many-to-many; operational list/filter first, optional graph second.
- AI Style/Try-On is a strategic Beauty module linked to My Page/Appointments.
- Provider can review authorized Final Look/intent before appointment.
- Events/Promotions are reusable platform capabilities.
- Clinic/Vascular can integrate EMR/EHR/eligibility rather than rebuilding everything.
- PHI is not a generic dashboard data source.
- Public Vascular About remains doctor-focused.
- Approved public Vascular auth labels are Sign up / Login / Scheduler.
- Existing BESMANI accounts are reused across verticals.
- Accessibility/simple clinic UX are platform standards.
- Figma is used when it prevents meaningful rework, not as mandatory redraw bureaucracy.

## 34. Codex / Developer Implementation Gate

Before implementing a new shared page or duplicating an existing one, report:

```text
Page/Flow:
Current implementation(s):
Proposed ownership:
[ ] Core Shared
[ ] Core Configurable
[ ] Vertical Specific
[ ] Protected Domain
Existing component(s) reusable:
Vertical extension required:
Authorization boundary:
Legacy data dependency:
Mobile/accessibility impact:
Proposed files/components:
Duplicate implementation being retired:
```

If the same capability exists in another BESMANI vertical, explicitly justify why reuse/configuration is insufficient before creating another implementation.

## 35. Definition of Done

The UI architecture is working when Profile/My Page/Referral/Appointment common behavior is not duplicated per vertical; Beauty and Clinic add modules without forking shared pages; identity is consistent; business/location/role context changes authorized UI correctly; Referral behavior is preserved; Final Look is safely attached to appointments; medical data stays protected; navigation is context-aware; mobile/accessibility states pass review; shared components have loading/empty/error/permission states; and design changes do not require editing multiple copied versions of the same shared page.

## 36. Next Companion Artifacts

Create only when they reduce ambiguity:

```text
docs/architecture/COMPONENT_REGISTRY.md
docs/architecture/ROUTE_OWNERSHIP.md
docs/architecture/API_CONTRACTS.md
docs/design/DESIGN_TOKENS.md
docs/design/RESPONSIVE_RULES.md
docs/security/MEDICAL_TRUST_BOUNDARY.md
```

## Final Instruction

Before creating a new BESMANI screen, ask:

> **Is the behavior shared, configurable, vertical-specific, or protected?**

Then build it at that ownership level. The objective is a user experience specialized to Beauty, Clinic, Marketplace, Academy and future verticals while still being powered by one coherent BESMANI platform.
