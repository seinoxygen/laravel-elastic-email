<?php

namespace SeinOxygen\ElasticEmail\Listeners;

use SeinOxygen\ElasticEmail\Events\WebhookCallReceived;
use SeinOxygen\ElasticEmail\Facades\ElasticEmail;
use SeinOxygen\ElasticEmail\Models\ElasticEmailHit;
use SeinOxygen\ElasticEmail\Models\ElasticEmailOutbound;

class OnWebhookCall
{
    public function handle(WebhookCallReceived $event)
    {
        if (config('elasticemail.save_hits')) {
            
            if(!isset($event->request->transaction) || !isset($event->request->messageid)){
                return false;
            }

            ElasticEmailHit::create([
                'transaction_id' => $event->request->transaction,
                'message_id' => $event->request->messageid,
                'status' => $event->request->status,
                'data' => $event->request->all()
            ]);

            $response = ElasticEmail::Email()->GetStatus($event->request->transaction, $showFailed = false, $showSent = true, $showDelivered = true, $showPending = true, $showOpened = true, $showClicked = false, $showAbuse = false, $showUnsubscribed = false, $showErrors = false, $showMessageIDs = false);

            $outbound = ElasticEmailOutbound::where('transaction_id', $event->request->transaction)->first();

            if($response->deliveredcount > 0 && is_null($outbound->delivered_at)){
                $outbound->delivered_at = now();
                $outbound->save();
            }

            if($response->openedcount > 0 && is_null($outbound->opened_at)){
                $outbound->opened_at = now();
                $outbound->save();
            }
        }
    }
}
