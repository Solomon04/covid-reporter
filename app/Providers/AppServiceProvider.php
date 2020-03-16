<?php

namespace App\Providers;

use App\Contracts\LocationValidation;
use App\Services\LocationValidationProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Location Validation interface binding.
        $this->app->bind(LocationValidation::class, function (){
            return new LocationValidationProvider($this->app->make('files'));
        });
    }
}
