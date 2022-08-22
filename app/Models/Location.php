<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $guarded = ['lokasi_id'];

    protected $primaryKey = 'lokasi_id';

    public function getRouteKeyName()
    {
        return 'lokasi_id';
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'item_lokasi', 'lokasi_id');
    }
}
