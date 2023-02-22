<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use Session;
use Cockie;
use DateTime;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
class SalesOrder extends Controller
{
    public function salesOrder(Request $request) {
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
        left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 

        left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=0 and isDistroy=0  order by orderHDSS.TimeStamp desc");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
        left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
        left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=0 and isDistroy=0");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS WHERE isDistroy=0 AND isSent=0");
        return view('salesOrder.salesOrderList',['orders'=>$orders,'allMoney'=>$allMoney[0]->sumAllMoney,'allPayed'=>$allPayed[0]->payedMoney]);
    }
    public function getOrderDetail(Request $request)
    {
        $orderSn=$request->get("orderSn");
        $orderItems=DB::select("SELECT *,orderBYSS.Price as totalPrice from NewStarfood.dbo.orderBYSS join Shop.dbo.PubGoods on orderBYSS.SnGood=PubGoods.GoodSn
        join (SELECT USN,UName as firstUnit from Shop.dbo.PUBGoodUnits)a on PubGoods.DefaultUnit=a.USN
        left join (SELECT USN,UName as secondUnit,SnGood from Shop.dbo.PUBGoodUnits join Shop.dbo.GoodUnitSecond on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit)b on b.SnGood=PubGoods.GoodSn
        where orderBYSS.SnHDS=".$orderSn);
        $order=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS JOIN Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            JOIN (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
                            FROM Shop.dbo.PhoneDetail
                            GROUP BY SnPeopel)a on PSN=a.SnPeopel WHERE SnOrder=$orderSn");
        
        $notEffecientList=DB::select("SELECT * FROM(
            SELECT ViewGoodExistsInStock.Amount AS stockAmount,a.Amount AS orderAmount,
            a.SnGood,IIF(ViewGoodExistsInStock.Amount <= a.Amount, 0, 1) AS goodExist 
            FROM Shop.dbo.ViewGoodExistsInStock JOIN (SELECT * From NewStarfood.dbo.OrderBYSS 
            WHERE SnHDS=$orderSn)a ON a.SnGood=ViewGoodExistsInStock.SnGood WHERE ViewGoodExistsInStock.CompanyNo=5 AND ViewGoodExistsInStock.FiscalYear=1399 AND SnStock=23)
            a JOIN Shop.dbo.PubGoods ON a.SnGood=PubGoods.GoodSn WHERE goodExist>1");
        $costs=DB::select("SELECT SUM(Price) AS totalPrice FROM Shop.dbo.OrderAmelBYS  WHERE SnOrder=$orderSn GROUP BY SnOrder");
        $totalAmount=DB::select("SELECT SUM(Price) AS totalMoney FROM NewStarfood.dbo.OrderBYSS WHERE SnHDS=$orderSn GROUP BY SnHDS");
        return Response::json([$orderItems,$order,$notEffecientList,$costs,$totalAmount]);
    }

    public function getSendItemInfo(Request $request)
    {
        $stockId=$request->get("stockId");
        $goodSn=$request->get("goodSn");
        $customerSn=$request->get("customerSn");
        //گرفتن اطلاعات موجودی قیمت فروش 
        
        $goodExist=DB::select("SELECT Amount FROM Shop.dbo.ViewGoodExistsInStock WHERE CompanyNo=5 AND FiscalYear=1399 AND SnGood=$goodSn AND SnStock=".$stockId);
        
        $goodPrice=DB::select("SELECT * FROM Shop.dbo.GoodPriceSale WHERE CompanyNo=5 and SnGood=".$goodSn);
        
        $goodPriceOfCustomer=DB::select("SELECT * FROM(
            SELECT Max(SerialNoBys) lastBYSSn FROM (
            SELECT Fi,SnGood,CustomerSn,SnFact,SerialNoBys FROM  Shop.dbo.FactorBYS  JOIN Shop.dbo.FactorHDS ON Shop.dbo.FactorBYS.SnFact=FactorHDS.SerialNoHDS
            )a WHERE CustomerSn=$customerSn AND SnGood=$goodSn)b JOIN Shop.dbo.FactorBYS ON b.lastBYSSn=FactorBYS.SerialNoBys");
        
        $lastSalePrice=DB::select("SELECT Fi,lastBysSn,SnGood FROM(
            SELECT MAX(SerialNoBys) AS lastBysSn FROM Shop.dbo.FactorBYS WHERE SnGood=$goodSn AND CompanyNo=5
            )a JOIN Shop.dbo.FactorBYS ON a.lastBysSn=FactorBYS.SerialNoBys");

        return Response::json([$goodExist,$goodPrice,$goodPriceOfCustomer,$lastSalePrice]);
    }
    public function addToFactorHisabdari(Request $request){
        $orderHDS=$request->post("orderHDS");
        DB::beginTransaction();

try {
        //خواندن داده ها از سفارش ارسالی
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.OrderHDSS where isSent=0 and SnOrder=$orderHDS");
        if(count($orders)>0){
            //در صورتیکه سفارش قبلا توسط کاربر دیگر ارسال نشده باشد!
            $orderbys=DB::table("NewStarfood.dbo.orderBYSS")->where("SnHDS",$orderHDS)->get();
            $netPriceOrder=0;
            foreach($orderbys as $order){
                $amount=DB::select("SELECT Amount FROM Shop.dbo.ViewGoodExistsInStock WHERE SnGood=$order->SnGood AND CompanyNo=5 AND FiscalYear=1399 AND SnStock=23");
                if($amount[0]->Amount >= $order->Amount){
                    $netPriceOrder+=$order->Price;  
                }
            }

            $order=$orders[0];
            $factDate=$request->post("sendOrderDate");
        
            $customerSn=$order->CustomerSn;
            
            $takhfif=$order->Takhfif;//
            $inVoiceNumber=$order->InVoiceNumber;//
            
            $factDesc=$order->OrderDesc;
        
            $fiscallYear=$order->FiscalYear;
        
            $SnGetAndPay=0;
        
            $netPriceHDS=$netPriceOrder;//از سفارش گرفته شود
            $payedPriceHDS=$order->payedMoney;//از سفارش گرفته شود
            $isOnline=$order->isPayed;
        
            $inforPriceHDS=0;//از سفارش گرفته شود
        
            $stockId=$request->post("stockId");
        
            $factTime=Carbon::now()->format("H:i:s");
        
            $otherAddress=$order->OrderAddress;//از سفارش گرفته شود
        
            $ersalTime=$order->OrderErsalTime;//از سفارش گرفته شود
        
            $snAddress=$order->OrderSnAddress;//از سفارش گرفته شود

            $factNo=1;
            $factNo=DB::table("Shop.dbo.FactorHDS")->where("FactType",3)->where("CompanyNo",5)->max("FactNo");

            DB::table("Shop.dbo.FactorHDS")->insert([
            "CompanyNo"=>5
            ,"FactType"=>3
            ,"FactNo"=>$factNo+1
            ,"FactDate"=>"$factDate"
            ,"CustomerSn"=>$customerSn
            ,"takhfif"=>$takhfif
            ,"FactDesc"=>"$factDesc"
            ,"FiscalYear"=>"$fiscallYear"
            ,"SnGetAndPay"=>$SnGetAndPay
            ,"NetPriceHDS"=>$netPriceHDS
            ,"InforPriceHDS"=>$inforPriceHDS
            ,"SnStockIn"=>$stockId
            ,"SnUser1"=>21
            ,"FactTime"=>"$factTime"
            ,"OtherAddress"=>"$otherAddress"
            ,"ErsalTime"=>$ersalTime
            ,"SnAddress"=>$snAddress
            ]);

            if($isOnline==1){
                //آخرین شماره سند
                // $lastDocNoHds=DB::table("Shop.dbo.GetAndPayHDS")->where("GetOrPayHDS",1)->max("DocNoHDS");
                // //آخرین شماره فاکتور مربوط  واریز کننده
                 $lastFactorHDSSn=DB::table("Shop.dbo.FactorHDS")->where("CustomerSn",$customerSn)->max("SerialNoHDS");
                // //وارد جدول سطح بالای داد و گرفت
                // DB::table("Shop.dbo.GetAndPayHDS")->insert(
                //     ["CompanyNo"=>5
                // ,"GetOrPayHDS"=>1
                // ,"DocNoHDS"=>$lastDocNoHds+1//auto increment according to HDS 
                // ,"DocDate"=>"".Jalalian::fromCarbon(Carbon::today())->format('Y/m/d').""//فارسی تاریخ
                // ,"DocDescHDS"=>"آنلاین پرداخت شد"
                // ,"StatusHDS"=>0
                // ,"PeopelHDS"=>$customerSn
                // ,"FiscalYear"=>"".Session::get("FiscallYear").""
                // ,"SnFactor"=>0
                // ,"InForHDS"=>0
                // ,"NetPriceHDS"=>$payedPriceHDS//مبلغ به ریال
                // ,"DocTypeHDS"=>0
                // ,"SnCashMaster"=>48
                // ,"BuyFactNo"=>0
                // ,"SaveTime"=>"".Jalalian::fromCarbon(Carbon::now())->format('H:i:s').""//ساعت دقیقه و ثانیه فعلی
                // ,"IsExport"=>0
                // ,"AmanatiOrZemanati"=>0
                // ,"PriceMaliat"=>0
                // ,"SnUser"=>0
                // ,"SnFactBuy"=>0
                // ,"SnTafHazineh"=>0
                // ,"SnNamayeshgah2"=>0
                // ,"SnBuyEX"=>0
                // ,"SnToCash"=>0
                // ,"NaghdiPrice"=>0
                // ,"SnKoodak"=>0
                // ,"SnSeller"=>0
                // ,"SnSerialNoFromPablet"=>0
                // ,"SnChequeTransBargashti"=>0
                // ,"PayIsBabatChequeBargashti"=>0
                // ,"GAPSnTaf1"=>0
                // ,"GAPTafType1"=>0
                // ,"GAPSnTaf2"=>0
                // ,"GAPTafType2"=>0
                // ,"ExportSanadGAP"=>0
                // ,"SnFactForTasviyeh"=>$lastFactorHDSSn//آخرین فاکتور
                // ,"MazanehPrice"=>0
                // ,"Fi_1_GramTala"=>0
                // ,"VaznTala"=>0
                // ,"SnGapHDSTablet"=>0
                // ,"SnGapSellerTablet"=>0
                // ,"SnOldFactForTasviyeh"=>0]);

                // //آخرین شماره ورود جدول سطح بالای داد و گرفت
                // $lastSnPayHDS=DB::table("Shop.dbo.GetAndPayHDS")->max("SerialNoHDS");
                // //جدول سطح دوم داد و گرفت
                // DB::table("Shop.dbo.GetAndPayBYS")->insert(
                //     ["CompanyNo"=>5
                // ,"DocTypeBYS"=>3
                // ,"Price"=>$payedPriceHDS
                // ,"ChequeDate"=>"".Jalalian::fromCarbon(Carbon::today())->format('Y/m/d').""
                // ,"ChequeNo"=>0
                // ,"AccBankno"=>""
                // ,"Owner"=>""
                // ,"SnBank"=>88
                // ,"Branch"=>""
                // ,"SnChequeBook"=>0
                // ,"FiscalYear"=>"".Session::get("FiscallYear").""
                // ,"SnHDS"=>$lastSnPayHDS
                // ,"DocDescBYS"=>"پرداخت آنلاین"
                // ,"StatusBYS"=>0
                // ,"ChequeRecNo"=>0
                // ,"CuType"=>0
                // ,"CuPrice"=>0
                // ,"SnAccBank"=>43
                // ,"LastSnTrans"=>0
                // ,"CashNo"=>0
                // ,"SnPriorDetail"=>0
                // ,"SnMainPeopel"=>0
                // ,"SnTransChequeRefrence"=>0
                // ,"IsExport"=>0
                // ,"RadifInDaftarCheque"=>0
                // ,"CuUnitPrice"=>0
                // ,"SnBigIntHDSFact_GapBYS"=>0
                // ,"NoPayaneh_KartKhanBys"=>""
                // ,"KarMozdPriceBys"=>0
                // ,"NoSayyadi"=>""
                // ,"NameSabtShode"=>""
                // ,"SnOldBysGAP"=>0
                // ,"SnOldHDSGAP"=>0
                // ,"SnSellerGap"=>0]);
                DB::table("NewStarfood.dbo.payedOnline")->insert([
                    'factorSn'=>$lastFactorHDSSn,
                    'payedMoney'=>$payedPriceHDS,
                    'invoiceNumber'=>"$inVoiceNumber"
                ]);    
            }
            //وارد کردن داده های جدول زیر شاخه فاکتور
            $lastFactSN=0;
            $lastFactSN=DB::table("Shop.dbo.FactorHDS")->where("FactType",3)->where("CompanyNo",5)->max("SerialNoHDS");
            $orders=DB::table("NewStarfood.dbo.orderBYSS")->where("SnHDS",$orderHDS)->get();
            $i=0;
            foreach($orders as $order){
                $i+=1;
                $amount=DB::select("SELECT Amount FROM Shop.dbo.ViewGoodExistsInStock WHERE SnGood=$order->SnGood AND CompanyNo=5 AND FiscalYear=1399 AND SnStock=23");
                if($amount[0]->Amount >= $order->Amount){
                    DB::table("Shop.dbo.FactorBYS")->insert([
                        "CompanyNo"=>5
                        ,"SnFact"=>$lastFactSN
                        ,"SnGood"=>$order->SnGood
                        ,"PackType"=>$order->PackType
                        ,"PackAmnt"=>$order->PackAmount
                        ,"Amount"=>$order->Amount
                        ,"Fi"=>$order->Fi
                        ,"Price"=>$order->Price
                        ,"FiPack"=>$order->FiPack
                        ,"SnStockBYS"=>$stockId
                        ,"PriceAfterAmel"=>0//کار شود
                        ,"FiAfterAmel"=>0//ک ش
                        ,"ItemNo"=>$i// ک ش
                        ,"PriceAfterTakhfif"=>$order->Price//ک ش
                        ,"RealFi"=>$order->Fi
                        ,"RealPrice"=>$order->Price
                    ]);

                }
            }
            DB::table("NewStarfood.dbo.orderHDSS")->where("SnOrder",$orderHDS)->update(['isSent'=>1]);
        }
        DB::commit();
        // all good
    } catch (\Exception $e) {
        DB::rollback();
        // something went wrong
    }
        return redirect("/salesOrder");
    }

    public function distroyOrder(Request $request)
    {
        $orderSn=$request->get("orderId");
        DB::table("NewStarfood.dbo.orderHDSS")->where("SnOrder",$orderSn)->update(['isDistroy'=>1]);
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS JOIN Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) AS adminName FROM CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=0 and isDistroy=0");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=0 and isDistroy=0");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS WHERE isDistroy=0 AND isSent=0");
        return Response::json([$orders,$allMoney[0],$allPayed[0]]);
    }
    public function deleteOrderItem(Request $request)
    {
        $orderSn=$request->get("orderSn");
        $orderHds=$request->get("hdsSn");
        DB::table("NewStarfood.dbo.orderBYSS")->where("SnOrderBYSS",$orderSn)->delete();
        $orderItems=DB::select("SELECT *,orderBYSS.Price as totalPrice from NewStarfood.dbo.orderBYSS join Shop.dbo.PubGoods on orderBYSS.SnGood=PubGoods.GoodSn
        join (SELECT USN,UName as firstUnit from Shop.dbo.PUBGoodUnits)a on PubGoods.DefaultUnit=a.USN
        left join (SELECT USN,UName as secondUnit,SnGood from Shop.dbo.PUBGoodUnits join Shop.dbo.GoodUnitSecond on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit)b on b.SnGood=PubGoods.GoodSn
        where orderBYSS.SnHDS=".$orderHds);
        return Response::json($orderItems);
    }
    public function searchItemForAddToOrder(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        $items=DB::select("SELECT * FROM Shop.dbo.PubGoods JOIN (SELECT UName AS firstUnit,USN FROM Shop.dbo.PUBGoodUnits WHERE CompanyNo=5)a  ON DefaultUnit=a.USN
                            LEFT JOIN (SELECT SnGood,UName AS secondUnit,USN as secondUnitSn,AmountUnit FROM Shop.dbo.GoodUnitSecond LEFT JOIN Shop.dbo.PUBGoodUnits on GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN)b on PubGoods.GoodSn=b.SnGood
                            WHERE CompanyNo=5 AND (GoodName LIKE N'%$searchTerm%')");
        return Response::json($items);
    }
    public function getGoodInfoForAddOrderItem(Request $request)
    {
        $goodSn=$request->get("goodSn");
        $stockId=$request->get("stockId");
        $customerSn=$request->get("customerSn");

        $items=DB::select(" SELECT * FROM Shop.dbo.PubGoods JOIN (SELECT UName AS firstUnit,USN FROM Shop.dbo.PUBGoodUnits WHERE CompanyNo=5)a  ON DefaultUnit=a.USN
                            LEFT JOIN (SELECT SnGood,UName AS secondUnit,USN as secondUnitSn,AmountUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits on GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN)b on PubGoods.GoodSn=b.SnGood
                            WHERE CompanyNo=5 AND (GoodSn = $goodSn)");
        //گرفتن اطلاعات موجودی قیمت فروش 
        
        $goodExist=DB::select("SELECT Amount FROM Shop.dbo.ViewGoodExistsInStock WHERE CompanyNo=5 AND FiscalYear=1399 AND SnGood=$goodSn AND SnStock=".$stockId);
        
        $goodPrice=DB::select("SELECT * FROM Shop.dbo.GoodPriceSale WHERE CompanyNo=5 and SnGood=".$goodSn);
        
        $goodPriceOfCustomer=DB::select("SELECT * FROM(
            SELECT Max(SerialNoBys) lastBYSSn FROM (
            SELECT Fi,SnGood,CustomerSn,SnFact,SerialNoBys FROM  Shop.dbo.FactorBYS  JOIN Shop.dbo.FactorHDS ON Shop.dbo.FactorBYS.SnFact=FactorHDS.SerialNoHDS
            )a WHERE CustomerSn=$customerSn AND SnGood=$goodSn)b JOIN Shop.dbo.FactorBYS ON b.lastBYSSn=FactorBYS.SerialNoBys");
        
        $lastSalePrice=DB::select("SELECT Fi,lastBysSn,SnGood FROM(
            SELECT MAX(SerialNoBys) AS lastBysSn FROM Shop.dbo.FactorBYS WHERE SnGood=$goodSn AND CompanyNo=5
            )a JOIN Shop.dbo.FactorBYS ON a.lastBysSn=FactorBYS.SerialNoBys");
        return Response::json([$items,$goodExist,$goodPrice,$goodPriceOfCustomer,$lastSalePrice]);
    }
    public function addToOrderItems(Request $request)
    {
        $defaultUnit;
        $packType;
        $packAmount;
        $orderHDSsn=$request->get('HdsSn');
        $amountUnit=$request->get('amountUnit');
        $productId=$request->get('goodSn');

        $secondUnits=DB::select("SELECT SnGoodUnit,AmountUnit FROM Shop.dbo.GoodUnitSecond WHERE GoodUnitSecond.SnGood=".$productId);
        $defaultUnits=DB::select("SELECT defaultUnit FROM Shop.dbo.PubGoods WHERE PubGoods.GoodSn=".$productId);
        foreach ($defaultUnits as $unit) {
            $defaultUnit=$unit->defaultUnit;
        }
        if(count($secondUnits)>0){
            foreach ($secondUnits as $unit) {
                $packType=$unit->SnGoodUnit;
                $packAmount=$unit->AmountUnit;
            }
        }else{
            $packType=$defaultUnit;
            $packAmount=1;
        }

        
        $prices=DB::select("SELECT GoodPriceSale.Price3,GoodPriceSale.Price4 FROM Shop.dbo.GoodPriceSale WHERE GoodPriceSale.SnGood=".$productId);
        $exactPrice=0;
        $exactPrice1=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
            $exactPrice1=$price->Price4;
        }
        $allMoney= $exactPrice * $amountUnit;
        $allMoneyFirst= $exactPrice1 * $amountUnit;
        $fiPack=$exactPrice*$packAmount;
        $fiPrice=$allMoney/$amountUnit;
        $packAmount1=$amountUnit/$packAmount;

        DB::table("NewStarfood.dbo.orderBYSS")->insert(["CompanyNo"=>5
                                                        ,"SnHDS"=>$orderHDSsn
                                                        ,"SnGood"=>$productId
                                                        ,"PackType"=>$packType
                                                        ,"PackAmount"=>$packAmount1
                                                        ,"Amount"=>$amountUnit
                                                        ,"Fi"=>$fiPrice
                                                        ,"Price"=>$allMoney
                                                        ,"DescRecord"=>"دستی توسط ادمین"
                                                        ,"DateOrder"=>"".Jalalian::fromCarbon(Carbon::today())->format('Y/m/d').""
                                                        ,"SnUser"=>12
                                                        ,"FactorFew"=>0
                                                        ,"FiPack"=>$fiPack
                                                        ,"PriceAfterTakhfif"=>$allMoney
                                                        ,"RealFi"=>$fiPrice
                                                        ,"RealPrice"=>$allMoney]);

        $orderItems=DB::select("SELECT *,orderBYSS.Price AS totalPrice FROM NewStarfood.dbo.orderBYSS JOIN Shop.dbo.PubGoods ON orderBYSS.SnGood=PubGoods.GoodSn
                                JOIN (SELECT USN,UName AS firstUnit FROM Shop.dbo.PUBGoodUnits)a ON PubGoods.DefaultUnit=a.USN
                                LEFT JOIN (SELECT USN,UName AS secondUnit,SnGood FROM Shop.dbo.PUBGoodUnits JOIN Shop.dbo.GoodUnitSecond on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit)b on b.SnGood=PubGoods.GoodSn
                                WHERE orderBYSS.SnHDS=".$orderHDSsn);
        return Response::json($orderItems);
    }
    public function getOrderItemInfo(Request $request)
    {
        $itemSn=$request->get("itemSn");
        $customerSn=$request->get("customerSn");
        $tem=DB::select("SELECT *,orderBYSS.Price AS totalPrice FROM NewStarfood.dbo.orderBYSS JOIN Shop.dbo.PubGoods ON orderBYSS.SnGood=PubGoods.GoodSn
                        JOIN (SELECT USN,UName AS firstUnit FROM Shop.dbo.PUBGoodUnits)a ON PubGoods.DefaultUnit=a.USN
                        LEFT JOIN (SELECT USN,UName as secondUnit,SnGood,AmountUnit
                        FROM Shop.dbo.PUBGoodUnits JOIN Shop.dbo.GoodUnitSecond ON PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit)b ON b.SnGood=PubGoods.GoodSn
                        WHERE orderBYSS.SnOrderBYSS=$itemSn");
        $goodSn=$tem[0]->GoodSn;
        $stockId=23;             
        //گرفتن اطلاعات موجودی قیمت فروش
        $goodExist=DB::select("SELECT Amount FROM Shop.dbo.ViewGoodExistsInStock WHERE CompanyNo=5 AND FiscalYear=1399 AND SnGood=$goodSn AND SnStock=".$stockId);

        $goodPrice=DB::select("SELECT * FROM Shop.dbo.GoodPriceSale WHERE CompanyNo=5 and SnGood=".$goodSn);
        
        $goodPriceOfCustomer=DB::select("SELECT * FROM(
            SELECT Max(SerialNoBys) lastBYSSn FROM (
            SELECT Fi,SnGood,CustomerSn,SnFact,SerialNoBys FROM  Shop.dbo.FactorBYS  JOIN Shop.dbo.FactorHDS ON Shop.dbo.FactorBYS.SnFact=FactorHDS.SerialNoHDS
            )a WHERE CustomerSn=$customerSn AND SnGood=$goodSn)b JOIN Shop.dbo.FactorBYS ON b.lastBYSSn=FactorBYS.SerialNoBys");
        
        $lastSalePrice=DB::select("SELECT Fi,lastBysSn,SnGood FROM(
            SELECT MAX(SerialNoBys) AS lastBysSn FROM Shop.dbo.FactorBYS WHERE SnGood=$goodSn AND CompanyNo=5
            )a JOIN Shop.dbo.FactorBYS ON a.lastBysSn=FactorBYS.SerialNoBys");                
        return Response::json([$tem[0],$goodExist,$goodPrice,$goodPriceOfCustomer,$lastSalePrice]);
    }
    public function editOrderItem(Request $request)
    {
        $itemSn=$request->get("orderSn");
        $orderHDSsn=$request->get('snHDS');
        $defaultUnit;
        $packType;
        $packAmount;
        $amountUnit=$request->get('amount');
        $productId=$request->get('productId');
        $secondUnits=DB::select("select SnGoodUnit,AmountUnit from Shop.dbo.GoodUnitSecond where GoodUnitSecond.SnGood=".$productId);
        $defaultUnits=DB::select("select defaultUnit from Shop.dbo.PubGoods where PubGoods.GoodSn=".$productId);
        foreach ($defaultUnits as $unit) {
            $defaultUnit=$unit->defaultUnit;
        }
        if(count($secondUnits)>0){
            foreach ($secondUnits as $unit) {
                $packType=$unit->SnGoodUnit;
                $packAmount=$unit->AmountUnit;
            }
            }else{
                $packType=$defaultUnit;
                $packAmount=1;
            }

        $prices=DB::select("select GoodPriceSale.Price3,GoodPriceSale.Price4 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$productId);
        $exactPrice=0;
        $exactPrice1=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
            $exactPrice1=$price->Price4;
        }
        $allMoney= $exactPrice * $amountUnit;
        $allMoneyFirst= $exactPrice1 * $amountUnit;
        $fiPack=$exactPrice*$packAmount;
        $fiPrice=$allMoney/$amountUnit;
        $packAmount1=$amountUnit/$packAmount;
        DB::update("UPDATE NewStarfood.dbo.orderBYSS SET Amount=".$amountUnit.",DescRecord='دستی ویرایش شد',PackAmount=$packAmount1 ,Price=".$allMoney.",FiPack=".$fiPack.",
        PriceAfterTakhfif=$allMoney
        WHERE SnOrderBYSS=".$itemSn);
        $orderItems=DB::select("SELECT *,orderBYSS.Price AS totalPrice FROM NewStarfood.dbo.orderBYSS JOIN Shop.dbo.PubGoods ON orderBYSS.SnGood=PubGoods.GoodSn
                                JOIN (SELECT USN,UName AS firstUnit FROM Shop.dbo.PUBGoodUnits)a ON PubGoods.DefaultUnit=a.USN
                                LEFT JOIN (SELECT USN,UName AS secondUnit,SnGood FROM Shop.dbo.PUBGoodUnits
                                JOIN Shop.dbo.GoodUnitSecond ON PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit)b ON b.SnGood=PubGoods.GoodSn
                                WHERE orderBYSS.SnHDS=".$orderHDSsn);
        return Response::json($orderItems);
    }

    public function getAllNewOrders(Request $request)
    {
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=0 and isDistroy=0  order by orderHDSS.TimeStamp desc");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=0 and isDistroy=0");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS WHERE isDistroy=0 AND isSent=0");
        return Response::json([$orders,$allMoney,$allPayed]);
    }
    public function getAllSentOrders(Request $request)
    {
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=1 and isDistroy=0 and CONVERT(DATE,orderHDSS.TimeStamp)=CONVERT(DATE,CURRENT_TIMESTAMP)");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=1 and isDistroy=0 and CONVERT(DATE,orderHDSS.TimeStamp)=CONVERT(DATE,CURRENT_TIMESTAMP)");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS WHERE isDistroy=0 AND isSent=1 and CONVERT(DATE,orderHDSS.TimeStamp)=CONVERT(DATE,CURRENT_TIMESTAMP)");
        return Response::json([$orders,$allMoney,$allPayed]);
    }
    public function getAllTodayOrders(Request $request)
    {
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE CONVERT(DATE,orderHDSS.TimeStamp)=CONVERT(DATE,CURRENT_TIMESTAMP)");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE  CONVERT(DATE,orderHDSS.TimeStamp)=CONVERT(DATE,CURRENT_TIMESTAMP)");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS WHERE  CONVERT(DATE,orderHDSS.TimeStamp)=CONVERT(DATE,CURRENT_TIMESTAMP)");
        return Response::json([$orders,$allMoney,$allPayed]);
    }
    public function getAllRemainOrders(Request $request)
    {
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=1 or isDistroy=1) and CONVERT(DATE,OrderHDSS.TimeStamp)= CONVERT(DATE,CURRENT_TIMESTAMP) order by orderHDSS.TimeStamp desc");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
                            LEFT JOIN (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
                            LEFT JOIN (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE isSent=1 or isDistroy=1");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS WHERE isDistroy=1 or isSent=1");
        return Response::json([$orders,$allMoney,$allPayed]);
    }
    public function getOrderFromDate(Request $request)
    {
        $orderDate=$request->get("fromDate");
        $orderType=$request->get("orderType");

        if($orderType==0){
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
        left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
        left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$orderDate' order by OrderHDSS.TimeStamp desc");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
        left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
        left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$orderDate'");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  WHERE (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$orderDate'");
        return Response::json([$orders,$allMoney,$allPayed]);
        }elseif($orderType==1){
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$orderDate'  order by OrderHDSS.TimeStamp desc");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$orderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  WHERE (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$orderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }else{
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=1 and isDistroy=0) and OrderHDSS.OrderDate>='$orderDate'  order by OrderHDSS.TimeStamp desc");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=1 and isDistroy=0) and OrderHDSS.OrderDate>='$orderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  WHERE (isSent=1 and isDistroy=0) and OrderHDSS.OrderDate>='$orderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }

    }

    public function getOrderToDate(Request $request)
    {
        $firstOrderDate=$request->get("fromDate");
        $secondOrderDate=$request->get("toDate");
        $orderType=$request->get("orderType");
        if($orderType==0){
        $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
        left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
        left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
        $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
        left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
        left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
        $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  WHERE (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
        return Response::json([$orders,$allMoney,$allPayed]);
    }elseif($orderType==1){
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  WHERE (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }else{
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=1 and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=1 and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  WHERE (isSent=1) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }
    }
    public function getOrderByCustName(Request $request)
    {
        $firstOrderDate=$request->get("fromDate");
        $secondOrderDate=$request->get("toDate");
        $orderType=$request->get("orderType");
        $name=$request->get("name");
        if($orderType==0){
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType and isDistroy=0) AND Name LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType and isDistroy=0) AND Name LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN   WHERE Name LIKE '%$name%' and (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }else{
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType or isDistroy=$orderType) AND Name LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType or isDistroy=$orderType) AND Name LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN   WHERE Name LIKE '%$name%' and (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);   
        }
    }
    public function getOrdersByCustCode(Request $request)
    {
        $firstOrderDate=$request->get("fromDate");
        $secondOrderDate=$request->get("toDate");
        $orderType=$request->get("orderType");
        $code=$request->get("code");
        if($orderType==0){
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType and isDistroy=0) AND PCode LIKE '%$code%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType and isDistroy=0) AND PCode LIKE '%$code%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN   WHERE PCode LIKE '%$code%' and  (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }else{
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType or isDistroy=$orderType) AND PCode LIKE '%$code%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType or isDistroy=$orderType) AND PCode LIKE '%$code%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN   WHERE PCode LIKE '%$code%' and (isSent=$orderType or isDistroy=1) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }
    }
    public function getOrdersByPoshtibanName(Request $request)
    {
        $firstOrderDate=$request->get("fromDate");
        $secondOrderDate=$request->get("toDate");
        $orderType=$request->get("orderType");
        $name=$request->get("name");
        if($orderType==0){
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType and isDistroy=0) AND adminName LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType and isDistroy=0) AND adminName LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN   WHERE Name LIKE '%$name%' and (isSent=$orderType and isDistroy=0) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }else{
            $orders=DB::select("SELECT * FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder WHERE (isSent=$orderType or isDistroy=$orderType) AND adminName LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allMoney=DB::select("SELECT sum(allPrice) as sumAllMoney FROM NewStarfood.dbo.orderHDSS join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN 
            left join (SELECT SnHDS,SUM(Price) as allPrice from NewStarfood.dbo.OrderBYSS group by SnHDS)a ON a.SnHDS=OrderHDSS.SnOrder  WHERE (isSent=$orderType or isDistroy=$orderType) AND adminName LIKE '%$name%' and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            $allPayed=DB::select("SELECT SUM(payedMoney) AS payedMoney FROM NewStarfood.dbo.OrderHDSS  join Shop.dbo.Peopels ON orderHDSS.CustomerSn=PSN
            left join (SELECT customer_id,admin_id,returnState,CONCAT(name,lastName) as adminName from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)b on b.customer_id=Peopels.PSN    WHERE b.adminName LIKE '%$name%' and (isSent=$orderType or isDistroy=$orderType) and OrderHDSS.OrderDate>='$firstOrderDate' and OrderHDSS.OrderDate<='$secondOrderDate'");
            return Response::json([$orders,$allMoney,$allPayed]);
        }
    }

    public function checkOrderExistance(Request $request)
    {
        $hds=$request->get("hds");
        $allIsNot=0;// در صورتیک تمام کالاها وجود نداشته باشد
        $items=DB::select("SELECT * FROM NewStarfood.dbo.orderBYSS where SnHDS=".$hds);//آیتم های سفارش
        $notExistGoods=array();//آی دی کالاهای که وجود ندارند.
        $notExist=array();
        foreach($items as $item){
            $amount=DB::select("SELECT Amount FROM Shop.dbo.ViewGoodExistsInStock WHERE SnGood=$item->SnGood AND CompanyNo=5 AND FiscalYear=1399 AND SnStock=23");
            if($amount[0]->Amount < $item->Amount){
                array_push($notExistGoods,$item->SnGood);
            }
        }
        //اسم کالاهای که موجودی ندارند.
        $notExitGoodIds = implode(',', $notExistGoods);
        if($notExitGoodIds){
        $notExist=DB::select("SELECT GoodCde,GoodSn,GoodName,Amount FROM  Shop.dbo.PubGoods JOIN Shop.dbo.ViewGoodExistsInStock ON PubGoods.GoodSn=ViewGoodExistsInStock.SnGood WHERE  ViewGoodExistsInStock.CompanyNo=5 AND ViewGoodExistsInStock.FiscalYear='1399' AND SnStock=23 AND GoodSn in($notExitGoodIds)");
        }
        //چک کردن عدم موجودی تمامی آیتم های سفارش
        if(count($notExist)==count($items)){
            $allIsNot=1;
        }
        return Response::json([$notExist,$allIsNot]);
    }
    public function getPaymentInfo(Request $request)
    {
        $inVoiceNumber=$request->get("InVoiceNumber");
        $paymentInfo=DB::select("SELECT * FROM NewStarfood.dbo.star_paymentResponds WHERE =$inVoiceNumber");
        return Response::json($paymentInfo);
    }
    public function editorderSendState(Request $request)
    {
        $orderSn=$request->get("orderSn");
        $customerSn=$request->get("CustomerSn");

        if($request->get("orderState")==1 or $request->get("orderState")==0){
            DB::table("NewStarfood.dbo.orderHDSS")->where("SnOrder",$orderSn)->where("CustomerSn",$customerSn)->update(['isSent'=>$request->get("orderState")]);
        }else{
            DB::table("NewStarfood.dbo.orderHDSS")->where("SnOrder",$orderSn)->where("CustomerSn",$customerSn)->update(['isDistroy'=>$request->get("orderState")]);   
        }
        return Response::json(1);
    }
}
