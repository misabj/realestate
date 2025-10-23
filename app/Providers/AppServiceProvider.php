<?php

namespace App\Providers;
use Filament\Facades\Filament;
use Filament\Tables\Columns\Layout\Panel;
use Illuminate\Support\ServiceProvider;

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
        Filament::serving(function () {
      
        });
    }

    public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->defaultLocale('en')
        ->locales(['en','sr','ru']); // omogućava promenu jezika u panelu
}
}
