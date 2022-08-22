<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['transaksi_id'];

    protected $primaryKey = 'transaksi_id';

    public function getRouteKeyName()
    {
        return 'transaksi_id';
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'transaksi_peminjam', 'peminjam_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'transaksi_item', 'item_id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // public function detailTransactions()
    // {
    //     return $this->hasMany(DetailTransaction::class, 'detail_transaksi_id', 'transaksi_id');
    // }
}
