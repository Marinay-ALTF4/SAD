<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'email', 'password', 'role', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Verify user credentials
     */
    public function verifyUser(string $identity, string $password)
    {
        $user = $this->groupStart()
                ->where('email', $identity)
                ->orWhere('username', $identity)
            ->groupEnd()
            ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}

