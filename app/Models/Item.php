<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['item_id'];

    protected $primaryKey = 'item_id';

    public function getRouteKeyName()
    {
        return 'item_id';
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'item_lokasi', 'lokasi_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaksi_item', 'item_id');
    }
}
