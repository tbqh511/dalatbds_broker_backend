<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use App\Repositories\CrmLeadRepositoryInterface;
use App\Repositories\Eloquent\CrmLeadRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CrmLeadRepositoryInterface::class, CrmLeadRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (empty(config('services.google_maps.place_api_key'))) {
            Log::warning('Google Maps API Key (PLACE_API_KEY) is missing. Please configure it in your .env file.');
        }
    }
}
