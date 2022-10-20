<?php

namespace SeinOxygen\ElasticEmail\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class WebhookCallReceived
{
    use Dispatchable, SerializesModels;

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
