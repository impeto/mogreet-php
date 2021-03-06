<?php

namespace Mogreet\Laravel;

use Illuminate\Support\ServiceProvider;
use Mogreet\Client;

class MogreetServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $config = $this->app->make('config');

        if ( $config->has( 'mogreet')){
            $client = new Client( $config->get('mogreet.client_id'), $config->get('mogreet.token'));
        } else {
            $client = new Client();
        }

        $this->app->singleton('mogreet', function( $app) use ( $client){
            return $client;
        });

        $this->app->singleton('Mogreet\Client', function( $app) use ( $client){
            return $client;
        });
    }

    public function provides()
    {
        return ['mogreet', 'Mogreet\Client'];
    }
}