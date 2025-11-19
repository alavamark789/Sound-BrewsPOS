<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'EmployeeID';

    protected $fillable = [
        'Name',
        'Role',
        'Email',
        'Password',
    ];

    protected $hidden = [
        'Password',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'EmployeeID', 'EmployeeID');
    }
}
