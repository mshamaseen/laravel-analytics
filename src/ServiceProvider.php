<?php
namespace Shamaseen\Analytics;

use Illuminate\Support\ServiceProvider as ServiceProviderAlias;

class ServiceProvider extends ServiceProviderAlias
{
    public function boot(){
        //publish configs
        $this->publishes([
            __DIR__.'/../config/la_analytics.php' => config_path('la_analytics.php'),
        ]);

        //load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
