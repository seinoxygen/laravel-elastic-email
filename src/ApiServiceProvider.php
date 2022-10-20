<?php

namespace SeinOxygen\ElasticEmail;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use SeinOxygen\ElasticEmail\Console\DeleteOld;
use SeinOxygen\ElasticEmail\Providers\EventServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register the Swift Transport instance.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ElasticEmail', function () {
            return new ElasticApi();
        });

        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();

            $this->commands([
                DeleteOld::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('elasticemail:delete-old')->daily();
            });      
        }  
    }

    private function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/elasticemail.php' => base_path('config/elasticemail.php'),
        ], 'config');

        if (!class_exists('CreateElasticEmailHitsTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_elastic_email_hits_table.php.stub' => database_path(
                    sprintf('migrations/%s_create_elastic_email_hits_table.php', date('Y_m_d_His'))
                ),
            ], 'migrations');
        }
    }
}
