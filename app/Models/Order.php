<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_info_id',
        'reference_number',
        'total_amount',
        'status',
    ];

    public function orderProduct()
    {
        return $this->hasMany(orderProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

}
