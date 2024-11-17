<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'exhibition_id' => 1,
            'category_id' => 1,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 1,
            'category_id' => 2,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 2,
            'category_id' => 3,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 2,
            'category_id' => 4,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 3,
            'category_id' => 5,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 3,
            'category_id' => 6,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 4,
            'category_id' => 7,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 4,
            'category_id' => 8,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 5,
            'category_id' => 9,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 5,
            'category_id' => 10,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 6,
            'category_id' => 11,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 6,
            'category_id' => 12,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 7,
            'category_id' => 13,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 7,
            'category_id' => 14,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 8,
            'category_id' => 1,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 8,
            'category_id' => 3,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 9,
            'category_id' => 2,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 9,
            'category_id' => 4,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 10,
            'category_id' => 6,
        ];
        DB::table('products')->insert($param);

        $param = [
            'exhibition_id' => 10,
            'category_id' => 8,
        ];
        DB::table('products')->insert($param);
    }
}
