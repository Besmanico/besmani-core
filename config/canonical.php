<?php

return [
    'features' => [
        'identity' => env('CANONICAL_IDENTITY_ENABLED', false),
        'business' => env('CANONICAL_BUSINESS_ENABLED', false),
        'services' => env('CANONICAL_SERVICES_ENABLED', false),
        'booking' => env('CANONICAL_BOOKING_ENABLED', false),
        'referrals' => env('CANONICAL_REFERRALS_ENABLED', false),
        'commerce' => env('CANONICAL_COMMERCE_ENABLED', false),
    ],
];
