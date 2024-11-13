<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExhibitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'categories_id' => 1,
            'conditions_id' => 1,
            'name' => '腕時計',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'brand_name' =>'',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 2,
            'conditions_id' => 2,
            'name' => 'HDD',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'brand_name' => '',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 3,
            'conditions_id' => 3,
            'name' => '玉ねぎ3束',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'brand_name' => '',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 4,
            'conditions_id' => 4,
            'name' => '革靴',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'brand_name' => '',
            'price' => 4000,
            'description' => '新クラシックなデザインの革靴',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 5,
            'conditions_id' => 1,
            'name' => 'ノートPC',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'brand_name' => '',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 6,
            'conditions_id' => 2,
            'name' => 'マイク',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'brand_name' => '',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 7,
            'conditions_id' => 3,
            'name' => 'ショルダーバッグ',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'brand_name' => '',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 8,
            'conditions_id' => 4,
            'name' => 'タンブラー',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'brand_name' => '',
            'price' => 500,
            'description' => '使いやすいタンブラー',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 9,
            'conditions_id' => 1,
            'name' => 'コーヒーミル',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'brand_name' => '',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
        ];
        DB::table('exhibitions')->insert($param);

        $param = [
            'categories_id' => 10,
            'conditions_id' => 2,
            'name' => 'メイクセット',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'brand_name' => '',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
        ];
        DB::table('exhibitions')->insert($param);



    }
}
