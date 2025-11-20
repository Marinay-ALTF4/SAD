<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    private array $categories = [
        'signature-coffee' => 'Signature Coffee',
        'iced-coffee'      => 'Iced & Cold Brew',
        'non-coffee'       => 'Tea & Non-Coffee',
        'pastries'         => 'Artisan Pastries',
        'savory'           => 'Savory Bites',
    ];

    private array $statuses = ['Available', 'Out of Stock'];

    public function index()
    {
        $model    = new ProductModel();
        $products = $model->orderBy('category', 'ASC')->orderBy('name', 'ASC')->findAll();

        $data = [
            'products'        => $products,
            'bestSellers'     => $model->getBestSellers(),
            'categories'      => $this->categories,
            'statusOptions'   => $this->statuses,
            'stats'           => [
                'available' => $model->countByStatus('Available'),
                'out'       => $model->countByStatus('Out of Stock'),
                'total'     => count($products),
            ],
        ];

        return view('Dashboard/Product', $data);
    }

    public function store()
    {
        [$data, $error] = $this->extractPayload();

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $model = new ProductModel();
        $model->insert($data);

        return redirect()->to('/product')->with('success', 'Product added to the menu.');
    }

    public function update($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Product not found.');
        }

        [$data, $error] = $this->extractPayload();

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $model->update($id, $data);

        return redirect()->to('/product')->with('success', 'Product updated.');
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $model->delete($id);

        return redirect()->to('/product')->with('success', 'Product removed.');
    }

    private function extractPayload(): array
    {
        $name = trim((string) $this->request->getPost('name'));
        $category = $this->request->getPost('category');
        $status = $this->request->getPost('status');
        $price = (float) $this->request->getPost('price');
        $bestSeller = $this->request->getPost('best_seller') === '1';

        if ($name === '') {
            return [null, 'Product name is required.'];
        }

        if (! array_key_exists($category, $this->categories)) {
            return [null, 'Pick a valid category.'];
        }

        if (! in_array($status, $this->statuses, true)) {
            return [null, 'Pick a valid availability status.'];
        }

        if ($price <= 0) {
            return [null, 'Price must be greater than zero.'];
        }

        return [[
            'name'        => $name,
            'category'    => $category,
            'status'      => $status,
            'price'       => round($price, 2),
            'best_seller' => $bestSeller ? 1 : 0,
        ], null];
    }
}
