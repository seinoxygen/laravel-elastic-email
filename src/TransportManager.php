<?php

namespace SeinOxygen\ElasticEmail;

use Illuminate\Mail\MailManager;

class TransportManager extends MailManager
{
    protected function createElasticEmailTransport()
    {
        
        $config = $this->app['config']->get('services.elastic_email', []);

        return new ElasticTransport(
            $this->guzzle($config),
            $config['key'],
            $config['account']
        );
    }
}
