<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['name' => 'Mama Gold Rice', 'price' => 76000],
            ['name' => 'Gucci Bag', 'price' => 50000],
            ['name' => 'Nike Jordans', 'price' => 120000],
            ['name' => 'Puma Shirt', 'price' => 1000],
            ['name' => 'Apple Smart Watch', 'price' => 750000],
            ['name' => 'Golden Grillz', 'price' => 92000],
        ]);
    }
}
