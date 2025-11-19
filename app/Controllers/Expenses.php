<?php

namespace App\Controllers;
use App\Models\ExpenseModel;

class Expenses extends BaseController
{
    protected $expenseModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
    }

    // List all expenses
    public function index()
    {
        $data['expenses'] = $this->expenseModel->orderBy('date', 'DESC')->findAll();
        $data['total_expenses'] = $this->expenseModel->selectSum('amount')->first()['amount'] ?? 0;
        return view('Dashboard/Expenses', $data);
    }

    // Add a new expense
    public function add()
    {
        $this->expenseModel->save([
            'date' => $this->request->getPost('date'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'amount' => $this->request->getPost('amount'),
        ]);

        return redirect()->to(base_url('expenses'));
    }

    // Edit an expense
    public function edit($id)
    {
        $data['expense'] = $this->expenseModel->find($id);
        return view('Dashboard/Expenses', $data);
    }

    // Update an expense
    public function update($id)
    {
        $this->expenseModel->update($id, [
            'date' => $this->request->getPost('date'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'amount' => $this->request->getPost('amount'),
        ]);

        return redirect()->to(base_url('expenses'));
    }

    // Delete an expense
    public function delete($id)
    {
        $this->expenseModel->delete($id);
        return redirect()->to(base_url('expenses'));
    }
}
