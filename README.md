# Laravel Elastic Email #

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/donate/?hosted_button_id=6CYVR8U4VDMAA)

A Laravel wrapper for sending emails via Elastic Email service and API capabilities that allows you to check the status of every email sent.
It provides a basic email log table to store all outbound emails where you can link to a model.

## Installation

Add Laravel Elastic Email as a dependency using the composer CLI:

```bash
composer require seinoxygen/laravel-elastic-email
```

## Mail Service Usage

This package works exactly like Laravel's native mailers. Refer to Laravel's Mail documentation.

Add the following to your config/services.php and add the correct values to your .env file

```php
'elastic_email' => [
	'key' => env('ELASTIC_KEY'),
	'account' => env('ELASTIC_ACCOUNT')
]
```

Add the following to your config/mail.php

```php
'elastic_email' => [
	'transport' => 'elasticemail'
]
```

Next, in config/app.php, comment out Laravel's default MailServiceProvider. If using < Laravel 5.5, add the MailServiceProvider and ApiServiceProvider to the providers array

```php
'providers' => [
    ...
    SeinOxygen\ElasticEmail\MailServiceProvider::class,
    SeinOxygen\ElasticEmail\ApiServiceProvider::class,
    ...
],
```

Next, in config/app.php, add the ElasticEmail to the aliases array

```php
'aliases' => [
    ...
    'ElasticEmail' => SeinOxygen\ElasticEmail\Facades\ElasticEmail::class,
    ...
],
```

Finally switch your default mail provider to elastic email in your .env file by setting **MAIL_DRIVER=elastic_email**

## Outbound Email Tracking

To keep track of all emails sent by the driver you'll need to publish the migrations and the configuration files:

```bash
php artisan vendor:publish --provider="SeinOxygen\ElasticEmail\ApiServiceProvider" --tag="migrations"
```

```bash
php artisan migrate
```

```bash
php artisan vendor:publish --provider="SeinOxygen\ElasticEmail\ApiServiceProvider" --tag="config"
```

By default all outgoing emails will be stored with the Elastic Email **message_id** and **transaction_id**.

Check **config/elasticemail.php** for more options.

### Linking Outgoing Emails To Your Models ###

In your mailable be sure to set the with array the following way.

```php
public function build()
{
    // You can set ad many models you want to relate with the outgoing email
    $models = [
        [$yourmodel->id, get_class($yourmodel)],
    ];

    return $this
        ->subject("My Subject")
        ->view('my-view')
        ->with([
            'models' => $models
        ]);
}
```

Sorry if it looks ugly. I haven't found a better way to do this...yet.

### Capturing Webhook Events ###

You will need to set a webhook in Elastic Email service pointing to **yourappurl.com/webhook/elasticemail**

There is an event being fired when data is sent to the webhook url.

```php
<?php

namespace app\Listeners;

use SeinOxygen\ElasticEmail\Events\WebhookCallReceived;

class WebhookCallListerner
{
    public function handle(WebhookCallReceived $event)
    {
        $request = $event->request;
    }
}
```

## Api Usage

For documentation visit https://api.elasticemail.com/public/help

```php

    //For contact
    ElasticEmail::Contact()

    //For emails
    ElasticEmail::Email()

```

## Credits

This package is based on [ZanySoft](https://github.com/zanysoft/laravel-elastic-email)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
