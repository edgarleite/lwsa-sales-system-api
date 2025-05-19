<?php

namespace App\Providers;

use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Repositories\Eloquent\SellerRepository;
use App\Services\Contracts\SellerServiceInterface;
use App\Services\Implementation\SellerService;
use App\Services\Contracts\SaleServiceInterface;
use App\Services\Implementation\SaleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registrar os repositórios
        $this->app->bind(SellerRepositoryInterface::class,SellerRepository::class);
        $this->app->bind(SaleRepositoryInterface::class,SaleRepository::class);
        // Registrar os serviços
        $this->app->bind(\App\Services\Contracts\AuthServiceInterface::class, \App\Services\Implementation\AuthService::class);
        $this->app->bind(\App\Services\Contracts\UserServiceInterface::class, \App\Services\Implementation\UserService::class);
        $this->app->bind(\App\Services\Contracts\SellerServiceInterface::class, \App\Services\Implementation\SellerService::class);
        $this->app->bind(\App\Services\Contracts\SaleServiceInterface::class, \App\Services\Implementation\SaleService::class);
        $this->app->bind(\App\Services\Contracts\ReportServiceInterface::class, \App\Services\Implementation\ReportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}