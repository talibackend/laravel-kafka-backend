<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cart;

class Order extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'cart_id',
        'total'
    ];
}
