<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Elastic Email API Key
    |--------------------------------------------------------------------------
    |
    | This value is the API key provided by Elastic Email.
    |
    */
    'key' => env('ELASTIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Elastic Email Account Email
    |--------------------------------------------------------------------------
    |
    | This value is the account email provided by Elastic Email.
    |
    */
    'account' => env('ELASTIC_ACCOUNT'),

    /*
    |--------------------------------------------------------------------------
    | Elastic Email Account Email
    |--------------------------------------------------------------------------
    |
    | This value is the account email provided by Elastic Email.
    |
    */
    'transactional' => env('ELASTIC_IS_TRANSACTIONAL', false),

    /*
    |--------------------------------------------------------------------------
    | Elastic Email Webhook Secret
    |--------------------------------------------------------------------------
    |
    | This value is the webhook secret provided by Elastic Email.
    |
    */
    'webhook_secret' => env('ELASTIC_WEBHOOK_SECRET'),

     /*
    |--------------------------------------------------------------------------
    | Save Webhook Hits
    |--------------------------------------------------------------------------
    |
    | Keep track of all webhooks received from Elastic Email in the elatic_email_hits table. 
    |
    */
    'save_hits' => false,

    /*
    |--------------------------------------------------------------------------
    | Save Webhook Hits For 30 Days
    |--------------------------------------------------------------------------
    |
    | This will delete all records older than 30 days.
    | Use null to disable this funtionality.
    |
    */
    'delete_after_days' => null,

];
