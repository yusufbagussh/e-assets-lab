<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['detail_transaksi_id'];

    protected $primaryKey = 'detail_transaksi_id';

    public function getRouteKeyName()
    {
        return 'detail_transaksi_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'detail_transaksi_user', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'detail_transaksi_id', 'transaksi_id');
    }
}
