<?php

namespace MennenOnline\SimpleApiConnector;

use Illuminate\Support\ServiceProvider;

class SimpleApiConnectorServiceProvider extends ServiceProvider
{
    public function boot() {
        $this->publishes([
            __DIR__.'/../config/api' => config_path('api'),
        ], 'config');
    }
}