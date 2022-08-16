<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_nama' => 'Yusuf Bagus',
            'user_email' => 'yusufbagus@gmail.com',
            'password' => bcrypt('yusuf57552'),
            'user_role' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'user_nama' => 'Dhimas Risang',
            'user_email' => 'dhimasrisang@gmail.com',
            'password' => bcrypt('dhimas57552'),
            'user_role' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
