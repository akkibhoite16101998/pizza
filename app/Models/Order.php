<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public function order_items()
    {
        return $this->hasMany(Order_items::class, 'order_id', 'id');
    }
}
