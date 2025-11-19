<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'CustomerID';

    protected $fillable = [
        'Name',
        'Email',
        'Phone',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'CustomerID', 'CustomerID');
    }
}
