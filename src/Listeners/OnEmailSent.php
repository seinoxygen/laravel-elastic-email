<?php

namespace SeinOxygen\ElasticEmail\Listeners;

use SeinOxygen\ElasticEmail\Models\ElasticEmailOutbound;
use Illuminate\Mail\Events\MessageSent;

class OnEmailSent
{
    public function handle(MessageSent $event)
    {
        $subject        = $event->message->getSubject();
        $toArr          = $this->parseAddresses($event->message->getTo());
        $ccArr          = $this->parseAddresses($event->message->getCc() ?? []);
        $from           = $this->parseAddresses($event->message->getFrom());
        $body           = $this->parseBodyText($event->message->getBody());
        
        $log = ElasticEmailOutbound::create([
            'message_id' => !is_null($event->message->getHeaders()->get('X-Message-ID')) ? $event->message->getHeaders()->get('X-Message-ID')->getFieldBody() : null,
            'transaction_id' => !is_null($event->message->getHeaders()->get('X-Transaction-ID')) ? $event->message->getHeaders()->get('X-Transaction-ID')->getFieldBody() : null,
            'from' => $from[0],
            'to' => json_encode($toArr),
            'cc' => $ccArr ? json_encode($ccArr) : NULL,
            'subject' => $subject,
            'body' => $body,
            'created_by' => $event->data['created_by'] ?? null,
        ]);

        if(isset($event->data['models']) && !is_null($event->data['models'])){
            foreach($event->data['models'] as $model){
                $log->models()->attach($model[0], ['model_type' => $model[1]]);
            }
        }
        return false;
    }

    private function parseAddresses(array $array): array
    {
        $parsed = [];
        foreach(array_keys($array) as $address) {
            if(!is_null($address)){
                $parsed[] = $address;
            }
        }
        return $parsed;
    }

    private function parseBodyText($body): string
    {
        return preg_replace('~[\r\n]+~', '<br>', $body);
    }
}