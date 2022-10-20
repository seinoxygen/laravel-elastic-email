<?php

namespace SeinOxygen\ElasticEmail\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElasticEmailHit extends Model
{
    use HasFactory;

    protected $table = "elastic_email_hits";

    protected $fillable = [
        'transaction_id',
        'message_id',
        'status',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
