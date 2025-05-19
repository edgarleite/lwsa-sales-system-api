<?php

namespace App\Repositories\Eloquent;

use App\Models\Seller;
use App\Repositories\Contracts\SellerRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SellerRepository implements SellerRepositoryInterface
{
    protected $model;

    public function __construct(Seller $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return Cache::remember('sellers.all', now()->addHours(2), function () {
            return $this->model->with('sales')->get();
        });
    }

    public function find(int $id)
    {
        return Cache::remember("sellers.{$id}", now()->addHour(), function () use ($id) {
            return $this->model->with('sales')->findOrFail($id);
        });
    }
    public function create(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $seller = $this->find($id);
        
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        
        $seller->update($data);
        return $seller;
    }

    public function delete(int $id)
    {
        $seller = $this->find($id);
        return $seller->delete();
    }

    public function getWithSales(int $sellerId)
    {
        return $this->model->with('sales')->findOrFail($sellerId);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->paginate($perPage);
    }
}