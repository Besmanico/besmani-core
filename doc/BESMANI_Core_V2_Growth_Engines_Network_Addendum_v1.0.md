# BESMANI Core V2 - Growth Engines & Network Addendum v1.0

**Date:** 2026-08-15  
**Status:** Approved architectural and product requirement  
**Architecture authority:** This addendum is part of the approved BESMANI Core V2 architecture. It is not an optional future feature list.

## 1. Core strategy

BESMANI's growth and monetization system has two primary engines.

### Engine A - Network

Build a living network of Users, Customers, Providers, Businesses, Locations, and Services.

Core capabilities include discovery, connection, messaging, referral, reference/recommendation, sharing, opportunities, booking, rewards, and repeat relationships.

`Discover -> Connect -> Message -> Refer/Recommend -> Book -> Complete -> Reward/Review/Share -> New discovery and referrals -> Repeat`

Referral and Reference/Recommendation are principal mechanisms for making the network useful and visible.

### Engine B - AI service intent and preparation

Booking is not merely a provider, date, and time.

`Discover service -> AI Style/Look Builder -> Customer Preferences -> Reference media/AI result -> Appointment Brief -> Provider Preparation -> Inventory Readiness -> Service -> Capture Result -> Share/Review/Refer -> Repeat`

## 2. BESMANI flywheel

`Network -> Referral/Reference/Message/Opportunity -> AI Style Intent -> Booking -> Provider Preparation -> Service Result -> Photo/Video/Before-After -> My Page/Portfolio -> Share/Review -> Referral/Recommendation -> New User/Provider -> Network Growth`

## 3. Required network experience

- Referrals: send/receive customer, requests, status, BC/rewards, and service/appointment linkage.
- References/Recommendations: recommend a Provider, Business, or Service; ask the network; preserve trust, provenance, and history.
- Messaging/Connections: user-provider, provider-provider, and permitted business-customer conversations contextualized by a referral, recommendation, booking, campaign, or opportunity.
- Opportunities: special days, recurring promotions, local events, campaigns, targeted offers, and referral opportunities.

## 4. Opportunities and campaigns

These concepts must not be modeled as a single generic Event or as hardcoded tables for each profession or special day. The architecture requires a flexible Campaign/Opportunity domain supporting:

1. Special Days/Weeks.
2. Recurring Specials.
3. Local Events.
4. Network Campaigns created by BESMANI with Provider/Business opt-in.

Provider offers may define title, description, audience, eligibility, discount/reward, services, locations, start/end, recurrence, capacity, referral eligibility, shareability, geographic visibility, terms, and status.

Required conceptual entities include campaigns, occurrences, audiences, participating Businesses, Locations and Services, offers, eligibility rules, capacity rules, recurring schedules, opportunity referrals, campaign bookings, and campaign metrics.

## 5. Happening on BESMANI

Homepage and My Page must be able to surface a dynamic Happening on BESMANI experience including current offers, events, appreciation programs, local opportunities, referral opportunities, recommendations, trending looks/services, and nearby participating Businesses.

Personalization may use location, interests/preferences, followed Providers, prior services, saved looks, network relationships, explicit or verified eligibility attributes, and active campaigns. Eligibility and sensitive profile data must never be inferred or exposed outside their authorized purpose.

## 6. Opportunity-to-referral loop

Eligible offers must be shareable and referral-enabled. A shared offer can create a traceable recommendation/referral, lead to a Campaign Booking, and trigger an immutable reward/discount outcome after service completion.

## 7. My Page, Social, and UGC

Users can capture/upload selfies, photos, and videos; create posts; save looks; share results; tag Provider, Business, Service, and Appointment; publish before/after content with consent; and recommend/refer from content.

Providers use the same canonical human identity and may maintain Provider/Business presence, portfolios, service result media, reviews, referrals/recommendations, and campaign participation. Provider is a capability and membership context, not a separate identity.

Required concepts include posts, post media, comments, reactions, follows, saved posts, shares, typed content tags, visibility, moderation reports, and media consent.

## 8. AI Style / Look Builder

Users can provide current and inspiration media, generate or modify looks, compare variants, approve a result, save it, attach it to an appointment, and share it with an authorized Provider.

The model must retain AI provenance, input/output media links, model/configuration metadata, generation lineage, selection, and explicit user approval state.

## 9. Customer Style Profile

Reusable preferences may include aesthetics, favorite/disliked colors or styles, maintenance preferences, saved looks, and service- or Provider-specific preferences.

Access scopes include private, public, shared with Provider, shared for Appointment, and shared with Business. Sharing must be explicit, revocable, purpose-bound, and auditable.

## 10. Service Preference Schemas

Different Services require configurable and versioned questions. The architecture must not create separate preference tables for every Service type.

Required concepts include schemas, fields, options, schema assignment/versioning, responses, and immutable response snapshots used by Appointment Briefs.

## 11. Appointment Brief and Provider Customer View

Relevant Appointments can carry a structured brief containing desired outcome, current-condition media, reference media, approved AI result, Customer notes, Service preferences, Q&A, Provider notes, preparation requirements, and duration implications.

Authorized staff with a valid Appointment context may see only necessary and authorized information: limited Customer identity, history with that Provider/Business, public or explicitly shared My Page content, shared Style Preferences, the Appointment Brief, reference media, approved AI output, and relevant Service notes.

An Appointment never grants access to the Customer's entire private My Page.

## 12. Provider Preparation and Inventory Readiness

Providers must be able to confirm feasibility, assign staff, reserve room/equipment, prepare products/materials, adjust duration, and request clarification or consultation.

Future inventory readiness must support:

`Appointment intent -> Service/material requirements -> Inventory check -> Alert -> Reserve/Reorder/Substitute -> Confirm`

Service and Booking architecture must carry these relationships from the beginning even when inventory automation is implemented later.

## 13. Before/After and result-to-network growth

Service results may become a consent-controlled before/after set, My Page content, Provider portfolio material, a verified review, a recommendation, a referral, or a share. Publication and portfolio reuse require explicit consent and preserve provenance back to the Appointment and participating parties.

## 14. Besmo AI boundary

Besmo operates only through authorized APIs and must respect privacy, consent, business membership, appointment purpose, and medical boundaries. Besmo suggestions/actions must be auditable and must not bypass domain policies.

## 15. Medical boundary

Clinical/PHI photos, treatment documentation, diagnoses, prescriptions, medical records, and PHI messages do not automatically enter Social/My Page. They remain in the protected Medical domain. Public/marketing use requires an explicit compliant consent process and a controlled non-PHI public copy/reference.

## 16. Architectural direction

Core V2 Phase 1 remains valid. Before Phase 2, the Master Architecture and ERD must formally include the Network Engine, Referral, Reference/Recommendation, Messaging/Connections, Opportunities/Campaigns, Happening on BESMANI, Social/UGC, My Page, AI Style Builder, Customer Style Profile, Service Preference Schemas, Appointment Brief, Provider Customer View, Provider Preparation, Before/After, and future Inventory Readiness.

Phase 2 Service Catalog and Business Services, and later Booking, must implement the relationships and invariants defined by this requirement.

## 17. Product decision rule

Prioritize capabilities that strengthen the Provider-Customer network, increase trusted referral/recommendation/discovery, communicate desired outcomes, improve Provider preparation and service quality, create consented result content that feeds discovery, and create legitimate monetization without degrading trust.

## 18. Position

BESMANI combines:

`NETWORK + REFERRAL/RECOMMENDATION + COMMUNICATION`

with:

`AI INTENT + BOOKING PREPARATION + SERVICE RESULTS`

to create a self-reinforcing Provider/Customer ecosystem.
