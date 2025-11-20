<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Home extends BaseController
{
    public function index(): string
    {
        $orders = new OrderModel();

        $data = [
            'todaySales'   => $orders->totalSalesByDate(),
            'totalOrders'  => $orders->countAll(),
            'newCustomers' => $orders->countNewCustomersByDate(),
            'recentOrders' => $orders->getRecentOrders(),
        ];

        return view('Dashboard/Dashboard', $data);
    }
}
