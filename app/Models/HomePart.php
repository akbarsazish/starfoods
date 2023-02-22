<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePart extends Model
{
    protected $connection = "sqlsrv";
    use HasFactory;
    protected $table = 'HomePart';
    protected $primaryKey='id'; 
    public function homePartType()
    {
       return $this->belongsTo(App\Models\HomePartType::class);
    }
}
