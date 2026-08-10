<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
    ],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'customerio' => [
        'app_api_key' => env('CUSTOMERIO_APP_API_KEY'),
        'transactional_endpoint' => env('CUSTOMERIO_TRANSACTIONAL_ENDPOINT', 'https://api.customer.io/v1/send/email'),
        'invitation_email_transactional_id' => env('CUSTOMERIO_INVITATION_EMAIL_TRANSACTIONAL_ID'),
        'invitation_sms_transactional_id' => env('CUSTOMERIO_INVITATION_SMS_TRANSACTIONAL_ID'),
    ],

];
