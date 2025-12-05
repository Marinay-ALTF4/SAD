<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Reports extends BaseController
{
    public function index()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. This page is only available for administrators.');
        }

        $orders = new OrderModel();

        $today        = date('Y-m-d');
        $startOfWeek  = date('Y-m-d', strtotime('monday this week'));
        $startOfMonth = date('Y-m-01');
        $endOfMonth   = date('Y-m-t');

        $weeklySales      = $orders->sumBetweenDates($startOfWeek, $today);
        $monthlySales     = $orders->sumBetweenDates($startOfMonth, $endOfMonth);
        $previousWeekFrom = date('Y-m-d', strtotime($startOfWeek . ' -7 days'));
        $previousWeekTo   = date('Y-m-d', strtotime($startOfWeek . ' -1 day'));
        $previousWeekSales = $orders->sumBetweenDates($previousWeekFrom, $previousWeekTo);

        $dailySeries   = $orders->getDailySalesSeries(7);
        $monthlySeries = $orders->getMonthlySalesSeries(6);
        $topItems      = $orders->getTopItems(5);

        $data = [
            'total_sales_today' => $orders->totalSalesByDate($today),
            'weekly_sales'      => $weeklySales,
            'monthly_sales'     => $monthlySales,
            'total_orders'      => $orders->countAll(),
            'pending_orders'    => $orders->countOrdersByStatus('Pending'),
            'completed_orders'  => $orders->countOrdersByStatus('Completed'),
            'weekly_orders'     => $orders->countOrdersBetweenDates($startOfWeek, $today),
            'monthly_orders'    => $orders->countOrdersBetweenDates($startOfMonth, $endOfMonth),
            'recent_orders'     => $orders->getRecentOrders(5),
            'daily_sales'       => $dailySeries,
            'monthly_sales_chart' => $monthlySeries,
            'top_items'         => $topItems,
            'recommendation'    => $this->buildRecommendation($weeklySales, $previousWeekSales, $topItems),
        ];

        return view('Dashboard/Reports', $data);
    }

    private function buildRecommendation(float $currentWeek, float $previousWeek, array $topItems): string
    {
        $trend = $previousWeek > 0
            ? (($currentWeek - $previousWeek) / $previousWeek) * 100
            : 100;

        $leader = $topItems[0]['name'] ?? null;

        if ($trend >= 5) {
            $message = 'Great job! Weekly sales are up ' . number_format($trend, 1) . '%. Keep pushing your best-sellers';
        } elseif ($trend <= -5) {
            $message = 'Weekly sales dipped ' . number_format(abs($trend), 1) . '%. Consider promoting bundles or spotlighting popular drinks during rush hours.';
        } else {
            $message = 'Weekly sales are steady. Test limited promos to spark additional growth.';
        }

        if ($leader) {
            $message .= " Top pick: {$leader}. Try upselling it with pastries or loyalty perks.";
        }

        return $message;
    }
}
