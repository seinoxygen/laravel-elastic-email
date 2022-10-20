<?php

namespace SeinOxygen\ElasticEmail;

use Illuminate\Mail\MailServiceProvider as LaravelMailServiceProvider;

class MailServiceProvider extends LaravelMailServiceProvider
{

    protected function registerIlluminateMailer(){

        $this->app->singleton('mail.manager', function($app) {
            return new TransportManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }
}
