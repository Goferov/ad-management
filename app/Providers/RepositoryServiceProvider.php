<?php

namespace App\Providers;

use App\Repositories\Contracts\AdEventRepositoryInterface;
use App\Repositories\Contracts\AdRepositoryInterface;
use App\Repositories\Eloquent\AdEventRepository;
use App\Repositories\Eloquent\AdRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AdRepositoryInterface::class, AdRepository::class);
        $this->app->bind(AdEventRepositoryInterface::class, AdEventRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
