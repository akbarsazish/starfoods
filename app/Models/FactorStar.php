<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactorStar extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'FactorStar';
    protected $primaryKey='SnOrder'; 
}
