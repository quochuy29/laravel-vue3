<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind("App\Repositories\BaseRepository", "App\Repositories\Impl\BaseRepositoryImpl");
        $this->app->bind("App\Repositories\EventRepository", "App\Repositories\Impl\EventRepositoryImpl");
        $this->app->bind("App\Repositories\RequestApproveRepository", "App\Repositories\Impl\RequestApproveRepositoryImpl");
        $this->app->bind("App\Repositories\RequestStatusRepository", "App\Repositories\Impl\RequestStatusRepositoryImpl");
        $this->app->bind("App\Repositories\CalendarRepository", "App\Repositories\Impl\CalendarRepositoryImpl");
        $this->app->bind("App\Repositories\RequestRepository", "App\Repositories\Impl\RequestRepositoryImpl");
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
