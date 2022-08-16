<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;;
class DetailTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('detail_transactions')->insert([
            'detail_transaksi_id' => 1,
            'detail_transaksi_user' => 2,
            'detail_transaksi_tgl_pengembalian' => Carbon::now()->format('Y-m-d'),
            'detail_transaksi_denda' => 10000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('detail_transactions')->insert([
            'detail_transaksi_id' => 2,
            'detail_transaksi_user' => 2,
            'detail_transaksi_tgl_pengembalian' => Carbon::now()->format('Y-m-d'),
            'detail_transaksi_denda' => 10000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
