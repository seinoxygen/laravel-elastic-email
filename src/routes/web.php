<?php
use Illuminate\Support\Facades\Route;
use SeinOxygen\ElasticEmail\Http\Controllers\WebhookController;

Route::get('/webhook/elasticemail', [WebhookController::class, 'store']);