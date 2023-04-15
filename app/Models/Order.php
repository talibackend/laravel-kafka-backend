<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cart;

class Order extends Model
{
    use HasFactory;
    public $timestamps = true;
    public $fillable = [
        'user_id',
        'cart_id',
        'total'
    ];
    public $user_id;
    public $cart_id;
    public $total;
}
