<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'test1',
            'email' => 'test1@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('coachtech1106')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'test2',
            'email' => 'test2@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('coachtech1106')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'test3',
            'email' => 'test3@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('coachtech1106')
        ];
        DB::table('users')->insert($param);
    }
}
