<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Response; 
use Session;
class PreBuyOrderAfter extends Controller
{
    public function index(Request $request)
    {
        $factorId=$request->get("id");
        //without stocks----------------------------------------------
        $orders=DB::select("SELECT PubGoods.GoodCde,star_pishKharidOrderAfter.SnOrderBYSPishKharidAfter,PubGoods.GoodName,NewStarfood.dbo.star_pishKharidOrderAfter .Fi,NewStarfood.dbo.star_pishKharidOrderAfter .Amount,NewStarfood.dbo.star_pishKharidOrderAfter .Price,A.UName as firstUnitName,B.UName as secondUnitName FROM NewStarfood.dbo.star_pishKharidOrderAfter 
        JOIN Shop.dbo.PubGoods ON PubGoods.GoodSn=NewStarfood.dbo.star_pishKharidOrderAfter.SnGood
        LEFT JOIN (SELECT PUBGoodUnits.USN,PUBGoodUnits.UName,PubGoods.GoodSn,PubGoods.defaultUnit FROM Shop.dbo.PUBGoodUnits JOIN Shop.dbo.PubGoods on PUBGoodUnits.USN=PubGoods.defaultUnit)A on A.GoodSn=NewStarfood.dbo.star_pishKharidOrderAfter .SnGood
        LEFT JOIN (SELECT PUBGoodUnits.USN,PUBGoodUnits.UName,GoodUnitSecond.SnGoodUnit FROM Shop.dbo.PUBGoodUnits JOIN Shop.dbo.GoodUnitSecond on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit)B on B.USN=PubGoods.defaultUnit
        WHERE SnHDS=".$factorId." and star_pishKharidOrderAfter.SnGood not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )  and  preBuyState=0");
        foreach ($orders as $order) {
            if(!$order->secondUnitName){
                $order->secondUnitName=$order->firstUnitName;
            }
        }
        return Response::json($orders);
    }
}
