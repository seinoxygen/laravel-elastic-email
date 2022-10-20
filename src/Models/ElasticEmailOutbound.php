<?php

namespace SeinOxygen\ElasticEmail\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElasticEmailOutbound extends Model
{
    use HasFactory;

    protected $table = "elastic_email_outbound";

    protected $fillable = [
        'message_id',
        'transaction_id',
        'from',
        'to',
        'cc',
        'subject',
        'body',
        'attachments',
        'created_by',
        'delivered_at',
        'opened_at'
    ];

    public function models(){
        return $this->morphedByMany(
            ElasticEmailOutbound::class,
            'model',
            'model_has_elastic_email_outbound',
            'elastic_email_outbound_id',
            'model_id'
        );
    }
}
