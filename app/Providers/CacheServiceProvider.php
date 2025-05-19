<?php

namespace App\Providers;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Limpa cache quando um vendedor é modificado
        Seller::updated(function ($seller) {
            Cache::forget("sellers.{$seller->id}");
            Cache::forget('sellers.all');
        });

        // Limpa cache quando uma venda é modificada
        Sale::updated(function ($sale) {
            Cache::forget("sellers.{$sale->seller_id}");
            Cache::forget('sellers.all');
            Cache::forget("sales.{$sale->id}");
            Cache::forget('sales.all');
        });
    }
}