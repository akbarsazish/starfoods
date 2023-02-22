<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
class SubGroupFavorit extends Controller{
    public function index(Request $request)
    {
        $overLine=0;
        $callOnSale=0;
        $zeroExistance=0;
        //without stocks----------------------------------------
        $listKala= DB::select("SELECT DISTINCT PubGoods.GoodSn,PubGoods.GoodName,GoodGroups.GoodGroupSn,GoodPriceSale.Price3,GoodPriceSale.Price4,PUBGoodUnits.UName as UNAME,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.freeExistance,Amount,activePishKharid from Shop.dbo.PubGoods
                                JOIN NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                                JOIN Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
                                JOIN NewStarfood.dbo.star_Favorite on PubGoods.GoodSn=star_Favorite.GoodSn
                                JOIN Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                                JOIN (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") A on PubGoods.GoodSn=A.SnGood
                                left JOIN Shop.dbo.GoodPriceSale on GoodPriceSale.SnGood=PubGoods.GoodSn  where customerSn=".Session::get('psn').' and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) order by Amount desc');

        foreach ($listKala as $kala) {

            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

        }

        $currency=1;
        $currencyName="ریال";
        $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
        foreach ($currencyExistance as $cr) {
            $currency=$cr->currency;
        }
        if($currency==10){
            $currencyName="تومان";
        }

        foreach ($listKala as $kala) {
            if($kala->activePishKharid<1){
                $overLine=0;
                $callOnSale=0;
                $zeroExistance=0;
                $exist="NO";
                $favorits=DB::select("SELECT * FROM star_Favorite");
                foreach ( $favorits as $favorite) {
                    if($kala->GoodSn==$favorite->goodSn and $favorite->customerSn==Session::get('psn')){
                        $exist='YES';
                        break;
                    }else{
                        $exist='NO';
                    }
                }
                $requested=0;
                $user = DB::table('NewStarfood.dbo.star_requestedProduct')->where('acceptance',0)->where('customerId',Session::get('psn'))->where('productId',$kala->GoodSn)->first();
                if($user){
                    $requested=1;
                }
                $kala->requested=$requested;
                $restrictionState=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$kala->GoodSn)->select("overLine","callOnSale","zeroExistance")->get();
                if(count($restrictionState)>0){
                    foreach($restrictionState as $rest){
                        if($rest->overLine==1){
                            $overLine=1;
                        }else{
                            $overLine=0;
                        }
                        if($rest->callOnSale==1){
                            $callOnSale=1;
                        }else{
                            $callOnSale=0;
                        }
                        if($rest->zeroExistance==1){
                            $zeroExistance=1;
                        }else{
                            $zeroExistance=0;
                        }
                    }
                }
                $boughtKalas=DB::select("select  FactorStar.*,orderStar.* from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and  orderStatus=0");
                $orderBYSsn;
                $secondUnit;
                $amount;
                $packAmount;
                foreach ($boughtKalas as $boughtKala) {
                    $orderBYSsn=$boughtKala->SnOrderBYS;
                    $orderGoodSn=$boughtKala->SnGood;
                    $amount=$boughtKala->Amount;
                    $packAmount=$boughtKala->PackAmount;
                    $secondUnits=DB::select('SELECT GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods
                                            JOIN Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood
                                            JOIN Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn
                                            );
                    if(count($secondUnits)>0){
                        foreach ($secondUnits as $unit) {
                            $secondUnit=$unit->UName;
                        }
                    }else{
                        $secondUnit=$kala->UName;
                    }
                }
                if(count($boughtKalas)>0){
                    $kala->bought="Yes";
                    $kala->SnOrderBYS=$orderBYSsn;
                    $kala->secondUnit=$secondUnit;
                    $kala->Amount=$amount;
                    $kala->PackAmount=$packAmount;
                }else{
                    $kala->bought="No";
                }
                $kala->favorite=$exist;
                $kala->overLine=$overLine;
                $kala->callOnSale=$callOnSale;
                if($zeroExistance==1){
                $kala->Amount=0;
                }
            }else{

                $overLine=0;
                $callOnSale=0;
                $zeroExistance=0;
                $exist="NO";
                $favorits=DB::select("SELECT * FROM star_Favorite");
                foreach ( $favorits as $favorite) {
                    if($kala->GoodSn==$favorite->goodSn and $favorite->customerSn==Session::get('psn')){
                        $exist='YES';
                        break;
                    }else{
                        $exist='NO';
                    }
                }
                $restrictionState=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$kala->GoodSn)->select("overLine","callOnSale","zeroExistance")->get();
                if(count($restrictionState)>0){
                    foreach($restrictionState as $rest){
                        if($rest->overLine==1){
                            $overLine=1;
                        }else{
                            $overLine=0;
                        }
                        if($rest->callOnSale==1){
                            $callOnSale=1;
                        }else{
                            $callOnSale=0;
                        }
                        if($rest->zeroExistance==1){
                            $zeroExistance=1;
                        }else{
                            $zeroExistance=0;
                        }
                    }
                }
                $boughtKalas=DB::select("SELECT  star_pishKharidFactor.*,star_pishKharidOrder.* from NewStarfood.dbo.star_pishKharidFactor join NewStarfood.dbo.star_pishKharidOrder on star_pishKharidFactor.SnOrderPishKharid=star_pishKharidOrder.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and  orderStatus=0");
                $orderBYSsn;
                $secondUnit;
                $amount;
                $packAmount;
                foreach ($boughtKalas as $boughtKala) {
                    $orderBYSsn=$boughtKala->SnOrderBYSPishKharid;
                    $orderGoodSn=$boughtKala->SnGood;
                    $amount=$boughtKala->Amount;
                    $packAmount=$boughtKala->PackAmount;
                    $secondUnits=DB::select('SELECT GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName FROM Shop.dbo.PubGoods
                                        JOIN Shop.dbo.GoodUnitSecond ON PubGoods.GoodSn=GoodUnitSecond.SnGood
                                        JOIN Shop.dbo.PUBGoodUnits ON PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn);
                    if(count($secondUnits)>0){
                            $secondUnit=$secondUnits[0]->UName;
                    }else{
                        $secondUnit=$kala->UName;
                    }
                }
                if(count($boughtKalas)>0){
                    $kala->bought="Yes";
                    $kala->SnOrderBYS=$orderBYSsn;
                    $kala->secondUnit=$secondUnit;
                    $kala->Amount=$amount;
                    $kala->PackAmount=$packAmount;
                }else{
                    $kala->bought="No";
                }
                $kala->favorite=$exist;
                $kala->overLine=$overLine;
                $kala->callOnSale=$callOnSale;
                if($zeroExistance==1){
                $kala->Amount=0;
                }
            }
        }
		$logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view('subGroups.favoritProducts',['favorits'=>$listKala,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos]);
    }
}
