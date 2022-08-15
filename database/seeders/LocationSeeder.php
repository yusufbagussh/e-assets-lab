<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('locations')->insert([
            'lokasi_nama' => 'Lemari A',
        ]);
        DB::table('locations')->insert([
            'lokasi_nama' => 'Lemari B',
        ]);
        DB::table('locations')->insert([
            'lokasi_nama' => 'Lemari C',
        ]);
    }
}
