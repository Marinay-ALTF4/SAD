<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Orders extends BaseController
{
    public function index()
    {
        $model = new OrderModel();
        $data['orders'] = $model->orderBy('order_date', 'DESC')->findAll();

        return view('Dashboard/Orders', $data);
    }

    public function add()
    {
        return view('orders/add');
    }

    public function store()
    {
        $model = new OrderModel();

        $data = [
            'customer_name' => $this->request->getPost('customer_name'),
            'order_items'   => $this->request->getPost('order_items'),
            'total'         => $this->request->getPost('total'),
            'status'        => $this->request->getPost('status'),
            'order_date'    => date('Y-m-d H:i:s'),
        ];

        $model->save($data);

        return redirect()->to('/orders')->with('success', 'Order Added!');
    }

    public function edit($id)
    {
        $model = new OrderModel();
        $data['order'] = $model->find($id);

        return view('orders/edit', $data);
    }

    public function update($id)
    {
        $model = new OrderModel();

        $data = [
            'customer_name' => $this->request->getPost('customer_name'),
            'order_items'   => $this->request->getPost('order_items'),
            'total'         => $this->request->getPost('total'),
            'status'        => $this->request->getPost('status'),
        ];

        $model->update($id, $data);

        return redirect()->to('/orders')->with('success', 'Order Updated!');
    }

    public function delete($id)
    {
        $model = new OrderModel();
        $model->delete($id);

        return redirect()->to('/orders')->with('success', 'Order Deleted!');
    }

    public function markComplete($id)
    {
        $model = new OrderModel();
        $model->update($id, ['status' => 'Completed']);

        return redirect()->to('/orders')->with('success', 'Order marked as Completed!');
    }
}
