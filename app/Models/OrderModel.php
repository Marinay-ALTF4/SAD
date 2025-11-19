<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['customer_name','items','total','status','order_date'];

    /**
     * Total sales today
     */
    public function totalSalesToday()
    {
        $orders = $this->where('DATE(order_date)', date('Y-m-d'))->findAll();
    
        $total = 0;
    
        foreach ($orders as $order) {
            // Assuming items stored as JSON: [{"name":"Latte","price":150,"quantity":2}, ...]
            $items = json_decode($order['items'], true);
            if ($items) {
                foreach ($items as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            }
        }
    
        return $total;
    }
    

    /**
     * Count orders by status
     */
    public function countOrdersByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Get recent orders
     */
    public function getRecentOrders($limit = 5)
    {
        return $this->orderBy('order_date', 'DESC')->limit($limit)->findAll();
    }
}
