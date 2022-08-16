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
            'item_gambar' => 'mikrotik.jpg',
            'item_kondisi' => 'Kondisi barang dalam keadaan bagus',
            'item_status' => 'Tersedia',
            'item_spesifikasi' => 'Mempunyai 6 colokan port, melaju dengan kecepatan luar biasa',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('items')->insert([
            'item_lokasi' => 2,
            'item_nama' => 'Router',
            'item_jumlah' => 10,
            'item_gambar' => 'router.jpg',
            'item_kondisi' => 'Kondisi barang dalam keadaan bagus',
            'item_status' => 'Tersedia',
            'item_spesifikasi' => 'Mempunyai 6 colokan port, melaju dengan kecepatan luar biasa',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('items')->insert([
            'item_lokasi' => 3,
            'item_nama' => 'Switch',
            'item_jumlah' => 10,
            'item_gambar' => 'switch.jpg',
            'item_kondisi' => 'Kondisi barang dalam keadaan bagus',
            'item_status' => 'Tersedia',
            'item_spesifikasi' => 'Mempunyai 6 colokan port, melaju dengan kecepatan luar biasa',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
