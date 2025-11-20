<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'price', 'category', 'status', 'best_seller'];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    public function getBestSellers(): array
    {
        return $this->where('best_seller', 1)
            ->orderBy('updated_at', 'DESC')
            ->findAll();
    }

    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }
}
