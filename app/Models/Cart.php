<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use App\Model\CartItem;

class Cart extends Model
{
    use HasFactory;
    public $timestamps = true;
    public $user_id;
    public $status;

}
