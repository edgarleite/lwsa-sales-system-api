<?php

namespace App\Repositories\Eloquent;

use App\Models\Sale;
use App\Repositories\Contracts\SaleRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SaleRepository implements SaleRepositoryInterface
{
    protected $model;

    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return Cache::remember('sales.all', now()->addHours(1), function () {
            return $this->model->with('seller')->get();
        });
    }

    public function find(int $id)
    {
        return $this->model->with('seller')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $sale = $this->find($id);
        $sale->update($data);
        return $sale;
    }

    public function delete(int $id)
    {
        $sale = $this->find($id);
        return $sale->delete();
    }

    public function getBySeller(int $sellerId)
    {
        return Cache::remember("sales.seller.{$sellerId}", now()->addMinutes(30), function () use ($sellerId) {
            return $this->model->where('seller_id', $sellerId)->with('seller')->get();
        });
    }

    public function getDailySales(string $date = null)
    {
        $date = $date ?: now()->format('Y-m-d');
        
        return $this->model->whereDate('sale_date', $date)
            ->with('seller')
            ->get();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->with('seller')->paginate($perPage);
    }
}