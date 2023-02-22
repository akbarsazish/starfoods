<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_AboutUs extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_aboutUs';
    protected $primaryKey='id';
}
