<?php

namespace App\Providers;

use App\repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\repositories\Eloquent\EloquentUserRepository;
use App\repositories\Contracts\UserRepositoryInterface;
use App\repositories\Eloquent\EloquentCategoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->bind(UserRepositoryInterface::class,EloquentUserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class,EloquentCategoryRepository::class);
    }
}
