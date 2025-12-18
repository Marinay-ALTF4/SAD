<?php

namespace App\Controllers;
use App\Models\ExpenseModel;

class Expenses extends BaseController
{
    protected ExpenseModel $expenses;

    private array $categories = [
        'Ingredients',
        'Utilities',
        'Rent',
        'Salaries',
        'Maintenance',
        'Marketing',
        'Miscellaneous',
    ];

    public function __construct()
    {
        $this->expenses = new ExpenseModel();
    }

    public function index()
    {
        $records = $this->expenses->orderBy('date', 'DESC')->findAll();
        $total   = array_reduce($records, static fn ($carry, $row) => $carry + (float) $row['amount'], 0);

        $data = [
            'expenses'   => $records,
            'categories' => $this->categories,
            'stats'      => [
                'today'  => $this->expenses->sumForDate(date('Y-m-d')),
                'month'  => $this->expenses->sumForMonth(date('Y-m')),
                'count'  => count($records),
                'total'  => $total,
            ],
        ];

        return view('Dashboard/Expenses', $data);
    }

    public function store()
    {
        [$payload, $error] = $this->extractPayload();

        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $this->expenses->insert($payload);

        return redirect()->to('/expenses')->with('success', 'Expense recorded.');
    }

    public function update($id)
    {
        $expense = $this->expenses->find($id);

        if (! $expense) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Expense not found.');
        }

        [$payload, $error] = $this->extractPayload();

        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $this->expenses->update($id, $payload);

        return redirect()->to('/expenses')->with('success', 'Expense updated.');
    }

    public function delete($id)
    {
        $this->expenses->delete($id);

        return redirect()->to('/expenses')->with('success', 'Expense removed.');
    }

    private function extractPayload(): array
    {
        $date        = $this->request->getPost('date');
        $category    = $this->request->getPost('category');
        $description = trim((string) $this->request->getPost('description'));
        $amount      = (float) $this->request->getPost('amount');

        if (! $date) {
            return [null, 'Please select a date.'];
        }

        if (! in_array($category, $this->categories, true)) {
            return [null, 'Choose a valid category.'];
        }

        if ($description === '') {
            return [null, 'Description is required.'];
        }

        if ($amount <= 0) {
            return [null, 'Amount must be greater than zero.'];
        }

        return [[
            'date'        => $date,
            'category'    => $category,
            'description' => $description,
            'amount'      => round($amount, 2),
        ], null];
    }
}
