<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;

class Reports extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $productModel = new ProductModel();

        // Summary Cards
        $data['total_sales_today'] = $orderModel->totalSalesToday();
        $data['total_orders'] = $orderModel->countAllResults();
        $data['pending_orders'] = $orderModel->countOrdersByStatus('Pending');
        $data['completed_orders'] = $orderModel->countOrdersByStatus('Completed');
        $data['new_customers'] = 12; // Replace with CustomerModel if available

        // Recent Orders Table
        $data['recent_orders'] = $orderModel->getRecentOrders(5);

        // Top Products Table
        $data['top_products'] = $productModel->getTopProducts(5);

        // Charts Data (example)
        $data['daily_sales_labels'] = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']; // placeholder
        $data['daily_sales_data'] = [300,500,400,600,450,700,650]; // replace with dynamic data

        $data['top_products_labels'] = array_map(fn($p)=>$p['name'], $data['top_products']);
        $data['top_products_data'] = array_map(fn($p)=>$p['total_sold'],$data['top_products']);

        return view('Dashboard/Reports', $data);
    }
}
