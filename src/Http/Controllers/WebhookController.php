<?php

namespace SeinOxygen\ElasticEmail\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SeinOxygen\ElasticEmail\Events\WebhookCallReceived;

class WebhookController extends Controller
{
    public function store(Request $request){
        event(new WebhookCallReceived($request));

        return response()->json(['message' => 'ok']);
    }
}