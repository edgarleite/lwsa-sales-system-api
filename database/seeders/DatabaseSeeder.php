<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Sale;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário admin
        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@empresa.com',
            'password' => bcrypt('123456'),
        ]);

        // Criar vendedores
        Seller::factory()
            ->count(10)
            ->create()
            ->each(function ($seller) {
                // Criar vendas para cada vendedor
                // Algumas vendas de hoje
                Sale::factory()
                    ->count(rand(1, 3))
                    ->today()
                    ->create(['seller_id' => $seller->id]);
                
                // Algumas vendas desta semana
                Sale::factory()
                    ->count(rand(3, 7))
                    ->thisWeek()
                    ->create(['seller_id' => $seller->id]);
                
                // Algumas vendas deste mês
                Sale::factory()
                    ->count(rand(5, 10))
                    ->thisMonth()
                    ->create(['seller_id' => $seller->id]);
            });
    }
}
