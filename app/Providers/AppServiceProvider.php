<?php

namespace App\Providers;

use App\Clients\DrugClient;
use App\Repositories\MedicationRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\AuthServiceInterface;
use App\Services\DrugSearchService;
use App\Services\DrugSearchServiceInterface;
use App\Services\FormatMedicineApiService;
use App\Services\FormatMedicineApiServiceInterface;
use App\Services\UserMedicationService;
use App\Services\UserMedicationServiceInterface;
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

        $this->app->bind(MedicationRepository::class, function ($app) {
            return new MedicationRepository();
        });

        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        $this->app->bind(AuthServiceInterface::class, function ($app) {
            return new AuthService($app->make(UserRepository::class));
        });

        $this->app->bind(
            FormatMedicineApiServiceInterface::class,
            FormatMedicineApiService::class
        );

        $this->app->bind(DrugSearchServiceInterface::class, function ($app) {
            return new DrugSearchService($app->make(DrugClient::class), $app->make(FormatMedicineApiService::class));
        });

        $this->app->bind(UserMedicationServiceInterface::class, function ($app) {
            return new UserMedicationService($app->make(DrugClient::class),$app->make(MedicationRepository::class), $app->make(FormatMedicineApiService::class));
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
