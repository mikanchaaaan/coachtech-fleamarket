<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'exhibition_id' => 1,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 1,
            'exhibition_id' => 2,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 1,
            'exhibition_id' => 3,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 1,
            'exhibition_id' => 4,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 1,
            'exhibition_id' => 5,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 2,
            'exhibition_id' => 6,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 2,
            'exhibition_id' => 7,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 2,
            'exhibition_id' => 8,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 2,
            'exhibition_id' => 9,
        ];
        DB::table('sales')->insert($param);

        $param = [
            'user_id' => 2,
            'exhibition_id' => 10,
        ];
        DB::table('sales')->insert($param);
    }
}
