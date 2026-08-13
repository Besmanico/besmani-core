<?php

return [
    'identity' => [
        // Keep null unless the source population has a known ISO 3166-1 region.
        // National-format numbers without a region are ambiguous and are not matched.
        'default_phone_region' => env('CANONICAL_PHONE_DEFAULT_REGION'),
    ],
    'features' => [
        'identity' => env('CANONICAL_IDENTITY_ENABLED', false),
        'business' => env('CANONICAL_BUSINESS_ENABLED', false),
        'services' => env('CANONICAL_SERVICES_ENABLED', false),
        'booking' => env('CANONICAL_BOOKING_ENABLED', false),
        'referrals' => env('CANONICAL_REFERRALS_ENABLED', false),
        'commerce' => env('CANONICAL_COMMERCE_ENABLED', false),
    ],
];
