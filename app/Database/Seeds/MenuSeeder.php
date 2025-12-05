<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Menu categories configuration
        // This seeder can be used to seed menu items with categories if needed
        // Categories are now defined in the Product controller
        
        $categories = [
            'Coffee Series',
            'Non-Coffee',
            'Milktea',
            'Coffee Frappes',
            'Matcha Series',
            'Fruitmilk Series',
            'Yakult Series',
            'Coke Float',
            'Hot Coffee',
            'Fruit Soda Series',
            'Snacks Series',
        ];

        // If you want to seed sample products with these categories, uncomment and modify:
        /*
        $sampleProducts = [
            [
                'name' => 'Sample Product',
                'category' => 'coffee-series',
                'price' => 100.00,
                'status' => 'Available',
                'best_seller' => 0,
            ],
        ];

        $this->db->table('products')->insertBatch($sampleProducts);
        */
    }
}

