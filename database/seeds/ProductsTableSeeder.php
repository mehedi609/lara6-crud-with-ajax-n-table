<?php

use App\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Man Formal Shirt', 'price' => 1200.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Women Shari', 'price' => 3500.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Wooden Chair', 'price' => 5000.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        Product::insert($data);
    }
}
