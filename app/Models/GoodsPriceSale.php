<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsPriceSale extends Model
{
    protected $connection = "sqlsrv1";
    use HasFactory;
    protected $table = 'GoodPriceSale';
    protected $primaryKey='SnGoodPriceSale'; 
}
