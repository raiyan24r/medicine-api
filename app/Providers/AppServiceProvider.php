<?php

namespace App\Providers;

use App\Clients\DrugClient;
use App\Services\DrugSearchService;
use App\Services\DrugSearchServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DrugClient::class, function ($app) {
            return new DrugClient();
        });

        $this->app->bind(DrugSearchServiceInterface::class, function ($app) {
            return new DrugSearchService($app->make(DrugClient::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('/Http/Helpers/HttpResponse.php');
    }
}
