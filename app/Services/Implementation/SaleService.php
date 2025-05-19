<?php

namespace App\Services\Implementation;

use App\Models\Sale;
use App\Services\Contracts\SaleServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class SaleService implements SaleServiceInterface
{
    public function paginateSales(int $perPage): LengthAwarePaginator
    {
        return Sale::paginate($perPage);
    }
    
    public function createSale(array $data): Sale
    {
        return Sale::create($data);
    }
    
    public function getSaleById(int $id): Sale
    {
        return Sale::findOrFail($id);
    }
    
    public function updateSale(int $id, array $data): Sale
    {
        $sale = $this->getSaleById($id);
        $sale->update($data);
        return $sale->fresh();
    }
    
    public function deleteSale(int $id): bool
    {
        return $this->getSaleById($id)->delete();
    }
    
    public function getSalesBySeller(int $sellerId): Collection
    {
        return Sale::where('seller_id', $sellerId)->get();
    }
}