<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'user_id',
        'quantity',
        'price',
        'total_price'
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
