<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\DealRepositoryInterface::class,
            \App\Repositories\Eloquent\DealRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\TaskRepositoryInterface::class,
            \App\Repositories\Eloquent\TaskRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });
    }
}
