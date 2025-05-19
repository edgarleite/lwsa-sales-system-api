<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearSalesCache extends Command
{
    protected $signature = 'cache:clear-sales';
    protected $description = 'Limpa o cache específico do sistema de vendas';

    public function handle()
    {
        Cache::forget('sellers.all');
        Cache::forget('sales.all');
        
        // Limpa cache de vendedores individuais
        $prefix = config('cache.prefix');
        $keys = Cache::getRedis()->keys("{$prefix}:sellers.*");
        foreach ($keys as $key) {
            Cache::forget(str_replace("{$prefix}:", '', $key));
        }
        
        $this->info('Cache do sistema de vendas limpo com sucesso!');
    }
}