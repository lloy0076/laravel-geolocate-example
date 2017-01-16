<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Geoip2Service;

class Geoip2Provider extends ServiceProvider
{
    /**
     * Defer the binding.
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $geoip2 = new Geoip2Service();

        $this->app->instance('Geoip2', $geoip2);
    }

    /**
     * Define the class this provides.
     */
    public function provides() {
        return [Geoip2Service::class];
    }
}
