<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $products = [];

        // for ($i = 1; $i <= 400; $i++) {
        //     $products[] = [
        //         'product_no' => 'PR' . rand(100000, 999999),
        //         'product_date' => '2025-04-18',
        //         'product_image' => 'avtar.png',
        //         'code' => null,
        //         'name' => 'Product ' . $i,
        //         'product_type_id' => 2,
        //         'color_id' => 7,
        //         'price' => 120+$i,
        //         'supplier_id' => 6,
        //         'warranty' => null,
        //         'quantity' => null,
        //         'category' => 1,
        //         'unit' => 1,
        //         'create_by' => null,
        //         'custom_field' => null,
        //         'soft_delete' => 0,
        //         'branch_id' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ];
        // }

        // DB::table('tbl_products')->insert($products);

    }
}
