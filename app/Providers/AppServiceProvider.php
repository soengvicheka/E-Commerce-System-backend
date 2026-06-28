<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Builder::macro('active', function (Builder $query) {
            return $query->where('is_active', true);
        });

        Builder::macro('featured', function (Builder $query) {
            return $query->where('is_featured', true);
        });
    }
}
