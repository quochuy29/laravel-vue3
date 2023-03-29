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
        $this->app->bind("App\Repositories\RequestStartRepository", "App\Repositories\Impl\RequestStartRepositoryImpl");
        $this->app->bind("App\Repositories\RequestTypeRepository", "App\Repositories\Impl\RequestTypeRepositoryImpl");
        $this->app->bind("App\Repositories\LeaveRequestRepository", "App\Repositories\Impl\LeaveRequestRepositoryImpl");
        $this->app->bind("App\Repositories\LeaveRequestHistoryRepository", "App\Repositories\Impl\LeaveRequestHistoryRepositoryImpl");
        $this->app->bind("App\Repositories\UserRepository", "App\Repositories\Impl\UserRepositoryImpl");
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
