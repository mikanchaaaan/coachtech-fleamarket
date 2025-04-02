<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressTableSeeder extends Seeder
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
            'postcode' => '153-0064',
            'address' => '東京都目黒区下目黒２丁目２０−２８',
            'building' => 'いちご目黒ビル 4階'
        ];
        DB::table('addresses')->insert($param);

        $param = [
            'user_id' => 2,
            'postcode' => '153-0064',
            'address' => '東京都目黒区下目黒２丁目２０−２８',
            'building' => 'いちご目黒ビル 4階'
        ];
        DB::table('addresses')->insert($param);

        $param = [
            'user_id' => 3,
            'postcode' => '153-0064',
            'address' => '東京都目黒区下目黒２丁目２０−２８',
            'building' => 'いちご目黒ビル 4階'
        ];
        DB::table('addresses')->insert($param);
    }
}
