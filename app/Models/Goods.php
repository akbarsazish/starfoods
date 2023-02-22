<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'PubGoods';
    protected $primaryKey = 'GoodSn';
    public $timestamps = false;
    protected $fillable = [
    'id','title','show_hide','created_date','fatherGroupId','selfGroupId',
        'TechnicalInfo','OtherInfo','DefaultUnit','Status','Height','Width',
        'length	Color','BarCode','FirstExistans','FiscalYear','IsService','SaleAmnt4Porsant',
        'SaleMinPercent','SaleMaxPercent','ReSaleAmnt4Porsant','ReSaleMinPercent','ReSaleMaxPercent',
        'IsActive','ReOrderExists','Calc3PercentMaliat','Weigh','HavalehTypeRialy','SnGoodZarf',
        'ZarfiyatZarf','TimeZemanatMon','TimeZemanatdDay','TimeTasviyehMon','TimeTasviyehDay','ShoseInfo',
        'DateReOrder','IsSerialNo','IsExport','PercentCharbi','BuyPrice','GoodExistsAlarm','TakhFifPayanMahFew',
        'TakhFifPayanMahPercent','CreateWithHand','VolumeGood','SnBrand','OjratSakht','ColorGood','Add_Update',
        'Ayar','PriceMolhaghat','OjratKol','PriceMolhaghat2','KashiOrSeramik','TarhKashiOrSeramik',
        'RangKashiOrSeramik','DargeKashiOrSeramik','SizeKashiOrSeramik','KarkhanehKashiOrSeramik','SnGroupTablet',
        'SnKhodroType','OjratKol_Buy','OjratSakhtNoghreh_Buy','PriceMolhaghat_Buy','PriceMolhaghat2_Buy','OjratKol_Nam','OjratSakhtNoghreh_Nam',
        'PriceMolhaghat_Nam','PriceMolhaghat2_Nam','LastBuyPrice','LastDateUpdate','FiAfterTakhfif','FiAfterAmel',
        'DateLastBuyPrice','SnSellerLastBuyPrice','Price','Price2','Price3','Price4','Price5','Price6',
        'Price7','Price8','Price9','Price10','IsFalleh','BeforeInterchange','AfterInterchange','Name_YearCreate','DarsadVazni_Buy',
        'OjratTypeCust','OjratTypeNamayandegi','OjratTypeBuy','PercentOjrat_Cust','PercentOjrat_Namayandegi','PercentOjrat_Buy','IsPackingType','SnPackingGood'
                            ];
    use HasFactory;
}
