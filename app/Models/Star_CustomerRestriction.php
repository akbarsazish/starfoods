<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_CustomerRestriction extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_customerRestriction';
    protected $primaryKey='id';
}
