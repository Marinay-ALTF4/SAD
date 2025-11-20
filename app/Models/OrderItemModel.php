<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'item_name', 'price', 'quantity'];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    public function getByOrderIds(array $orderIds): array
    {
        if (empty($orderIds)) {
            return [];
        }

        return $this->whereIn('order_id', $orderIds)
            ->orderBy('order_id', 'ASC')
            ->findAll();
    }
}

