<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Orders extends BaseController
{
    /**
     * Allowed order statuses
     */
    private array $validStatuses = ['Pending', 'Completed', 'Cancelled'];

    public function index()
    {
        $model  = new OrderModel();
        $orders = $model->orderBy('order_date', 'DESC')->findAll();

        $data = [
            'pendingOrders'   => array_values(array_filter($orders, static fn ($order) => $order['status'] !== 'Completed')),
            'completedOrders' => array_values(array_filter($orders, static fn ($order) => $order['status'] === 'Completed')),
            'stats' => [
                'todayOrders' => $model->countOrdersByDate(),
                'pending'     => $model->countOrdersByStatus('Pending'),
                'completed'   => $model->countOrdersByStatus('Completed'),
            ],
        ];

        return view('Dashboard/Orders', $data);
    }

    public function store()
    {
        $model = new OrderModel();
        $items = $this->normalizeItems($this->request->getPost('items') ?? []);

        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Add at least one order item with price and quantity.');
        }

        $status = $this->sanitizeStatus($this->request->getPost('status'));

        $data = [
            'customer_name' => $this->normalizeCustomerName($this->request->getPost('customer_name')),
            'items'         => json_encode($items),
            'total'         => $this->calculateTotal($items),
            'status'        => $status,
            'order_date'    => date('Y-m-d H:i:s'),
        ];

        $model->insert($data);

        return redirect()->to('/orders')->with('success', 'Order created successfully.');
    }

    public function update($id)
    {
        $model = new OrderModel();
        $order = $model->find($id);

        if (! $order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Order not found');
        }

        $items = $this->normalizeItems($this->request->getPost('items') ?? []);

        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Add at least one order item with price and quantity.');
        }

        $status = $this->sanitizeStatus($this->request->getPost('status'));

        $data = [
            'customer_name' => $this->normalizeCustomerName($this->request->getPost('customer_name')),
            'items'         => json_encode($items),
            'total'         => $this->calculateTotal($items),
            'status'        => $status,
        ];

        $model->update($id, $data);

        return redirect()->to('/orders')->with('success', 'Order updated successfully.');
    }

    public function updateStatus($id)
    {
        $model = new OrderModel();
        $order = $model->find($id);

        if (! $order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Order not found');
        }

        $status = $this->sanitizeStatus($this->request->getPost('status'));

        $model->update($id, ['status' => $status]);

        return redirect()->to('/orders')->with('success', 'Order status updated.');
    }

    public function delete($id)
    {
        $model = new OrderModel();
        $model->delete($id);

        return redirect()->to('/orders')->with('success', 'Order deleted successfully.');
    }

    /**
     * Normalize and filter item payload
     */
    private function normalizeItems(array $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            $name = trim($item['name'] ?? '');
            $price = isset($item['price']) ? (float) $item['price'] : 0;
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;

            if ($name === '' || $price <= 0 || $quantity <= 0) {
                continue;
            }

            $normalized[] = [
                'name'     => $name,
                'price'    => round($price, 2),
                'quantity' => $quantity,
            ];
        }

        return $normalized;
    }

    private function calculateTotal(array $items): float
    {
        $sum = array_reduce($items, static function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return round($sum, 2);
    }

    private function sanitizeStatus(?string $status): string
    {
        return in_array($status, $this->validStatuses, true) ? $status : 'Pending';
    }

    private function normalizeCustomerName(?string $name): string
    {
        $name = trim((string) $name);

        return $name === '' ? 'Walk-in Customer' : $name;
    }
}
