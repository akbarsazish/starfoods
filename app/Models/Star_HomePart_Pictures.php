<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_HomePart_Pictures extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_homepart_pictures';
    protected $primaryKey='id';
}
