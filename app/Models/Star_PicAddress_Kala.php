<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_PicAddress_Kala extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'starPicAddress';
    protected $primaryKey='id';
}
