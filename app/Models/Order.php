<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderID';

    protected $fillable = [
        'CustomerID',
        'EmployeeID',
        'OrderDate',
    ];

    protected $casts = [
        'OrderDate' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID', 'EmployeeID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'OrderID', 'OrderID');
    }

    public function totalAmount(): float
    {
        return $this->orderDetails
            ->map(fn ($detail) => $detail->Quantity * $detail->product->Price)
            ->sum();
    }
}
