<?php

namespace App\Repositories\Contracts;

interface SellerRepositoryInterface
{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getWithSales(int $sellerId);
    public function paginate(int $perPage = 15);
}