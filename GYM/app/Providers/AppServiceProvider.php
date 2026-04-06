<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ Disable HTML sanitization in Filament
        \Filament\Tables\Columns\TextColumn::configureUsing(function ($column) {
            $column->html();
        });
    }
}