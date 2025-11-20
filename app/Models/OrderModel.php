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
        $orders = $this->where('status', 'Completed')->findAll();
        $totals = [];

        foreach ($orders as $order) {
            $items = json_decode($order['items'], true) ?? [];
            foreach ($items as $item) {
                if (! isset($item['name'])) {
                    continue;
                }

                $name = $item['name'];
                $qty = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                $price = isset($item['price']) ? (float) $item['price'] : 0;

                if (! isset($totals[$name])) {
                    $totals[$name] = [
                        'name'     => $name,
                        'quantity' => 0,
                        'revenue'  => 0,
                    ];
                }

                $totals[$name]['quantity'] += $qty;
                $totals[$name]['revenue']  += $price * $qty;
            }
        }

        usort($totals, static function ($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        return array_slice(array_values($totals), 0, $limit);
    }
}
