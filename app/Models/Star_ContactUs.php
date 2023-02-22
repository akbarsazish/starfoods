<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star_ContactUs extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_contactUs';
    protected $primaryKey='id';
}
