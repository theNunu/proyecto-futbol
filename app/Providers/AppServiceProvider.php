<?php

namespace App\Providers;

use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Erp\Repositories\Eloquent\TransaccionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        // 2. Vincula la Interfaz con su Clase concreta
        $this->app->bind(TransaccionRepositoryInterface::class, TransaccionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
