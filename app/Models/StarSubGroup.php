<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarSubGroup extends Model
{
    protected $table = 'Star_Group_DEF';
    protected $primaryKey = 'id';
    public $timestamps = false;
  protected  $fillable=[
    'id','title','show_hide','created_date','fatherGroupId','selfGroupId','percentTakhf'];
    use HasFactory;
}
