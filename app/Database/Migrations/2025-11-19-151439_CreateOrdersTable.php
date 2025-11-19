<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        // Check if table already exists, avoid duplicate errors
         {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'customer_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => false,
                ],
                'items' => [
                    'type' => 'TEXT',
                    'null' => false,
                ],
                'total' => [
                    'type'    => 'DECIMAL',
                    'constraint' => '10,2',
                     'null'    => false,
                ],
                'status' => [
                    'type'    => 'ENUM',
                    'constraint' => ['Pending', 'Completed', 'Cancelled'],
                    'default' => 'Pending',
                    'null'    => false,
                ],
                'order_date' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true); // Primary Key
            $this->forge->createTable('orders');
        }
    }

    public function down()
    {
        $this->forge->dropTable('orders', true); // Drop table if exists
    }
}
