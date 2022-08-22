<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;

    protected $guarded = ['peminjam_id'];

    protected $primaryKey = 'peminjam_id';

    public function getRouteKeyName()
    {
        return 'peminjam_id';
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'peminjam_jurusan', 'jurusan_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaksi_peminjam', 'peminjam_id');
    }
}
