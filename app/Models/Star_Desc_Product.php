<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_Desc_Product extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_desc_product';
    protected $primaryKey='id';
}
