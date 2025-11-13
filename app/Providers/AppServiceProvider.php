<?php

namespace App\Providers;

use App\Models\Historic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Historic::created(function ($historic) {
            if ($historic->check() == true) {
                Log::info('Passando no IFeeee Provider', ['id' => Auth::id()]);
                return false;
            }
            Log::info('Passando no ELSEee Provider', ['id' => Auth::id()]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
