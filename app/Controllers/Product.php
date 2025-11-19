<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    public function index()
    {
        $model = new ProductModel();
        $data['products'] = $model->findAll();

        return view('Dashboard/Product', $data);
    }

    public function add()
    {
        return view('product/add');
    }

    public function store()
    {
        $model = new ProductModel();

        $data = [
            'name'     => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'price'    => $this->request->getPost('price'),
            'status'   => $this->request->getPost('status'),
        ];

        $model->save($data);

        return redirect()->to('/product')->with('success', 'Product Added!');
    }

    public function edit($id)
    {
        $model = new ProductModel();
        $data['product'] = $model->find($id);

        return view('product/edit', $data);
    }

    public function update($id)
    {
        $model = new ProductModel();

        $data = [
            'name'     => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'price'    => $this->request->getPost('price'),
            'status'   => $this->request->getPost('status'),
        ];

        $model->update($id, $data);

        return redirect()->to('/product')->with('success', 'Product Updated!');
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $model->delete($id);

        return redirect()->to('/product')->with('success', 'Product Deleted!');
    }
}
