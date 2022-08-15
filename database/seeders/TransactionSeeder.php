<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            'transaksi_item' => 1,
            'transaksi_peminjam' => 1,
            'transaksi_tgl_pinjam' => Carbon::now()->format('Y-m-d'),            
            'transaksi_tgl_kembali' => Carbon::now()->format('Y-m-d'),            
        ]);
        DB::table('transactions')->insert([
            'transaksi_item' => 2,
            'transaksi_peminjam' => 2,
            'transaksi_tgl_pinjam' => Carbon::now()->format('Y-m-d'),            
            'transaksi_tgl_kembali' => Carbon::now()->format('Y-m-d'),            
        ]);
    }
}
