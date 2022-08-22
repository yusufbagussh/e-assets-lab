<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['role_id'];

    protected $primaryKey = 'role_id';

    public function users()
    {
        return $this->hasMany(User::class, 'user_role', 'role_id');
    }

    public function getRouteKeyName()
    {
        return 'role_id';
    }
}
