<?php

namespace Cksaa\LaravelAliMts;

use Illuminate\Support\ServiceProvider;

class MtsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/mts.php' => config_path('mts.php'),
        ]);
    }

}