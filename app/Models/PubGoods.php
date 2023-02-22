<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PubGoods extends Model
{
    protected $connection = "sqlsrv1";
    use HasFactory;
    protected $table = 'PubGoods';
    protected $primaryKey='GoodSn'; 
}
