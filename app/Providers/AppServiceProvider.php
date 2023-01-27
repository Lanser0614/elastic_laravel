<?php

namespace App\Providers;

use App\MyElastic\MyElasticConnect;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(MyElasticConnect::class, function () {
            return (new MyElasticConnect())->getElasticClient();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
