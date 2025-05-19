<?php

namespace App\Services\Implementation;

use App\Models\Seller;
use App\Services\Contracts\SellerServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class SellerService implements SellerServiceInterface
{
    public function paginateSellers(int $perPage): LengthAwarePaginator
    {
        return Seller::paginate($perPage);
    }
    
    public function createSeller(array $data): Seller
    {
        return Seller::create($data);
    }
    
    public function getSellerById(int $id): Seller
    {
        return Seller::findOrFail($id);
    }
    
    public function updateSeller(int $id, array $data): Seller
    {
        $seller = $this->getSellerById($id);
        $seller->update($data);
        return $seller->fresh();
    }
    
    public function deleteSeller(int $id): bool
    {
        return $this->getSellerById($id)->delete();
    }
    
    public function getSellerWithSales(int $id): Seller
    {
        return Seller::with('sales')->findOrFail($id);
    }
}