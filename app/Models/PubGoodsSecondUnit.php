<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PubGoodsSecondUnit extends Model
{
    protected $connection = "sqlsrv1";
    use HasFactory;
    protected $table = 'GoodUnitSecond';
    protected $primaryKey='SnUnitSecond'; 
}
