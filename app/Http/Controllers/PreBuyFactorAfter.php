<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Response;
use Session;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
class PreBuyFactorAfter extends Controller
{
    //
    public function index(Request $request)
    {
        $factors=DB::table("NewStarfood.dbo.star_pishKharidFactorAfter")->join("Shop.dbo.Peopels","CustomerSn","=","PSN")->where("orderStatus",0)->select("*")->get();

        foreach ($factors as $factor) {
            $allMoney=0;
            $orders=DB::table("NewStarfood.dbo.star_pishKharidOrderAfter")->where("SnHDS",$factor->SnOrderPishKharidAfter)->select("*")->get();
            foreach ($orders as $order) {
                $allMoney+=$order->Price;
            }
            $factor->allMoney=$allMoney;
        }
        return View('admin.listFactorAfter',['factors'=>$factors]);
    }
    public function sendFactorToApp(Request $request)
    { 
        
        $factorId=$request->post("factorNumber");
        $orderSns=$request->post("SnOrderBYSPishKharidAfter");
        $totalCostNaql=0;
        $totalCostNasb=0;
        $totalCostMotafariqa=0;
        $totalCostBrgiri=0;
        $totalCostTarabari=0;
        $customerSn=$request->post("csn");
        $factorNumber=DB::select("SELECT MAX(OrderNo) as maxFact from Shop.dbo.OrderHDS WHERE CompanyNo=5");
        $factorNo=0;
        $current = Carbon::today();
        $todayDate = Jalalian::fromCarbon($current)->format('Y/m/d');
        foreach ($factorNumber as $number) {
            $factorNo=$number->maxFact;
        }
        
        $factorNo=$factorNo+1;
        $customerAddress=DB::select("SELECT peopeladdress FROM Shop.dbo.Peopels WHERE PSN=".$customerSn);
        $orderAddress="";
        foreach ($customerAddress as $peopelAdd) {
            $orderAddress=$peopelAdd->peopeladdress;
        }
        $lastOrdersStarAfter=DB::select("SELECT MAX(SnOrderPishKharidAfter) as orderSn from NewStarfood.dbo.star_pishKharidFactorAfter WHERE CustomerSn=".$customerSn." and OrderStatus=0");
        $lastOrderSnStar=0;
        foreach ($lastOrdersStarAfter as $lastOrder) {
            $lastOrderSnStar=$lastOrder->orderSn;
        }
        $countSendedOrders=0;
        $item=0;
        if($request->post("action")=="sendToApp"){
            DB::insert("INSERT INTO Shop.dbo.OrderHDS (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)
            VALUES(5,".$factorNo.",'".$todayDate."',".$customerSn.",'',0,'','','".Session::get("FiscallYear")."',0,0,3,'".$orderAddress."','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
            $maxsOrders=DB::select("SELECT MAX(SnOrder) as maxOrderId from Shop.dbo.OrderHDS where CustomerSn=".$customerSn);
            $maxsOrderId=0;
            foreach ($maxsOrders as $maxOrder) {
                $maxsOrderId=$maxOrder->maxOrderId;
            }
            $lastOrders=DB::select("SELECT MAX(SnOrder) as orderSn from Shop.dbo.OrderHDS WHERE CustomerSn=".$customerSn." and OrderStatus=0");
            $lastOrderSn=0;
            foreach ($lastOrders as $lastOrder) {
                $lastOrderSn=$lastOrder->orderSn;
            }
            $orederBYS=DB::select("SELECT * FROM NewStarfood.dbo.star_pishKharidOrderAfter where SnHDS=".$factorId);

            $countOrderBYS=DB::table("NewStarfood.dbo.star_pishKharidOrderAfter")->where("preBuyState",0)->where("SnHDS",$factorId)->count();
            
            foreach ($orederBYS as $order) {
                $item=$item+1;
                foreach ($orderSns as $sn) {
                if($sn==$order->SnOrderBYSPishKharidAfter and $order->preBuyState==0){
                    $countSendedOrders+=1;
                    DB::update("UPDATE NewStarfood.dbo.star_pishKharidOrderAfter set preBuyState=1 where SnOrderBYSPishKharidAfter=".$sn);
                    DB::insert("INSERT INTO Shop.dbo.OrderBYS(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)
                    VALUES(5,".$lastOrderSn.",".$order->SnGood.",".$order->PackType.",".$order->PackAmount.",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->PriceAfterTakhfif.",0,0,".$order->Price.",".$order->PriceAfterTakhfif.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                }
                }
            }
            
            if($countSendedOrders==$countOrderBYS){
                DB::update("update NewStarfood.dbo.star_pishKharidFactorAfter set OrderStatus=1 WHERE  SnOrderPishKharidAfter=".$lastOrderSnStar);
            }
            return redirect("/prebuyFactors");
        }else{
            $orederBYS=DB::select("SELECT * FROM NewStarfood.dbo.star_pishKharidOrderAfter where SnHDS=".$factorId);
            $countOrderBYS=DB::table("NewStarfood.dbo.star_pishKharidOrderAfter")->where("preBuyState",0)->where("SnHDS",$factorId)->count();
            
            foreach ($orederBYS as $order) {
                $item=$item+1;
                foreach ($orderSns as $sn) {
                    if($sn==$order->SnOrderBYSPishKharidAfter and $order->preBuyState==0){
                        
                        $countSendedOrders+=1;
                        DB::delete("DELETE FROM NewStarfood.dbo.star_pishKharidOrderAfter where SnOrderBYSPishKharidAfter=".$sn);
                    }
                }
            }
            if($countSendedOrders==$countOrderBYS){
                DB::delete("DELETE FROM NewStarfood.dbo.star_pishKharidFactorAfter WHERE  SnOrderPishKharidAfter=".$lastOrderSnStar);
            }
            return redirect("/prebuyFactors");
        }
    }



    public function listPreBuys(Request $request){
        // $factors=DB::table("NewStarfood.dbo.star_pishKharidFactorAfter")->join("Shop.dbo.Peopels","CustomerSn","=","PSN")->where("orderStatus",0)->select("*")->get();
        // foreach ($factors as $factor) {
        //     $allMoney=0;
        //     $orders=DB::table("NewStarfood.dbo.star_pishKharidOrderAfter")->where("SnHDS",$factor->SnOrderPishKharidAfter)->select("*")->get();
        //     foreach ($orders as $order) {
        //         $allMoney+=$order->Price;
        //     }
        //     $factor->allMoney=$allMoney;
        // }

        // $afternoonTitle="";
        // $moorningTitle="";
        // $defaultUnit;
        // $orderHDSs=DB::select("SELECT SnOrder FROM NewStarfood.dbo.FactorStar WHERE customerSn=".Session::get('psn')." and OrderStatus=0");
        // $SnHDS=0;
        // foreach ($orderHDSs as $hds) {
        //     $SnHDS=$hds->SnOrder;
        // }
        // $orderBYSs=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,orderStar.*,PUBGoodUnits.UName,A.SumAmount as AmountExist,dbo.star_GoodsSaleRestriction.freeExistance from Shop.dbo.PubGoods
        //                 join NewStarfood.dbo.orderStar on PubGoods.GoodSn=orderStar.SnGood
        //                 join NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
        //                 join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
        //                 join (select SUM(Amount) AS SumAmount,SnGood from Shop.dbo.ViewGoodExistsInStock join NewStarfood.dbo.addedStocks on
        //                 ViewGoodExistsInStock.SnStock=addedStocks.stockId where ViewGoodExistsInStock.FiscalYear=".Session::get("FiscallYear")."
        //                 group by ViewGoodExistsInStock.SnGood) A on PubGoods.GoodSn=A.SnGood
        //                 WHERE PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) and SnHDS=".$SnHDS);
        
        // foreach ($orderBYSs as $kala) {
        //     $kala->SumAmount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
        // }


        // $changedPriceState=0;
        // foreach ($orderBYSs as $order) {
        //     $orderGoodSn=$order->GoodSn;
        //     $secondUnits=DB::select('select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn);
        //     $secondUnit;
        //     $defaultUnits=DB::select("select defaultUnit from Shop.dbo.PubGoods where PubGoods.GoodSn=".$orderGoodSn);
        //     foreach ($defaultUnits as $unit) {
        //         $defaultUnit=$unit->defaultUnit;
        //     }
        //     if(count($secondUnits)>0){
        //         foreach ($secondUnits as $unit) {
        //             $secondUnit=$unit->UName;
        //         }
        //     }else{
        //         $secondUnit=$defaultUnit;
        //     }
        //     $order->secondUnitName=$secondUnit;
        //     $order->changedPrice=0;
        //     //در صورتیکه قیمت ها تغییر کرده باشند
        //     $prices=DB::select("SELECT * FROM Shop.dbo.GoodPriceSale where SnGood=".$order->GoodSn);
        //     foreach ($prices as $price) {
        //         if($order->Fi != $price->Price3){
        //             $changedPriceState=1;
        //             break;
        //         }
        //     }
        // }
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

            $changedPriceState=0;
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
                //در صورتیکه قیمت ها تغییر کرده باشند
                
                $prices=DB::select("SELECT * FROM Shop.dbo.GoodPriceSale where SnGood=".$order->GoodSn);
                foreach ($prices as $price) {
                    if($order->Fi != $price->Price3){
                        $changedPriceState=1;
                        break;
                    }
                }
            }
        return View('userProfile.listPreBuyFactors',['factors'=>$orderHDSsPishKharid,'orderPishKarids'=>$orderPishKarids,'changedPriceState'=>$changedPriceState,'currency'=>$currency,'currencyName'=>$currencyName]);
    }
}
