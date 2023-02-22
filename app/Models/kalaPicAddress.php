<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kalaPicAddress extends Model
{
    protected $table = 'starPicAddress';
    protected $primaryKey = 'picId';
    public $timestamps = false;
    use HasFactory;
}
