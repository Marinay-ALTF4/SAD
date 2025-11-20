<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
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

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items', true);

        $db = \Config\Database::connect();

        if ($db->fieldExists('items', 'orders')) {
            $orders = $db->table('orders')->select('id, items')->get()->getResultArray();
            $itemsBuilder = $db->table('order_items');

            foreach ($orders as $order) {
                if (empty($order['items'])) {
                    continue;
                }

                $items = json_decode($order['items'], true);
                if (! is_array($items)) {
                    continue;
                }

                foreach ($items as $item) {
                    $itemsBuilder->insert([
                        'order_id'  => $order['id'],
                        'item_name' => $item['name'] ?? 'Item',
                        'price'     => $item['price'] ?? 0,
                        'quantity'  => $item['quantity'] ?? 1,
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s'),
                    ]);
                }
            }

            $this->forge->dropColumn('orders', 'items');
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        if (! $db->fieldExists('items', 'orders')) {
            $fields = [
                'items' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ];
            $this->forge->addColumn('orders', $fields);

            $items = $db->table('order_items')->get()->getResultArray();
            $grouped = [];
            foreach ($items as $item) {
                $grouped[$item['order_id']][] = [
                    'name'     => $item['item_name'],
                    'price'    => (float) $item['price'],
                    'quantity' => (int) $item['quantity'],
                ];
            }

            foreach ($grouped as $orderId => $list) {
                $db->table('orders')
                    ->where('id', $orderId)
                    ->update(['items' => json_encode($list)]);
            }
        }

        $this->forge->dropTable('order_items', true);
    }
}

