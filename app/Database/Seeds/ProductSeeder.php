<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Coffee Series
            ['name' => 'Almond Coffee', 'category' => 'coffee-series', 'price' => 50.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Caramel Coffee', 'category' => 'coffee-series', 'price' => 50.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Spanish Coffee', 'category' => 'coffee-series', 'price' => 50.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'HazelNut Coffee', 'category' => 'coffee-series', 'price' => 50.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Mocha Coffee', 'category' => 'coffee-series', 'price' => 50.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Non-Coffee
            ['name' => 'Choco Latte', 'category' => 'non-coffee', 'price' => 49.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Ube', 'category' => 'non-coffee', 'price' => 49.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Choco Ube', 'category' => 'non-coffee', 'price' => 49.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Chocolate Strawberry', 'category' => 'non-coffee', 'price' => 69.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Dark Chocolate', 'category' => 'non-coffee', 'price' => 69.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Milktea
            ['name' => 'Cookies n Cream', 'category' => 'milktea', 'price' => 39.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Coffee Frappes
            ['name' => 'Vanilla', 'category' => 'coffee-frappes', 'price' => 79.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Oreo', 'category' => 'coffee-frappes', 'price' => 79.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Matcha Series
            ['name' => 'Matcha Latte', 'category' => 'matcha-series', 'price' => 59.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Matcha Frappes', 'category' => 'matcha-series', 'price' => 59.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Strawberry Matcha', 'category' => 'matcha-series', 'price' => 59.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Fruitmilk Series
            ['name' => 'Strawberry', 'category' => 'fruitmilk-series', 'price' => 39.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Blueberry', 'category' => 'fruitmilk-series', 'price' => 39.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Yakult Series
            ['name' => 'Strawberry Yakult', 'category' => 'yakult-series', 'price' => 59.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Mango Yakult', 'category' => 'yakult-series', 'price' => 59.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Blueberry Yakult', 'category' => 'yakult-series', 'price' => 59.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Coke Float
            ['name' => 'Sprite Float', 'category' => 'coke-float', 'price' => 45.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Royal Float', 'category' => 'coke-float', 'price' => 45.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Coke Float', 'category' => 'coke-float', 'price' => 45.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Hot Coffee
            ['name' => 'Coffee Caramel', 'category' => 'hot-coffee', 'price' => 49.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Coffee Spanish', 'category' => 'hot-coffee', 'price' => 49.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Coffee Almond', 'category' => 'hot-coffee', 'price' => 49.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Fruit Soda Series
            ['name' => 'Strawberry', 'category' => 'fruit-soda-series', 'price' => 35.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Apple', 'category' => 'fruit-soda-series', 'price' => 35.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Melon', 'category' => 'fruit-soda-series', 'price' => 35.00, 'status' => 'Available', 'best_seller' => 0],
            
            // Snacks Series
            ['name' => 'Pizza', 'category' => 'snacks-series', 'price' => 120.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Hotdog', 'category' => 'snacks-series', 'price' => 39.00, 'status' => 'Available', 'best_seller' => 0],
            ['name' => 'Fries', 'category' => 'snacks-series', 'price' => 20.00, 'status' => 'Available', 'best_seller' => 0],
        ];

        // Use insertBatch to insert all products
        $this->db->table('products')->insertBatch($products);
    }
}

