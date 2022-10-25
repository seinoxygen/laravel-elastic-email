<?php

namespace SeinOxygen\ElasticEmail;

use Illuminate\Mail\MailManager;

class TransportManager extends MailManager
{
    protected function createElasticEmailTransport()
    {
        $config = array_merge($this->app['config']->get('services.elastic_email', []), $this->app['config']->get('elasticemail', []));

        return new ElasticTransport(
            $this->guzzle($config),
            $config
        );
    }
}
