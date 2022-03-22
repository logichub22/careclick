<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Billing\Rave;
use Illuminate\Http\Request;

class RaveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Rave::class, function ($app)
            {
                return new Rave(config('services.rave.publickey'), config('services.rave.secretkey'));
            });
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
