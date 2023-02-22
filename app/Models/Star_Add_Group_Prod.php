<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_Add_Group_Prod extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_add_prod_group';
    protected $primaryKey='id'; 
}
