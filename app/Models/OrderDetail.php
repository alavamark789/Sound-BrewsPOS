<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderDetailID';

    protected $fillable = [
        'OrderID',
        'ProductID',
        'Quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }
}
