<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'ProductName',
        'Price',
        'Stock',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'ProductID', 'ProductID');
    }
}
