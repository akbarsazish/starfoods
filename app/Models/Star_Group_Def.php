<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_Group_Def extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'Star_Group_Def';
    protected $primaryKey='id';
}
