<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'items'        => [
                'type'       => 'TEXT', // JSON string of items
            ],
            'prices'       => [
                'type'       => 'TEXT', // JSON string of prices
            ],
            'status'       => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Completed', 'Cancelled'],
                'default'    => 'Pending',
            ],
            'order_date'   => [
                'type' => 'DATETIME',
            ],
            'created_at'   => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at'   => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
