<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            'item_lokasi' => 1,
            'item_nama' => 'Mikrotik',
            'item_jumlah' => 10,
            'item_gambar' => 'item-image/default.png',
            'item_kondisi' => 'Kondisi barang dalam keadaan bagus',
            'item_spesifikasi' => 'Kondisi barang dalam keadaan bagus',
            'item_status' => 'Tersedia',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('items')->insert([
            'item_lokasi' => 2,
            'item_nama' => 'Router',
            'item_jumlah' => 10,
            'item_gambar' => 'item-image/default.png',
            'item_kondisi' => 'Kondisi barang dalam keadaan bagus',
            'item_spesifikasi' => 'Kondisi barang dalam keadaan bagus',
            'item_status' => 'Tersedia',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('items')->insert([
            'item_lokasi' => 3,
            'item_nama' => 'switch',
            'item_jumlah' => 10,
            'item_gambar' => 'item-image/default.png',
            'item_kondisi' => 'Kondisi barang dalam keadaan bagus',
            'item_spesifikasi' => 'Kondisi barang dalam keadaan bagus',
            'item_status' => 'Tersedia',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
