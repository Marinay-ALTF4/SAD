<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date', 'category', 'description', 'amount'];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    public function sumForDate(string $date): float
    {
        $result = $this->selectSum('amount')
            ->where('date', $date)
            ->first();

        return (float) ($result['amount'] ?? 0);
    }

    public function sumForMonth(string $yearMonth): float
    {
        $result = $this->selectSum('amount')
            ->where("DATE_FORMAT(date, '%Y-%m')", $yearMonth)
            ->first();

        return (float) ($result['amount'] ?? 0);
    }

    public function recent(int $limit = 5): array
    {
        return $this->orderBy('date', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
