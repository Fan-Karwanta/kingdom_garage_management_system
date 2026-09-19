<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Philippine Standard Geographic Code (PSGC) Address Service
    |--------------------------------------------------------------------------
    |
    | The system is PH-only. The PSA PSGC dataset is imported into a local
    | table (tbl_psgc_barangays) so address autocomplete never depends on an
    | external API at runtime.
    |
    | phone_country_code is the constant used for OTP/SMS login instead of the
    | old tbl_countries.phonecode lookup (which was coupled to country_id).
    |
    */

    'psgc' => [
        'phone_country_code' => env('PHONE_COUNTRY_CODE', '+63'),
        'search_limit' => (int) env('PSGC_SEARCH_LIMIT', 20),
        'min_query_length' => (int) env('PSGC_MIN_QUERY_LENGTH', 2),
        // Source URL for the import command (PSGC JSON dump).
        'import_url' => env('PSGC_IMPORT_URL', 'https://psgc.cloud/api/v2/barangays?per_page=500'),
    ],

];
