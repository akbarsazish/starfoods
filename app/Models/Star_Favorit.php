<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_Favorit extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_Favorite';
    protected $primaryKey='id';
}
