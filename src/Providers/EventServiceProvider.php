<?php

namespace SeinOxygen\ElasticEmail\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use SeinOxygen\ElasticEmail\Events\WebhookCallReceived;
use SeinOxygen\ElasticEmail\Listeners\OnEmailSent;
use SeinOxygen\ElasticEmail\Listeners\OnWebhookCall;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WebhookCallReceived::class => [
            OnWebhookCall::class,
        ],
        MessageSent::class => [
            OnEmailSent::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
