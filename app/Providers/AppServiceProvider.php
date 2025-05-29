<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Session;
use MongoDB\BSON\ObjectId;
use Illuminate\Session\DatabaseSessionHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* Session::extend('mongodb', function ($app) {
            return new DatabaseSessionHandler(
                $app['db']->connection(env('SESSION_CONNECTION', 'mongodb')),
                'sessions',
                'id',
                'payload',
                'last_activity',
                120
            );
        }); */
    }
}
