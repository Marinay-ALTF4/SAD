<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\OrderItemModel;


class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'customer_name',
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
     * Get recent orders with items
     */
    public function getRecentOrders(int $limit = 5): array
    {
        $orders = $this->orderBy('order_date', 'DESC')
            ->limit($limit)
            ->findAll();

        return $this->attachItems($orders);
    }

    public function attachItems(array $orders): array
    {
        if (empty($orders)) {
            return $orders;
        }

        $orderIds = array_column($orders, 'id');
        $itemModel = new OrderItemModel();
        $items = $itemModel->getByOrderIds($orderIds);

        $grouped = [];
        foreach ($items as $item) {
            $grouped[$item['order_id']][] = [
                'name'     => $item['item_name'],
                'price'    => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
            ];
        }

        foreach ($orders as &$order) {
            $order['items'] = $grouped[$order['id']] ?? [];
        }

        return $orders;
    }

    public function sumBetweenDates(string $startDate, string $endDate): float
    {
        $result = $this->selectSum('total')
            ->where('status', 'Completed')
            ->where('order_date >=', $startDate . ' 00:00:00')
            ->where('order_date <=', $endDate . ' 23:59:59')
            ->first();

        return (float) ($result['total'] ?? 0);
    }

    public function countOrdersBetweenDates(string $startDate, string $endDate): int
    {
        return $this->where('order_date >=', $startDate . ' 00:00:00')
            ->where('order_date <=', $endDate . ' 23:59:59')
            ->countAllResults();
    }

    public function getDailySalesSeries(int $days = 7): array
    {
        $labels = [];
        $data   = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('M d', strtotime($date));
            $data[]   = $this->totalSalesByDate($date);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getMonthlySalesSeries(int $months = 6): array
    {
        $labels = [];
        $data   = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthDate = date('Y-m-01', strtotime("-{$i} months"));
            $start = date('Y-m-01', strtotime($monthDate));
            $end   = date('Y-m-t', strtotime($monthDate));

            $labels[] = date('M Y', strtotime($monthDate));
            $data[]   = $this->sumBetweenDates($start, $end);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getTopItems(int $limit = 5): array
    {
        $db = \Config\Database::connect();

        return $db->table('order_items')
            ->select('order_items.item_name AS name, SUM(order_items.quantity) AS quantity, SUM(order_items.price * order_items.quantity) AS revenue')
            ->join('orders', 'orders.id = order_items.order_id', 'inner')
            ->where('orders.status', 'Completed')
            ->groupBy('order_items.item_name')
            ->orderBy('revenue', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
