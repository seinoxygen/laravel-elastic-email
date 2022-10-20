<?php

namespace SeinOxygen\ElasticEmail\Console;

use Illuminate\Console\Command;
use SeinOxygen\ElasticEmail\Models\ElasticEmailHit;

class DeleteOld extends Command
{
    protected $signature = 'elasticemail:delete-old';

    protected $description = 'Delete old elastic email hits from database';

    public function handle()
    {
        if(config('elasticemail.delete_after_days', null)) {
            $this->info('Deleting old elastic email hits older than ' . config('elasticemail.delete_after_days') . ' days.');

            ElasticEmailHit::query()
                ->where('created_at', '<', now()->subDays(config('elasticemail.delete_after_days')))
                ->delete();

            $this->info('Done!');
        } else {
            $this->info('Deleting old elastic email hits is disabled. Check your config file!');
        }
    }
}
