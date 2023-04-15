<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    public $timestamps = true;
    public $fillable = [
        'cart_id',
        'product_id',
        'quantity'
    ];
    public $cart_id;
    public $product_id;
    public $quantity;
}
