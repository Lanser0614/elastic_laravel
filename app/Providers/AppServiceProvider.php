<?php

namespace App\Providers;

use App\Services\MyElastic\ElasticConnect\MyElasticConnect;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Route;
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
