<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(LocationSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(MajorSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(BorrowerSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(DetailTransactionSeeder::class);
    }
}
