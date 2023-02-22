<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
use DateTime;
class Carts extends Controller {
    public function index(Request $request)
    {
        $afternoonTitle="";
        $moorningTitle="";
        $defaultUnit;
        $orderHDSs=DB::select("SELECT SnOrder FROM NewStarfood.dbo.FactorStar WHERE customerSn=".Session::get('psn')." and OrderStatus=0");
        $SnHDS=0;
        foreach ($orderHDSs as $hds) {
            $SnHDS=$hds->SnOrder;
        }

        //with stocks -----------------------------------
        $orderBYSs=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,orderStar.*,PUBGoodUnits.UName,A.Amount as AmountExist,dbo.star_GoodsSaleRestriction.freeExistance from Shop.dbo.PubGoods
                        JOIN NewStarfood.dbo.orderStar on PubGoods.GoodSn=orderStar.SnGood
                        JOIN NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                        JOIN Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
                        JOIN (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") A on PubGoods.GoodSn=A.SnGood
                        WHERE PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) and SnHDS=".$SnHDS);
        
    //    foreach ($orderBYSs as $kala) {

        //    $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
    
      //  }
        
        $currency=1;
        
        $currencyName="ریال";
        
        $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
        
        foreach ($currencyExistance as $cr) {

            $currency=$cr->currency;

        }
        
        if($currency==10){

            $currencyName="تومان";

        }

        $changedPriceState=0;
        
        foreach ($orderBYSs as $order) {
            $orderGoodSn=$order->GoodSn;
            $secondUnits=DB::select('select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn);
            $secondUnit;
            $defaultUnits=DB::select("select UName from Shop.dbo.PubGoods join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN where PubGoods.GoodSn=".$orderGoodSn);
            
            foreach ($defaultUnits as $unit) {
                $defaultUnit=$unit->UName;
            }
            
            if(count($secondUnits)>0){
                
                foreach ($secondUnits as $unit) {
                    
                    $secondUnit=$unit->UName;
                
                }
            
            }else{
                
                $secondUnit=$defaultUnit;
            
            }
            
            $order->secondUnitName=$secondUnit;
            
            //در صورتیکه قیمت ها تغییر کرده باشند
            $prices=DB::select("SELECT Price3 FROM Shop.dbo.GoodPriceSale where SnGood=".$order->GoodSn);
            
            foreach ($prices as $price) {
                
                if($order->Fi != $price->Price3){
                    
                    $changedPriceState=1;
                    break;
               
                }
            }
            
            $order->changedPrice=$changedPriceState;
        }
		
        $intervalBetweenBuys=1000;
        $MainIntervalBetweenBuys= DB::select("SELECT DATEDIFF(hour,CONVERT(DATETIME, (SELECT TimeStamp from NewStarfood.dbo.OrderHDSS where
		OrderHDSS.SnOrder=(select Max(SnOrder) from NewStarfood.dbo.OrderHDSS WHERE  CustomerSn=".Session::get('psn')." and isDistroy=0))), CURRENT_TIMESTAMP)
		AS DateDiff");

        if($MainIntervalBetweenBuys[0]->DateDiff){
            
            $intervalBetweenBuys=$MainIntervalBetweenBuys[0]->DateDiff;
        }else{
            
            $intervalBetweenBuys=$intervalBetweenBuys;
        }
        $webSettings=DB::select("SELECT * from NewStarfood.dbo.star_webSpecialSetting");
        $minSalePriceFactor=1000;
        $pardakhtLive=0;
        $minSalePriceFactor=$webSettings[0]->minSalePriceFactor;
        $afternoonTitle=$webSettings[0]->moorningTimeContent;
        $moorningTitle=$webSettings[0]->afternoonTimeContent;

        $customerRestrictions=DB::select("SELECT * FROM NewStarfood.dbo.star_customerRestriction where customerId=".Session::get('psn'));
        foreach ($customerRestrictions as $restrict) {
            if($restrict->minimumFactorPrice>0){
            $minSalePriceFactor=$restrict->minimumFactorPrice;
            }
        }

        //used for پیش خرید
        
        $currency=1;
        $currencyName="ریال";
        $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
        foreach ($currencyExistance as $cr) {
            $currency=$cr->currency;
        }
        if($currency==10){
            $currencyName="تومان";
        }

        $orderHDSsPishKharid=DB::select("SELECT SnOrderPishKharid FROM NewStarfood.dbo.star_pishKharidFactor WHERE customerSn=".Session::get('psn')." and OrderStatus=0");
        $SnHDS=0;
        foreach ($orderHDSsPishKharid as $hds) {
            $SnHDS=$hds->SnOrderPishKharid;
        }
        $orderPishKarids=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,star_pishKharidOrder.*,PUBGoodUnits.UName,A.Amount as AmountExist,dbo.star_GoodsSaleRestriction.freeExistance,activePishKharid from Shop.dbo.PubGoods
                                join NewStarfood.dbo.star_pishKharidOrder on PubGoods.GoodSn=star_pishKharidOrder.SnGood
                                join NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                                join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
                                join (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") A on PubGoods.GoodSn=A.SnGood WHERE SnHDS=".$SnHDS);
        foreach ($orderPishKarids as $kala) {
            $kala->AmountExist+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
        }
        foreach ($orderPishKarids as $order) {
            $orderGoodSn=$order->GoodSn;
            $secondUnits=DB::select('select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn);
            
            $defaultUnits=DB::select("select UName from Shop.dbo.PubGoods join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN where PubGoods.GoodSn=".$orderGoodSn);
            
            if(count($secondUnits)>0){
                $order->secondUnitName=$secondUnits[0]->UName;
            }else{
                $order->secondUnitName=$defaultUnits[0]->UName;
            }

            $order->changedPrice=0;
        }
		
		$logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view ('carts.carts',['orders'=>$orderBYSs,'orderPishKarids'=>$orderPishKarids,'intervalBetweenBuys'=>$intervalBetweenBuys,'minSalePriceFactor'=>$minSalePriceFactor,'changedPriceState'=>$changedPriceState,'afternoonTitle'=>$afternoonTitle,'moorningTitle'=>$moorningTitle,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos]);
    }
    public function cartView(Request $request)
    {
        $factorSn=$request->post('factorSn');
        $factorhds=DB::select("SELECT * FROM NewStarfood.dbo.FactorStar WHERE SerialNoHDS=".$factorSn);
        $factorDate1;
        foreach ($factorhds as $factor) {
            $factorDate1=$factor->FactDate;
        }
        $factorBYS=DB::select("SELECT * FROM NewStarfood.dbo.orderStar WHERE SnFact=".$factorSn);
        foreach ($factorBYS as $buy) {
            $defaultUnit;
            $kalaName;
            $secondUnit;
            $amountUnit;
            $kala=DB::select('SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName FROM Shop.dbo.PubGoods JOIN Shop.dbo.PUBGoodUnits
            ON PubGoods.DefaultUnit=PUBGoodUnits.USN WHERE PubGoods.CompanyNo=5 AND PubGoods.GoodSn='.$buy->SnGood);
            foreach ($kala as $k) {
            $defaultUnit=$k->UName;
            $kalaName=$k->GoodName;
            }
            $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$buy->SnGood);
            foreach ($subUnitStuff as $stuff) {
            $secondUnit=$stuff->secondUnit;
            $amountUnit=$stuff->AmountUnit;
            }
            $buy->firstUnit=$defaultUnit;
            $buy->secondUnit=$secondUnit;
            $buy->amountUnit=$amountUnit;
            $buy->GoodName=$kalaName;
            $factorDate=Carbon::parse($buy->TimeStamp);
            $buy->factorDate = $factorDate1;
            $buy->factorTime = Jalalian::fromCarbon($factorDate)->format('H:m:s');
        }
        return view('carts.cartView',['factorBYS'=>$factorBYS]);
    }

    public function factorView(Request $request){
        $factorSn=$request->post("factorSn");
        $factorhds=DB::select("SELECT * FROM Shop.dbo.FactorHDS WHERE SerialNoHDS=".$factorSn);
        $factorDate1;
        foreach ($factorhds as $factor) {
            $factorDate1=$factor->FactDate;
        }
        $factorBYS=DB::select("SELECT * FROM Shop.dbo.FactorBYS WHERE SnFact=".$factorSn);
        foreach ($factorBYS as $buy) {
            $defaultUnit;
            $kalaName;
            $secondUnit;
            $amountUnit;
            $kala=DB::select('SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName FROM Shop.dbo.PubGoods JOIN Shop.dbo.PUBGoodUnits
            ON PubGoods.DefaultUnit=PUBGoodUnits.USN WHERE PubGoods.CompanyNo=5 AND PubGoods.GoodSn='.$buy->SnGood);
            foreach ($kala as $k) {
            $defaultUnit=$k->UName;
            $kalaName=$k->GoodName;
            }
            $subUnitStuff= DB::select("SELECT PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$buy->SnGood);
            if(count($subUnitStuff)>0){
            foreach ($subUnitStuff as $stuff) {
            $secondUnit=$stuff->secondUnit;
            }
            }else{
                $secondUnit=$defaultUnit;
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
            $buy->firstUnit=$defaultUnit;
            $buy->secondUnit=$secondUnit;
            $buy->GoodName=$kalaName;
            $factorDate=Carbon::parse($buy->TimeStamp);
            $buy->factorDate = $factorDate1;
            $buy->factorTime = Jalalian::fromCarbon($factorDate)->format('H:m:s');
        }
        return view('carts.cartView',['factorBYS'=>$factorBYS,'currency'=>$currency,'currencyName'=>$currencyName]);
    }
    public function getOrders(Request $request)
    {
        $SnHDS= $request->post("snHds");
        $orders=DB::table("Shop.dbo.OrderBYS")->where("SnHDS",$SnHDS)->where("FactorFew",">",0)->get();
        return $orders;
    }

}
