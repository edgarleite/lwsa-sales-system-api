<?php

namespace App\Repositories\Contracts;

interface SaleRepositoryInterface
{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getBySeller(int $sellerId);
    public function getDailySales(string $date = null);
    public function paginate(int $perPage = 15);
}