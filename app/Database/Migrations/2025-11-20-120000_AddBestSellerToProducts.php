<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBestSellerToProducts extends Migration
{
    public function up()
    {
        $fields = [
            'best_seller' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
            ],
        ];

        $this->forge->addColumn('products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'best_seller');
    }
}

