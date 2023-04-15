<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\Product;

class CartItem extends Model
{
    use HasFactory;
    public $timestamps = true;
    public $cart_id;
    public $product_id;
    public $quantity;
}
