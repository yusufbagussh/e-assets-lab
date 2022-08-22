<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $fillable = ['user_nama', 'user_email', 'password', 'user_role'];
    protected $hidden = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'user_role', 'role_id');
    }

    public function transactionsCreated()
    {
        return $this->hasMany(Transaction::class, 'created_by', 'id');
    }

    public function transactionsUpdated()
    {
        return $this->hasMany(Transaction::class, 'updated_by', 'id');
    }

    // public function detailTransactions()
    // {
    //     return $this->hasMany(DetailTransaction::class, 'detail_transactions', 'id');
    // }
}
