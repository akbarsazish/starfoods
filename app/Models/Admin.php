<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'admin';
    protected $primaryKey='id'; 
}
