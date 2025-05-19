<?php

namespace App\Services\Contracts;

use App\Models\Seller;
use Illuminate\Pagination\LengthAwarePaginator;

interface SellerServiceInterface
{
    public function paginateSellers(int $perPage): LengthAwarePaginator;
    public function createSeller(array $data): Seller;
    public function getSellerById(int $id): Seller;
    public function updateSeller(int $id, array $data): Seller;
    public function deleteSeller(int $id): bool;
    public function getSellerWithSales(int $id): Seller;
}
