<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\repositories\Eloquent\EloquentUserRepository;
use App\repositories\Contracts\UserRepositoryInterface;

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
    }
}
