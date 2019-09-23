<?php

namespace TrayLabs\InfluxDB\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use InfluxDB\Client as InfluxClient;
use InfluxDB\Database as InfluxDB;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/InfluxDB.php' => config_path('influxdb.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(InfluxDB::class, function($app) {
            $client = new InfluxClient(
                $this->app['config']->get('influxdb.host'),
                $this->app['config']->get('influxdb.port'),
                $this->app['config']->get('influxdb.username'),
                $this->app['config']->get('influxdb.password'),
                $this->app['config']->get('influxdb.ssl'),
                $this->app['config']->get('influxdb.verifySSL'),
                $this->app['config']->get('influxdb.timeout')
            );
            return $client->selectDB($this->app['config']->get('influxdb.dbname'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            InfluxDB::class,
        ];
    }
}
