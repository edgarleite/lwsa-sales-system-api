<?php

namespace App\Services\Contracts;

use App\Models\Sale;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SaleServiceInterface
{
    public function paginateSales(int $perPage): LengthAwarePaginator;
    public function createSale(array $data): Sale;
    public function getSaleById(int $id): Sale;
    public function updateSale(int $id, array $data): Sale;
    public function deleteSale(int $id): bool;
    public function getSalesBySeller(int $sellerId): Collection;
}