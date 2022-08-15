<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('majors')->insert([
            'jurusan_fakultas' => 'FMIPA',
            'jurusan_nama' => 'Teknik Informatika'
        ]);
        DB::table('majors')->insert([
            'jurusan_fakultas' => 'Teknik',
            'jurusan_nama' => 'Teknik Komputer'
        ]);
        DB::table('majors')->insert([
            'jurusan_fakultas' => 'FKIP',
            'jurusan_nama' => 'Pendidikan Teknik Elektro'
        ]);
    }
}
