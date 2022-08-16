<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;
    protected $guarded = ['jurusan_id'];

    protected $primaryKey = 'jurusan_id';

    public function getRouteKeyName()
    {
        return 'jurusan_id';
    }
}
