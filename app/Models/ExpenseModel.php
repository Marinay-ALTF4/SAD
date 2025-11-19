<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date', 'category', 'description', 'amount'];
    protected $useTimestamps = true; // uses created_at and updated_at
}
