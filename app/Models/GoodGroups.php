<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodGroups extends Model
{
     
    protected $table = 'GoodGroups';
    protected $primaryKey = 'GoodGroupSn';
    public $timestamps = false;
    protected $fillable = [
                            'FatherGrpSN',
                            'NameGRP',
                            'TimeStamp',
                            'SalePercentSood',
                            'BuyPercentSood', 
                            'IsService', 
                            'AllowDelete', 
                            'IsExport', 
                            'IsGoodExistsInSite', 
                            'OjratKol_G', 
                            'OjratKol_Buy_G', 
                            'OjratKol_Nam_G', 
                            'OjratSakhtNoghreh_G', 
                            'OjratSakhtNoghreh_Buy_G', 
                            'OjratSakhtNoghreh_Nam_G', 
                            'PriceMolhaghat2_Buy_G', 
                            'PriceMolhaghat2_Nam_G', 
                            'DarsadVazni_Buy_G', 
                            'TypeOjratCust', 
                            'PercentDanehey_Cust', 
                            'PercentTotal_Cust'
                            ];
    use HasFactory;

}
