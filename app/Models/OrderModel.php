<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'customer_name',
        'items',
        'total',
        'status',
        'order_date',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    /**
     * Sum of completed order totals for a specific date (defaults to today)
     */
    public function totalSalesByDate(string $date = null): float
    {
        $date = $date ?? date('Y-m-d');

        $result = $this->selectSum('total')
            ->where('status', 'Completed')
            ->where('DATE(order_date)', $date)
            ->first();

        return (float) ($result['total'] ?? 0);
    }

    /**
     * Count orders created on a specific date (defaults to today)
     */
    public function countOrdersByDate(string $date = null): int
    {
        $date = $date ?? date('Y-m-d');

        return $this->where('DATE(order_date)', $date)->countAllResults();
    }

    /**
     * Count orders by status
     */
    public function countOrdersByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Count distinct named customers created on a specific date.
     * Walk-in or blank customers are ignored.
     */
    public function countNewCustomersByDate(string $date = null): int
    {
        $date = $date ?? date('Y-m-d');

        return $this->select('customer_name')
            ->where('customer_name !=', '')
            ->where('customer_name !=', 'Walk-in Customer')
            ->where('DATE(order_date)', $date)
            ->distinct()
            ->countAllResults();
    }

    /**
     * Get recent orders
     */
    public function getRecentOrders(int $limit = 5): array
    {
        return $this->orderBy('order_date', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
