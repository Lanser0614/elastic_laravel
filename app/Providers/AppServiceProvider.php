<?php

namespace App\Providers;

use App\Services\MyElastic\ElasticConnect\MyElasticConnect;
use App\Services\MyElastic\ElasticSearchBuilder\ElasticBuilder;
use App\Services\MyElastic\ElasticSearchBuilder\Interfce\ElasticBuilderInterface;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $this->app->bind(ElasticBuilderInterface::class, ElasticBuilder::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        DB::listen(function (QueryExecuted $query) {
            Log::debug(
                "My Sql Logging",
                [
                    "sql" => $query->sql,
                    'REQUEST_TIME' => request()->server()['REQUEST_TIME'],
                    'sql_time_working' => $query->time,
                    "url" => request()->url(),
                    "queryParam" => request()->query(),
                    "body" => request()->request->all()
                ]
            );

        });
    }
}
