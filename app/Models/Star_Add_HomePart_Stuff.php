<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_Add_HomePart_Stuff extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_add_homePart_stuff';
    protected $primaryKey='id'; 
}
