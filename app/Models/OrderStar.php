<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStar extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'orderStar';
    protected $primaryKey='SnOrderBYS'; 
}
