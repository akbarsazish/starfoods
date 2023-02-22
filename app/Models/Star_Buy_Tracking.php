<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_Buy_Tracking extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_BuyTracking';
    protected $primaryKey='id';
}
