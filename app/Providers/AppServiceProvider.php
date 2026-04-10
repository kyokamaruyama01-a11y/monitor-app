<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Codespaces環境の場合、強制的にHTTPSと正しいURLを使うようにする
        if(config('app.env') !== 'local' || isset($_SERVER['HTTP_X_FORWARDED_HOST'])){
            URL::forceScheme('https');
            //.envのAPP_URLを強制的にルートURLにする
            URL::forceRootUrl(config('app.url'));
        }
        
        //
    }
}
