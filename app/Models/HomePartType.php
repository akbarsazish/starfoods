<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePartType extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'star_HomePart_Type';
    protected $primaryKey='id';
    public function homePart()
    {
       return $this->hasMany(App\Models\HomePart::class);
    }
}
