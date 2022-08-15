<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BorrowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('borrowers')->insert([
            'peminjam_no_identitas' => 1967896567899,
            'peminjam_nama' => 'Rizki Alfian',
            'peminjam_jurusan' => 1,
            'peminjam_email' => 'rizkialfian@gmail.com',
            'peminjam_no_wa' => 89670198915,
            'peminjam_status' => 'Mahasiswa Aktif'
        ]);
        DB::table('borrowers')->insert([
            'peminjam_no_identitas' => 1967896567899,
            'peminjam_nama' => 'Saka Pangestu',
            'peminjam_jurusan' => 2,
            'peminjam_email' => 'sakapangestu@gmail.com',
            'peminjam_no_wa' => 89670198915,
            'peminjam_status' => 'Mahasiswa Aktif'
        ]);
    }
}