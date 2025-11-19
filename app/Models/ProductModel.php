<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','price','category'];

    public function getTopProducts($limit = 5)
    {
        return $this->orderBy('price', 'ASC')->limit($limit)->findAll();
    }
}
