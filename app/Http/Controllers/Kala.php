<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Goods;
use Response;
use Session;
use Cockie;
use DateTime;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
class Kala extends Controller {

    public function index(Request $request) {
        $withoutRestrictions=DB::select("SELECT PubGoods.GoodSn from Shop.dbo.PubGoods where GoodSn not in (select productId from NewStarfood.dbo.star_GoodsSaleRestriction) and CompanyNo=5 and PubGoods.GoodGroupSn>49");
        if(count($withoutRestrictions)>0){
            foreach ($withoutRestrictions as $kala) {
                DB::insert("INSERT INTO NewStarfood.dbo.star_GoodsSaleRestriction(maxSale,minSale,productId,overLine,callOnSale,zeroExistance,hideKala,activeTakhfifPercent,freeExistance,costLimit,costError,sabtDate ,costAmount ,inforsType,activePishKharid)
                VALUES(-1, 1, ".$kala->GoodSn.",0,0,0,0,0,0,0,'',null ,0,0,0)");
            }
        }

        $kala_list=DB::select("SELECT TOP 100 * FROM(
                        SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,V.SumAmount as Amount,V.FiscalYear,S.hideKala
                        ,GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn FROM Shop.dbo.PubGoods 
                        JOIN Shop.dbo.GoodGroups ON PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                        JOIN Shop.dbo.GoodPriceSale ON PubGoods.GoodSn=GoodPriceSale.SnGood
                        JOIN (SELECT Amount AS SumAmount,SnGood,FiscalYear FROM Shop.dbo.ViewGoodExists) V ON PubGoods.GoodSn=V.SnGood 
                        JOIN(SELECT productId,hideKala FROM NewStarfood.dbo.star_GoodsSaleRestriction)S ON PubGoods.GoodSn=S.productId
                        WHERE GoodName!='' AND NameGRP!='' AND GoodSn!=0 AND PubGoods.GoodGroupSn >49
                        AND PubGoods.CompanyNo=5 AND V.FiscalYear=1399)a
                        JOIN
                        (SELECT Max(FactorBYS.TimeStamp) AS lastDate, GoodSn FROM Shop.dbo.PubGoods  
                        JOIN Shop.dbo.FactorBYS ON PubGoods.GoodSn=FactorBYS.SnGood
                        WHERE GoodGroupSn>49 AND GoodSn!=0 group by GoodSn)b ON a.GoodSn=b.GoodSn order by Amount desc");
        // foreach ($kala_list as $kala) {
        //    $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
        // }
        $stocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock FROM Shop.dbo.Stocks WHERE SnStock!=0 AND NameStock!='' AND CompanyNo=5");

        // کیوری کالای در خواست شده 
         $requests=DB::select("SELECT * from(
        SELECT countRequest,a.productId,GoodName,GoodSn,TimeStamp FROM(SELECT COUNT(star_requestedProduct.id) AS countRequest,min(TimeStamp) as TimeStamp,productId  FROM NewStarfood.dbo.star_requestedProduct group by productId)a
        
        JOIN Shop.dbo.PubGoods ON PubGoods.GoodSn=a.productId )b  order by TimeStamp desc");

        // کیوری تخصیص تصویر کالا 
        $mainGroups=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=0 order by mainGroupPriority asc");

        // کیور پیش خرید 
        $factors=DB::table("NewStarfood.dbo.star_pishKharidFactorAfter")->join("Shop.dbo.Peopels","CustomerSn","=","PSN")->where("orderStatus",0)->select("*")->get();

        foreach ($factors as $factor) {
            $allMoney=0;
            $orders=DB::table("NewStarfood.dbo.star_pishKharidOrderAfter")->where("SnHDS",$factor->SnOrderPishKharidAfter)->select("*")->get();
            foreach ($orders as $order) {
                $allMoney+=$order->Price;
            }
            $factor->allMoney=$allMoney;
        }

        // کیور مربوط برند 
         $brands=DB::table("NewStarfood.dbo.star_brands")->select("*")->get();

        // کیور کالاهای شامل هشدار 
         $alarmStuff=DB::select("SELECT * FROM(
                    SELECT * FROM( SELECT GoodSn FROM Shop.dbo.PubGoods WHERE GoodSn
                    in( SELECT productId FROM NewStarfood.dbo.star_GoodsSaleRestriction WHERE AlarmAmount>0)
                    )a
                    JOIN Shop.dbo.ViewGoodExists on a.GoodSn=ViewGoodExists.SnGood WHERE ViewGoodExists.CompanyNo=5 and ViewGoodExists.FiscalYear=".Session::get("FiscallYear").")b 
                    JOIN (SELECT AlarmAmount,productId FROM NewStarfood.dbo.star_GoodsSaleRestriction)c on b.GoodSn=c.productId");
    
    foreach ($alarmStuff as $kala) {
        $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
    }
    
    $alarmedKala=array();
    foreach ($alarmStuff as $stuff) {
    if($stuff->AlarmAmount >= $stuff->Amount ){
    array_push($alarmedKala,$stuff->productId);

    } }
    $alarmedKalas=array();
    if(count($alarmedKala)>0){
    $alarmedKalas=DB::select("SELECT GoodName,Amount FROM Shop.dbo.PubGoods
                        JOIN Shop.dbo.ViewGoodExists on PubGoods.GoodSn=ViewGoodExists.SnGood 
                        WHERE ViewGoodExists.CompanyNo=5 and ViewGoodExists.FiscalYear=".Session::get("FiscallYear")."
                        AND GoodSn in(".implode(',',$alarmedKala).")");

    foreach ($alarmedKalas as $kala) {
        $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
    }
    }
// کیوری لیست گروپ 
       $mainGroups=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=0 order by mainGroupPriority asc");
        return view ('admin.listKala',['listKala'=>$kala_list,'stocks'=>$stocks, 'products'=>$requests, 'mainGroups'=>$mainGroups, 'factors'=>$factors, 'brands'=>$brands, 'alarmedKalas'=>$alarmedKalas]);
    }

    public function searchKalaByName(Request $request)
    {
        $name=$request->get('name');
        $kala_list=DB::select(" SELECT PubGoods.GoodName,PubGoods.GoodGroupSn,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,V.Amount,GoodCde,GoodGroups.NameGRP from Shop.dbo.PubGoods
                        join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                        JOIN (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood
                        left join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                        where GoodName!='' and NameGRP!='' and GoodSn!=0
                        and PubGoods.CompanyNo=5  and PubGoods.GoodGroupSn >49 and (GoodName like '%".$name."%' or GoodCde like '%".$name."%')") ;
        
        foreach ($kala_list as $kala) {
            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
        
        }
        return Response::json($kala_list);
    }

    public function searchKalaByCode(Request $request)
    {
        $code=$request->get('code');

        $kala_list=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodGroupSn,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,V.Amount,
                            GoodCde,GoodGroups.NameGRP FROM Shop.dbo.PubGoods JOIN Shop.dbo.GoodGroups
                            ON PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            JOIN Shop.dbo.GoodPriceSale ON PubGoods.GoodSn=GoodPriceSale.SnGood
                            JOIN (SELECT * FROM Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V ON PubGoods.GoodSn=V.SnGood
                            where GoodName!='' AND NameGRP!='' AND GoodSn!=0
                            AND PubGoods.CompanyNo=5 AND PubGoods.GoodGroupSn >49 AND (GoodName like '%".$code."%' or GoodCde like '%".$code."%')") ;
        
        foreach ($kala_list as $kala) {

            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
        
        }

        return Response::json($kala_list);
    }
    public function getStocks(Request $request)
    {
        $stocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks where SnStock!=0 and NameStock!='' and CompanyNo=5");
        return Response::json($stocks);
    }
    public function searchKalaByStock(Request $request)
    {
        $stockId=$request->get("stockId");
        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,V.Amount,
                        PubGoods.GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                        join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                        join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                        JOIN (select * from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood 
                        where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49
                        and PubGoods.CompanyNo=5 and V.FiscalYear=".Session::get("FiscallYear")." and V.SnStock=".$stockId);

        
        return Response::json($kalas);
    }
    public function searchKalaByExisanceOnStock(Request $request)
    {
        $exitsance=$request->get('stockExistance');
        $stockId=$request->get('stockId');
        if($exitsance==10){
            //all
            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,ViewGoodExistsInStock.Amount,
                        PubGoods.GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                        join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                        join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                        join Shop.dbo.ViewGoodExistsInStock ON PubGoods.GoodSn=ViewGoodExistsInStock.SnGood
                        where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49
                        and PubGoods.CompanyNo=5 and ViewGoodExistsInStock.FiscalYear=".Session::get("FiscallYear")." and ViewGoodExistsInStock.SnStock=
                        ".$stockId);

            return Response::json($kalas);

        }
        
        if($exitsance==1){
            //NO ZERO
            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,ViewGoodExistsInStock.Amount,
                            PubGoods.GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                            join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            join Shop.dbo.ViewGoodExistsInStock ON PubGoods.GoodSn=ViewGoodExistsInStock.SnGood
                            where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49
                            and PubGoods.CompanyNo=5 and ViewGoodExistsInStock.FiscalYear=".Session::get("FiscallYear")." and ViewGoodExistsInStock.Amount>0 and ViewGoodExistsInStock.SnStock=
                            ".$stockId);

            return Response::json($kalas);

        }
        if($exitsance==0){
            //ZERO
            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,ViewGoodExistsInStock.Amount,
                            PubGoods.GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                            join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            join Shop.dbo.ViewGoodExistsInStock ON PubGoods.GoodSn=ViewGoodExistsInStock.SnGood
                            where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49
                            and PubGoods.CompanyNo=5 and ViewGoodExistsInStock.FiscalYear=".Session::get("FiscallYear")." and ViewGoodExistsInStock.Amount=0 and ViewGoodExistsInStock.SnStock=
                            ".$stockId);

            return Response::json($kalas);
        }

    }

    public function listKalaFromPart($id,$picId)
    {
        $picId=$picId;
        $id=$id;
        $overLine=0;
        $callOnSale=0;
        $zeroExistance=0;
        $kalaList=DB::select("SELECT NewStarfood.dbo.star_add_homePart_stuff.*,GoodName,A.GoodSn,Price4,Price3,B.SUNAME secondUnit,UNAME as firstUnit,V.Amount,G.GoodGroupSn,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.activePishKharid
                        FROM NewStarfood.dbo.star_add_homePart_stuff
                        join (SELECT PubGoods.GoodSn,PubGoods.GoodGroupSn FROM Shop.dbo.GoodGroups join Shop.dbo.PubGoods on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn) G on G.GoodSn=NewStarfood.dbo.star_add_homePart_stuff.productId
                        join (SELECT PUBGoodUnits.UName,PubGoods.GoodSn from Shop.dbo.PUBGoodUnits join Shop.dbo.PubGoods on PUBGoodUnits.USN=PubGoods.DefaultUnit) A  on A.GoodSn=NewStarfood.dbo.star_add_homePart_stuff.productId
                        JOIN (SELECT PubGoods.GoodName,PubGoods.GoodSn FROM Shop.dbo.PubGoods) C on c.GoodSn=NewStarfood.dbo.star_add_homePart_stuff.productId
                        JOIN (select * from Shop.dbo.ViewGoodExists) V on NewStarfood.dbo.star_add_homePart_stuff.productId=V.SnGood
                        left JOIN (Select GoodPriceSale.Price4,GoodPriceSale.Price3,GoodPriceSale.SnGood from Shop.dbo.GoodPriceSale) S on S.SnGood=NewStarfood.dbo.star_add_homePart_stuff.productId
                        left JOIN (SELECT GoodUnitSecond.SnGoodUnit,PUBGoodUnits.UName as SUNAME,GoodUnitSecond.SnGood from Shop.dbo.GoodUnitSecond join Shop.dbo.PUBGoodUnits on GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN) B on NewStarfood.dbo.star_add_homePart_stuff.productId=B.SnGood
                        left join NewStarfood.dbo.star_GoodsSaleRestriction on NewStarfood.dbo.star_add_homePart_stuff.productId=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                        where NewStarfood.dbo.star_add_homePart_stuff.partPic=".$picId." and V.FiscalYear=".Session::get("FiscallYear")."  and NewStarfood.dbo.star_add_homePart_stuff.productId not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) order by Amount desc");
        
        foreach ($kalaList as $kala) {

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

        foreach ($kalaList as $kala) {
            $overLine=0;
            $callOnSale=0;
            $zeroExistance=0;
            $freeExistance=0;
            $activePishKharid=0;
            $exist="NO";
            $favorits=DB::select("SELECT * FROM NewStarfood.dbo.star_Favorite");

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

            $restrictionState=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$kala->GoodSn)->select("overLine","callOnSale","zeroExistance",'freeExistance','activePishKharid')->get();
            if(count($restrictionState)>0){
                foreach($restrictionState as $rest){
                    if($rest->overLine==1){
                        $overLine=1;
                    }
                    if($rest->callOnSale==1){
                        $callOnSale=1;
                    }
                    if($rest->zeroExistance==1){
                        $zeroExistance=1;
                    }
                    if($rest->activePishKharid==1){
                        $activePishKharid=1;
                    }
                    if($rest->freeExistance==1){
                        $freeExistance=1;
                    }
                }
            }
            $boughtKalas=DB::select("select  FactorStar.*,orderStar.* from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and orderStatus=0");
            $orderBYSsn;
            $secondUnit;
            $amount;
            $packAmount;
            foreach ($boughtKalas as $boughtKala) {
                $orderBYSsn=$boughtKala->SnOrderBYS;
                $orderGoodSn=$boughtKala->SnGood;
                $amount=$boughtKala->Amount;
                $packAmount=$boughtKala->PackAmount;
                $secondUnits=DB::select("select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood=".$orderGoodSn);
                foreach ($secondUnits as $unit) {
                    $secondUnit=$unit->UName;
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
            $boughtKalas=DB::select("select  star_pishKharidFactor.*,star_pishKharidOrder.* from NewStarfood.dbo.star_pishKharidFactor join NewStarfood.dbo.star_pishKharidOrder on star_pishKharidFactor.SnOrderPishKharid=star_pishKharidOrder.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and  orderStatus=0");
            $orderBYSsn;
            $secondUnit;
            $amount;
            $packAmount;
            foreach ($boughtKalas as $boughtKala) {
                $orderBYSsn=$boughtKala->SnOrderBYSPishKharid;
                $orderGoodSn=$boughtKala->SnGood;
                $amount=$boughtKala->Amount;
                $packAmount=$boughtKala->PackAmount;
                $secondUnits=DB::select("select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods
                join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood
                join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood=".$orderGoodSn);
                if(count($secondUnits)>0){
                    foreach ($secondUnits as $unit) {
                        $secondUnit=$unit->UName;
                    }
                }else{
                    $secondUnit=$kala->firstUnit;
                }
            }
            if(count($boughtKalas)>0){
                $kala->bought="Yes";
                $kala->SnOrderBYS=$orderBYSsn;
                $kala->secondUnit=$secondUnit;
                $kala->Amount=$amount;
                $kala->PackAmount=$packAmount;
                $kala->UName=$kala->firstUnit;
            }else{
                $kala->bought="No";
            }
            $kala->favorite=$exist;
            $kala->overLine=$overLine;
            $kala->callOnSale=$callOnSale;
            $kala->activePishKharid=$activePishKharid;
            $kala->freeExistance=$freeExistance;
            if($zeroExistance==1){
            $kala->Amount=0;
            }
        }
		$logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view('kala.kalaFromPart',['kala'=>$kalaList,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos]);
    }
    public function editKala(Request $request){
        $kalaId=$request->get('kalaId');
        $exactKalaId=$kalaId;
        $maxSaleOfAll=0;
        $showTakhfifPercent=0;
        $kala=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PubGoods.Price,PubGoods.price2,B.SUNAME,B.AmountUnit, GoodGroups.NameGRP,PUBGoodUnits.UName,star_desc_product.descProduct from Shop.dbo.PubGoods
        join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
        inner join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
        LEFT JOIN NewStarfood.dbo.star_desc_product ON PubGoods.GoodSn=star_desc_product.GoodSn
        left JOIN (SELECT GoodUnitSecond.AmountUnit,GoodUnitSecond.SnGoodUnit,PUBGoodUnits.UName as SUNAME,GoodUnitSecond.SnGood from Shop.dbo.GoodUnitSecond join Shop.dbo.PUBGoodUnits on GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN) B on PubGoods.GoodSn=B.SnGood
        where PubGoods.GoodSn=".$exactKalaId);
        $exactKala;
        foreach ($kala as $k) {
            $exactKala=$k;
           $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$k->GoodSn);
            if(count($subUnitStuff)>0){
                foreach ($subUnitStuff as $stuff) {
                    $exactKala->secondUnit=$stuff->secondUnit;
                    $exactKala->amountUnit=$stuff->AmountUnit;
                }
            }else{
                $exactKala->secondUnit="تعریف نشده است";
                $exactKala->amountUnit="تعریف نشده است";
            }
           $priceStuff= DB::select("SELECT GoodPriceSale.Price3,GoodPriceSale.Price4 FROM Shop.dbo.GoodPriceSale WHERE GoodPriceSale.SnGood=".$k->GoodSn);
           foreach ($priceStuff as $stuff) {
            $exactKala->mainPrice=$stuff->Price3;
            $exactKala->overLinePrice=$stuff->Price4;
            }
            $webSpecialSettings=DB::table("NewStarfood.dbo.star_webSpecialSetting")->select('maxSale')->get();
            foreach ($webSpecialSettings as $special) {
                $maxSaleOfAll=$special->maxSale;
            }
            $restrictSaleStuff=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$k->GoodSn)->select("minSale","maxSale","overLine","callOnSale","zeroExistance","hideKala",
                                                                                            "activeTakhfifPercent",'inforsType',"freeExistance",'costLimit','costError','costAmount','activePishKharid','alarmAmount')->get();
            if(count($restrictSaleStuff)>0){
                foreach ($restrictSaleStuff as $saleStuff) {
                $exactKala->minSale=$saleStuff->minSale;
                $exactKala->showTakhfifPercent=$saleStuff->activeTakhfifPercent;
                    if($saleStuff->maxSale>-1){
                        $exactKala->maxSale=$saleStuff->maxSale;

                    }else{
                        $exactKala->maxSale=$maxSaleOfAll;
                    }
                $exactKala->callOnSale=$saleStuff->callOnSale;
                $exactKala->overLine=$saleStuff->overLine;
                $exactKala->zeroExistance=$saleStuff->zeroExistance;
                $exactKala->hideKala=$saleStuff->hideKala;
                $exactKala->freeExistance=$saleStuff->freeExistance;
                $exactKala->costLimit=$saleStuff->costLimit;
                $exactKala->costError=$saleStuff->costError;
                $exactKala->costAmount=$saleStuff->costAmount;
                $exactKala->inforsType=$saleStuff->inforsType;
                $exactKala->activePishKharid=$saleStuff->activePishKharid;
                $exactKala->alarmAmount=$saleStuff->alarmAmount;
                }
            }else{
                $exactKala->freeExistance=0;
                $exactKala->minSale=1;
                $exactKala->maxSale=$maxSaleOfAll;
                $exactKala->callOnSale=0;
                $exactKala->overLine=0;
                $exactKala->zeroExistance=0;
                $exactKala->hideKala=0;
                $exactKala->showTakhfifPercent=0;
                $exactKala->costLimit=0;
                $exactKala->costError="ندارد";
                $exactKala->costAmount=0;
                $exactKala->inforsType=0;
                $exactKala->activePishKharid=0;
                $exactKala->alarmAmount=0;
            }
        }
        $mainGroupList=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=0");
        $addedKala=DB::select("select firstGroupId,product_id from NewStarfood.dbo.star_add_prod_group");
        $exist="";
        foreach($kala as $kl){
            foreach($mainGroupList as $group){
                foreach($addedKala as $addkl){
                    if($addkl->firstGroupId==$group->id and $kl->GoodSn==$addkl->product_id){
                        $exist='ok';
                        break;
                    }else{
                        $exist='no';
                    }
                }
                $group->exist=$exist;
            }
        }
        $kalaPriceHistory=DB::table("NewStarfood.dbo.star_KalaPriceHistory")->join("NewStarfood.dbo.admin",'admin.id','=','star_KalaPriceHistory.userId')->where('productId',$kalaId)->select("admin.*","star_KalaPriceHistory.*")->get();
        $infors=DB::select("select * from Shop.dbo.infors where CompanyNo=5 and TypeInfor=5");
        $assameKalas=DB::table("NewStarfood.dbo.star_assameKala")->where("mainId",$kalaId)->leftjoin("Shop.dbo.PubGoods","assameId","=","GoodSn")->select("*")->get();
        $stocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks where SnStock not in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kalaId.") and SnStock!=0 and NameStock!='' and CompanyNo=5");
        $addedStocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks
                        JOIN star_addedStock on Stocks.SnStock=star_addedStock.stockId where star_addedStock.productId=".$kalaId);
         return view('admin.editKala',['kala'=>$exactKala,'mainGroupList'=>$mainGroupList,'stocks'=>$stocks,'assameKala'=>$assameKalas,'addedStocks'=>$addedStocks,'infors'=>$infors,'priceHistory'=>$kalaPriceHistory]);
       // return Response::json([$exactKala,$mainGroupList, $stocks, $assameKalas,$addedStocks,$infors, $kalaPriceHistory]);
    }

    public function editKalaModal(Request $request){
        $kalaId=$request->get('kalaId');
        $exactKalaId=$kalaId;
        $maxSaleOfAll=0;
        $showTakhfifPercent=0;
        $kala=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PubGoods.Price,PubGoods.price2,B.SUNAME,B.AmountUnit, GoodGroups.NameGRP,PUBGoodUnits.UName,star_desc_product.descProduct from Shop.dbo.PubGoods
        join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
        inner join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
        LEFT JOIN NewStarfood.dbo.star_desc_product ON PubGoods.GoodSn=star_desc_product.GoodSn
        left JOIN (SELECT GoodUnitSecond.AmountUnit,GoodUnitSecond.SnGoodUnit,PUBGoodUnits.UName as SUNAME,GoodUnitSecond.SnGood from Shop.dbo.GoodUnitSecond join Shop.dbo.PUBGoodUnits on GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN) B on PubGoods.GoodSn=B.SnGood
        where PubGoods.GoodSn=".$exactKalaId);
        $exactKala;
        foreach ($kala as $k) {
            $exactKala=$k;
           $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$k->GoodSn);
            if(count($subUnitStuff)>0){
                foreach ($subUnitStuff as $stuff) {
                    $exactKala->secondUnit=$stuff->secondUnit;
                    $exactKala->amountUnit=$stuff->AmountUnit;
                }
            }else{
                $exactKala->secondUnit="تعریف نشده است";
                $exactKala->amountUnit="تعریف نشده است";
            }
           $priceStuff= DB::select("SELECT GoodPriceSale.Price3,GoodPriceSale.Price4 FROM Shop.dbo.GoodPriceSale WHERE GoodPriceSale.SnGood=".$k->GoodSn);
           foreach ($priceStuff as $stuff) {
            $exactKala->mainPrice=$stuff->Price3;
            $exactKala->overLinePrice=$stuff->Price4;
            }
            $webSpecialSettings=DB::table("NewStarfood.dbo.star_webSpecialSetting")->select('maxSale')->get();
            foreach ($webSpecialSettings as $special) {
                $maxSaleOfAll=$special->maxSale;
            }
            $restrictSaleStuff=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$k->GoodSn)->select("minSale","maxSale","overLine","callOnSale","zeroExistance","hideKala",
                                                                                            "activeTakhfifPercent",'inforsType',"freeExistance",'costLimit','costError','costAmount','activePishKharid','alarmAmount')->get();
            if(count($restrictSaleStuff)>0){
                foreach ($restrictSaleStuff as $saleStuff) {
                $exactKala->minSale=$saleStuff->minSale;
                $exactKala->showTakhfifPercent=$saleStuff->activeTakhfifPercent;
                    if($saleStuff->maxSale>-1){
                        $exactKala->maxSale=$saleStuff->maxSale;

                    }else{
                        $exactKala->maxSale=$maxSaleOfAll;
                    }
                $exactKala->callOnSale=$saleStuff->callOnSale;
                $exactKala->overLine=$saleStuff->overLine;
                $exactKala->zeroExistance=$saleStuff->zeroExistance;
                $exactKala->hideKala=$saleStuff->hideKala;
                $exactKala->freeExistance=$saleStuff->freeExistance;
                $exactKala->costLimit=$saleStuff->costLimit;
                $exactKala->costError=$saleStuff->costError;
                $exactKala->costAmount=$saleStuff->costAmount;
                $exactKala->inforsType=$saleStuff->inforsType;
                $exactKala->activePishKharid=$saleStuff->activePishKharid;
                $exactKala->alarmAmount=$saleStuff->alarmAmount;
                }
            }else{
                $exactKala->freeExistance=0;
                $exactKala->minSale=1;
                $exactKala->maxSale=$maxSaleOfAll;
                $exactKala->callOnSale=0;
                $exactKala->overLine=0;
                $exactKala->zeroExistance=0;
                $exactKala->hideKala=0;
                $exactKala->showTakhfifPercent=0;
                $exactKala->costLimit=0;
                $exactKala->costError="ندارد";
                $exactKala->costAmount=0;
                $exactKala->inforsType=0;
                $exactKala->activePishKharid=0;
                $exactKala->alarmAmount=0;
            }
        }
        $mainGroupList=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=0");
        $addedKala=DB::select("select firstGroupId,product_id from NewStarfood.dbo.star_add_prod_group");
        $exist="";
        foreach($kala as $kl){
            foreach($mainGroupList as $group){
                foreach($addedKala as $addkl){
                    if($addkl->firstGroupId==$group->id and $kl->GoodSn==$addkl->product_id){
                        $exist='ok';
                        break;
                    }else{
                        $exist='no';
                    }
                }
                $group->exist=$exist;
            }
        }
        $kalaPriceHistory=DB::table("NewStarfood.dbo.star_KalaPriceHistory")->join("NewStarfood.dbo.admin",'admin.id','=','star_KalaPriceHistory.userId')->where('productId',$kalaId)->select("admin.*","star_KalaPriceHistory.*")->get();
        $infors=DB::select("select * from Shop.dbo.infors where CompanyNo=5 and TypeInfor=5");
        $assameKalas=DB::table("NewStarfood.dbo.star_assameKala")->where("mainId",$kalaId)->leftjoin("Shop.dbo.PubGoods","assameId","=","GoodSn")->select("*")->get();
        $stocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks where SnStock not in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kalaId.") and SnStock!=0 and NameStock!='' and CompanyNo=5");
        $addedStocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks
                        JOIN star_addedStock on Stocks.SnStock=star_addedStock.stockId where star_addedStock.productId=".$kalaId);
       return Response::json([$exactKala,$mainGroupList, $stocks, $assameKalas,$addedStocks,$infors, $kalaPriceHistory]);
    }


    public function restrictSale(Request $request){
        $overLine1=$request->get('overLine');
        $freeExistance=$request->get('freeExistance');
        $callOnSale=$request->get('callOnSale');
        $zeroExistance=$request->get('zeroExistance');
        $hideKala=$request->get("hideKala");
        $productId=$request->get('kalaId');
        $showTakhfifPercent=$request->get('activeTakhfifPercent');
        $costLimit=$request->get('costLimit');
        $costAmount=$request->get('costAmount');
        $inforsType=$request->get('infors');
        $costErrorContent=$request->get('costErrorContent');
        $activePishKharid=$request->get('activePishKharid');
        $alarmAmount=$request->get('alarmAmount');
        $overLine=0;

        if($showTakhfifPercent){
            $showTakhfifPercent=1;
            $overLine=1;
        }else{
            $showTakhfifPercent=0;
        }

        if($freeExistance){
            $freeExistance=1;
        }else{
            $freeExistance=0;
        }

        if($hideKala){
            $hideKala=1;
        }else{
            $hideKala=0;
        }
        if($activePishKharid){
            $activePishKharid=1;
        }else{
            $activePishKharid=0;
        }

        if($overLine1 or $overLine==1){
            $overLine=1;
        }else{
            $overLine=0;
        }

        if($callOnSale){
            $callOnSale=1;
        }else{
            $callOnSale=0;
        }

        if($zeroExistance){
            $zeroExistance=1;
        }else{
            $zeroExistance=0;
        }

        $maxSaleOfAll=0;
        $webSpecialSettings=DB::table("NewStarfood.dbo.star_webSpecialSetting")->select('maxSale')->get();
        foreach ($webSpecialSettings as $special) {
            $maxSaleOfAll=$special->maxSale;
        }
        $checkExistance = DB::select("SELECT * FROM NewStarfood.dbo.star_GoodsSaleRestriction where productId=".$productId);
        if((count($checkExistance)>0)){
            DB::update("UPDATE NewStarfood.dbo.star_GoodsSaleRestriction  SET overLine=".$overLine.",callOnSale=".$callOnSale.",zeroExistance=".$zeroExistance.",
            hideKala=".$hideKala.",freeExistance=".$freeExistance.",activeTakhfifPercent=".$showTakhfifPercent.",costLimit=".$costLimit."
            ,costError='$costErrorContent',costAmount=$costAmount,inforsType=$inforsType,activePishKharid=$activePishKharid,alarmAmount=$alarmAmount  WHERE productId=".$productId);
        }else{
            DB::insert("INSERT INTO NewStarfood.dbo.star_GoodsSaleRestriction(maxSale, minSale, productId,overLine,callOnSale,zeroExistance,hideKala,activeTakhfifPercent) VALUES(".$maxSaleOfAll.", 1, ".$productId.",".$overLine.",".$callOnSale.",".$zeroExistance.",".$hideKala.",".$showTakhfifPercent.")");
        }
        return Response::json($hideKala);
    }


    public function changeKalaPic(Request $request)
    {
        $kalaId=$request->get('kalaId');
        $picture1=$request->file('firstPic');
        $picture2=$request->file('secondPic');
        $picture3=$request->file('thirthPic');
        $picture4=$request->file('fourthPic');
        $picture5=$request->file('fifthPic');
        $filename1="";
        $filename2="";
        $filename3="";
        $filename4="";
        $filename5="";
        if($picture1){
        $filename1=$picture1->getClientOriginalName();
        $filename1=$kalaId.'_1.'.'jpg';
        $picture1->move("resources/assets/images/kala/",$filename1);
        }
        if($picture2){
        $filename2=$picture2->getClientOriginalName();
        $filename2=$kalaId.'_2.'.'jpg';
        $picture2->move("resources/assets/images/kala/",$filename2);
        }
        if($picture3){
        $filename3=$picture3->getClientOriginalName();
        $filename3=$kalaId.'_3.'.'jpg';
        $picture3->move("resources/assets/images/kala/",$filename3);
        }
        if($picture4){
        $filename4=$picture4->getClientOriginalName();
        $filename4=$kalaId.'_4.'.'jpg';
        $picture4->move("resources/assets/images/kala/",$filename4);
        }
        if($picture5){
        $filename5=$picture5->getClientOriginalName();
        $filename5=$kalaId.'_5.'.'jpg';
        $picture5->move("resources/assets/images/kala/",$filename5);
        }
        DB::insert("INSERT INTO NewStarfood.dbo.starPicAddress(goodId,picAddress,picAddress2,picAddress3,picAddress4,picAddress5) VALUES(".$kalaId.",'".$filename1."','".$filename2."','".$filename3."','".$filename4."','".$filename5."')");
        return Response::json('good');
    }


    public function descKala($groupId,$id)
    {
        $groupId=$groupId;
        $productId=$id;
        $overLine=0;
        $callOnSale=0;
        $zeroExistance=0;
        //without stocks----------------------------------------
        $listKala=DB::select("SELECT  GoodGroups.NameGRP,PubGoods.GoodCde,PubGoods.GoodSn,PubGoods.GoodName,GoodPriceSale.Price3,GoodPriceSale.Price4,PUBGoodUnits.UName as UNAME,A.Amount as AmountExist,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.freeExistance,star_GoodsSaleRestriction.activePishKharid  from Shop.dbo.PubGoods
        join Shop.dbo.GoodGroups on GoodGroups.GoodGroupSn=PubGoods.GoodGroupSn
        left join Shop.dbo.GoodPriceSale on GoodPriceSale.SnGood=PubGoods.GoodSn
        left join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
        join (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") A on PubGoods.GoodSn=A.SnGood
        left join NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
         WHERE PubGoods.GoodSn=".$productId." and PubGoods.GoodGroupSn>49");

        foreach ($listKala as $kala) {

            $kala->AmountExist+DB::select("SELECT SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

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
            $exist="NO";
            $overLine=0;
            $callOnSale=0;
            $zeroExistance=0;
            $discriptKala="توضیحاتی ندارد";
            $discription=DB::select("SELECT descProduct FROM NewStarfood.dbo.star_desc_Product where GoodSn=".$kala->GoodSn);
            foreach ($discription as $disc) {
                $discriptKala=$disc->descProduct;
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
            $kala->descKala=$discriptKala;
            $favorits=DB::select("SELECT * FROM NewStarfood.dbo.star_Favorite");
            foreach ( $favorits as $favorite) {
                if($kala->GoodSn==$favorite->goodSn and $favorite->customerSn==Session::get('psn')){
                    $exist='YES';
                    break;
                }else{
                    $exist='NO';
                }
            }
            $boughtKalas=DB::select("select  FactorStar.*,orderStar.* from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn."  and orderStatus=0");
            $orderBYSsn;
            $secondUnit;
            $amount;
            $packAmount;
            $defaultUnit;
            $defaultUnits=DB::select("select defaultUnit from Shop.dbo.PubGoods where PubGoods.GoodSn=".$kala->GoodSn);
            foreach ($defaultUnits as $unit) {
                $defaultUnit=$unit->defaultUnit;
            }
            foreach ($boughtKalas as $boughtKala) {
                $orderBYSsn=$boughtKala->SnOrderBYS;
                $orderGoodSn=$boughtKala->SnGood;
                $amount=$boughtKala->Amount;
                $packAmount=$boughtKala->PackAmount;
                $secondUnits=DB::select("select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood=".$orderGoodSn);
            if(count($secondUnits)>0){
                foreach ($secondUnits as $unit) {
                $secondUnit=$unit->UName;
            }
            }else{
                $secondUnit=$defaultUnit;
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
            $kala->AmountExist=0;
            }
            $preBoughtKalas=DB::select("select  star_pishKharidFactor.*,star_pishKharidOrder.* from NewStarfood.dbo.star_pishKharidFactor join NewStarfood.dbo.star_pishKharidOrder on star_pishKharidFactor.SnOrderPishKharid=star_pishKharidOrder.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and  orderStatus=0");
            $orderBYSsn;
            $secondUnit;
            $amount;
            $packAmount;
            foreach ($preBoughtKalas as $boughtKala) {
                $orderBYSsn=$boughtKala->SnOrderBYSPishKharid;
                $orderGoodSn=$boughtKala->SnGood;
                $amount=$boughtKala->Amount;
                $packAmount=$boughtKala->PackAmount;
                $secondUnits=DB::select("select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods
                join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood
                join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood=".$orderGoodSn);
                if(count($secondUnits)>0){
                    foreach ($secondUnits as $unit) {
                        $secondUnit=$unit->UName;
                    }
                }else{
                    $secondUnit=$kala->UName;
                }
            }
            if(count($preBoughtKalas)>0){
                $kala->preBought="Yes";
                $kala->SnOrderBYS=$orderBYSsn;
                $kala->secondUnit=$secondUnit;
                $kala->Amount=$amount;
                $kala->PackAmount=$packAmount;
                $kala->UName=$kala->UNAME;
            }else{
                $kala->preBought="No";
            }
        }
        $mainGroup=DB::select("select title from NewStarfood.dbo.Star_Group_Def where Star_Group_Def.id=".$groupId);
        $groupName="";
        foreach ($mainGroup as $gr) {
            $groupName=$gr->title;
        }
        // $assameKalas=DB::table("NewStarfood.dbo.star_assameKala")->join("Shop.dbo.PubGoods","PubGoods.GoodSn",'=','star_assameKala.assameId')->where("mainId",$productId)->select("*")->take(4)->get();
        $assameKalas=DB::select("select TOP 4 * from Shop.dbo.PubGoods join NewStarfood.dbo.star_assameKala on PubGoods.GoodSn=star_assameKala.assameId WHERE mainId=".$productId." and PubGoods.GoodSn not in (select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1)");
		$logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view('kala.descKala',['product'=>$listKala,'groupName'=>$groupName,'assameKalas'=>$assameKalas,'mainGroupId'=>$groupId,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos]);
    }
    public function listSubKala($groupId)
    {
        $overLine=0;
        $callOnSale=0;
        $zeroExistance=0;
    //whithout sotocks
       $listKala= DB::select("SELECT  GoodSn,GoodName,UName,Price3,Price4,SnGoodPriceSale,IIF(csn>0,'YES','NO') favorite,productId,IIF(ISNULL(productId,0)=0,0,1) as requested,IIF(zeroExistance=1,0,IIF(ISNULL(SnOrderBYS,0)=0,SumAmount,BoughtAmount)) Amount,IIF(ISNULL(SnOrderBYS,0)=0,'No','Yes') bought,callOnSale,SnOrderBYS,BoughtAmount,PackAmount,overLine,secondUnit,freeExistance,activeTakhfifPercent,activePishKharid FROM(

                            SELECT  PubGoods.GoodSn,PubGoods.GoodName,PUBGoodUnits.UName,csn,D.productId,GoodPriceSale.Price3,GoodPriceSale.Price4,GoodPriceSale.SnGoodPriceSale
                                                    ,SumAmount,E.zeroExistance,E.callOnSale,SnOrderBYS,BoughtAmount,PackAmount,E.overLine,secondUnit,star_GoodsSaleRestriction.freeExistance,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.activePishKharid FROM Shop.dbo.PubGoods
                                                    JOIN NewStarfood.dbo.star_GoodsSaleRestriction ON PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                                                    JOIN NewStarfood.dbo.star_add_prod_group ON PubGoods.GoodSn=product_id
                                                    JOIN NewStarfood.dbo.Star_Group_DEF ON Star_Group_DEF.id=star_add_prod_group.firstGroupId
                                                    JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
                                                    join(select SUM(Amount) as SumAmount,SnGood from Shop.dbo.ViewGoodExistsInStock
                                                    where  ViewGoodExistsInStock.FiscalYear=".Session::get('FiscallYear')." and ViewGoodExistsInStock.CompanyNo=5 and SnStock=23 group by SnGood)B on PubGoods.GoodSn=B.SnGood 
                                                    left JOIN (select  SnOrderBYS,SnGood,Amount as BoughtAmount,PackAmount from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and orderStatus=0)f on f.SnGood=PubGoods.GoodSn
                                                    left join (select goodSn as csn from NewStarfood.dbo.star_Favorite WHERE star_Favorite.customerSn=".Session::get('psn').")C on PubGoods.GoodSn=C.csn
                                                    LEFT JOIN (select productId from NewStarfood.dbo.star_requestedProduct where customerId=".Session::get('psn').")D on PubGoods.GoodSn=D.productId
                                                    left join (select zeroExistance,callOnSale,overLine,productId from NewStarfood.dbo.star_GoodsSaleRestriction)E on E.productId=PubGoods.GoodSn
                                                    JOIN (SELECT * from Shop.dbo.ViewGoodExists) A ON PubGoods.GoodSn=A.SnGood
                                                    LEFT JOIN Shop.dbo.GoodPriceSale ON GoodPriceSale.SnGood=PubGoods.GoodSn
                                                    left join (select GoodUnitSecond.AmountUnit,SnGood,UName as secondUnit from Shop.dbo.GoodUnitSecond
                                                    join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5)G on G.SnGood=PUbGoods.GoodSn
                                                    WHERE firstGroupId=$groupId and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) and A.FiscalYear=".Session::get('FiscallYear')." and A.CompanyNo=5
                                                    GROUP BY PubGoods.GoodSn,E.zeroExistance,E.callOnSale,E.overLine,BoughtAmount,PackAmount,SnOrderBYS,secondUnit,D.productId,PubGoods.GoodName,SumAmount,GoodPriceSale.Price3,GoodPriceSale.Price4,GoodPriceSale.SnGoodPriceSale,PUBGoodUnits.UName,star_GoodsSaleRestriction.activeTakhfifPercent,
                                                    star_GoodsSaleRestriction.freeExistance,star_GoodsSaleRestriction.activePishKharid,csn 
                                                    ) A order by SumAmount desc") ;
                    
        //foreach ($listKala as $kala) {
       //     $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock
		//	where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock
		//	where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
       // }

       $listSubGroups=DB::select("SELECT 
       id,
       title
       ,show_hide
       ,selfGroupId
       ,percentTakhf
       ,secondBranchId
       ,thirdBranchId
       ,mainGroupPriority
       ,subGroupPriority FROM NewStarfood.dbo.Star_Group_DEF where selfGroupId=".$groupId." order by subGroupPriority desc");
       $currency=1;
       $currencyName="ریال";
       $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
       foreach ($currencyExistance as $cr) {
           $currency=$cr->currency;
       }
       if($currency==10){
           $currencyName="تومان";
       }

      $logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view('groupPart.groupPart',['listKala'=>$listKala,'listGroups'=>$listSubGroups,'mainGrId'=>$groupId,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos]);
    }
    public function getListKala(Request $request)
    {
        $listKala=DB::select("SELECT GoodSn,GoodName,price,Price2 FROM Shop.dbo.PubGoods WHERE CompanyNo=5 and GoodSn!=0 and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )");
        return Response::json($listKala);
    }
    public function getSelectedListKala(Request $request)
    {
        $ids=$request->get('kalaIds');
        $kalas= array( );
        $kala;
        foreach ($ids as $id) {
            $kala=DB::select("SELECT GoodSn,GoodName,price,Price2 FROM Shop.dbo.PubGoods WHERE CompanyNo=5 and GoodSn=".$id);
            $kalas=$kala;
        }
        return Response::json($kalas);
    }
    public function changeKalaPartPriority(Request $request)
    {
        $partId=$request->get('partId');
        $kalaId=$request->get('kalaId');
        $priorityState=$request->get('priority');
        $kala = DB::select("SELECT priority FROM NewStarfood.dbo.star_add_homePart_stuff WHERE productId=".$kalaId." and homepartId=".$partId);
        $countKala = DB::select("SELECT COUNT(id) as countKala FROM NewStarfood.dbo.star_add_homePart_stuff WHERE homepartId=".$partId);
        $countAllKala=0;
        foreach ($countKala as $countKl) {
            $countAllKala=$countKl->countKala;
        }
        $priority=0;
        foreach ($kala as $k) {
            $priority=$k->priority;
        }
        if ($priorityState=='up') {
            if($priority>1){
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority-1).' WHERE homepartId='.$partId.'and productId='.$kalaId);
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority).' WHERE homepartId='.$partId.'and productId!='.$kalaId.' and priority='.($priority-1));
            $kala = DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff join Shop.dbo.PubGoods on star_add_homePart_stuff.productId=PubGoods.GoodSn WHERE homepartId=".$partId." order by priority asc");
            return Response::json($kala);
        }else{
            $kala = DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff join Shop.dbo.PubGoods on star_add_homePart_stuff.productId=PubGoods.GoodSn WHERE homepartId=".$partId." order by priority asc");
            return Response::json($kala);
        }

        } else {
            if($priority<$countAllKala and $priority>0){
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority+1).' WHERE homepartId='.$partId.'and productId='.$kalaId);
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority).' WHERE homepartId='.$partId.'and productId!='.$kalaId.' and priority='.($priority+1));
            $kala = DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff join Shop.dbo.PubGoods on star_add_homePart_stuff.productId=PubGoods.GoodSn WHERE homepartId=".$partId." order by priority asc");
            return Response::json($kala);
            }else{
                $kala = DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff join Shop.dbo.PubGoods on star_add_homePart_stuff.productId=PubGoods.GoodSn WHERE homepartId=".$partId." order by priority asc");
                return Response::json($kala);

            }

        }

    }
    public function getListKalaOnePic(Request $request)
    {
        $partPic= $request->get("partPic");
        $addedKala=DB::select("SELECT PubGoods.GoodSn,PubGoods.GoodName FROM Shop.dbo.PubGoods INNER JOIN NewStarfood.dbo.star_add_homePart_stuff ON PubGoods.GoodSn=star_add_homePart_stuff.productId WHERE partPic=".$partPic." and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) ORDER BY priority ASC");
        $allKalas=DB::select("SELECT GoodSn,GoodName FROM Shop.dbo.PubGoods WHERE GoodSn!=0 and CompanyNo=5 and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )");
        return Response::json(['addedKala'=>$addedKala,'allKala'=>$allKalas]);
    }
    public function searchKalas(Request $request) {
        $term = $request->get('searchTerm');
        $id= $request->get('id');
        if($id){
                $kalas=DB::select("SELECT Shop.dbo.PubGoods.GoodName,Shop.dbo.PubGoods.GoodSn from Shop.dbo.PubGoods
                join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                where PubGoods.companyNo=5 and Shop.dbo.PubGoods.GoodSn not in (
                    SELECT Shop.dbo.PubGoods.GoodSn from Shop.dbo.PubGoods
                    join NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=star_add_prod_group.product_id
                    where companyNo=5 and star_add_prod_group.secondGroupId=".$id.")
                    and Shop.dbo.PubGoods.GoodName like '%".$term."%'
                    or Shop.dbo.PubGoods.GoodCde like '%".$term."%'
                    and PubGoods.GoodGroupSn>49 and PubGoods.GoodName !=''
                    and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction
                    where hideKala=1 )"
                );
                return Response::json($kalas);
            }else{
                $kalas=DB::select("SELECT Shop.dbo.PubGoods.GoodName,Shop.dbo.PubGoods.GoodSn from Shop.dbo.PubGoods
                join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                where PubGoods.companyNo=5  and (Shop.dbo.PubGoods.GoodName like '%".$term."%'
                or Shop.dbo.PubGoods.GoodCde like '%".$term."%') and PubGoods.GoodName!=''
                and PubGoods.GoodGroupSn>49 and PubGoods.GoodSn
                not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )"
                );
                return Response::json($kalas);
            }
    }
    
    public function getKalasSearch(Request $request)
    {
        $groupId=$request->get('groupId');
        $subGroups;
        $kalas;
        $flag;
        if($groupId!=0){
            $flag=array(1,2);
            $subGroups=DB::select("SELECT id,title FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=".$groupId);
            $kalas=DB::select("SELECT GoodName,GoodSn,GoodGroupSn FROM Shop.dbo.PubGoods
            JOIN NewStarfood.dbo.star_add_prod_group ON PubGoods.GoodSn=star_add_prod_group.product_id
             WHERE firstGroupId=".$groupId." and PubGoods.GoodGroupSn>49 and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1)");
        }else{
            $flag=array(1);
            $subGroups=DB::select("SELECT id,title FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=0");
            $kalas=DB::select("SELECT GoodName,GoodSn,GoodGroupSn FROM Shop.dbo.PubGoods WHERE CompanyNo=5 and GoodGroupSn>49 and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) and GoodSn!=0");

        }
        return Response::json(['kalas'=>$kalas,'subGroups'=>$subGroups,'flag'=>$flag]);
    }
    public function getKalasSearchSubGroup(Request $request)
    {
        $groupId=$request->get('groupId');
        $kalas=DB::select("SELECT GoodName,GoodSn FROM Shop.dbo.PubGoods INNER JOIN NewStarfood.dbo.star_add_prod_group ON PubGoods.GoodSn=star_add_prod_group.product_id WHERE secondGroupId=".$groupId." and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )");
        return Response::json($kalas);
    }
    public function changePicturesKalaPriority(Request $request)
    {
        $picId=$request->get('picId');
        $kalaId=$request->get('kalaId');
        $priorityState=$request->get('priorityState');
        $priorities = DB::select("SELECT priority FROM NewStarfood.dbo.star_add_homePart_stuff WHERE productId=".$kalaId." AND partPic=".$picId);
        $priority=0;
        foreach ($priorities as $pr) {
            $priority=$pr->priority;
        }
        if ($priorityState=='up') {
            if($priority>1){
            DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority=".($priority-1)." WHERE productId=".$kalaId." AND partPic=".$picId);
            DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority=".($priority)." WHERE productId!=".$kalaId." and priority=".($priority-1));

            return redirect('/controlMainPage');

        }else{
            return 'up not allowed with priority:'.$priority;
        }
        } else {
            if($priority<20 or $priority>=0){
                DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority+=1 WHERE productId=".$kalaId." AND partPic=".$picId);
                DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority=".($priority)." WHERE productId!=".$kalaId." and priority=".($priority+1));
            return redirect('/controlMainPage');
            }else{
                return 'down not allowed with priority:'.$priority;
            }
        }
        return Response::json('good');
    }
    public function getUnitsForSettingMinSale(Request $request){
        $kalaId=$request->get('Pcode');
        $secondUnit;
        $defaultUnit;
        $amountUnit;
        $amountExist=0;
        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName,V.Amount FROM Shop.dbo.PubGoods
                JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
                JOIN (SELECT * FROM Shop.dbo.ViewGoodExists WHERE ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood WHERE PubGoods.CompanyNo=5 AND PubGoods.GoodSn=".$kalaId);
        foreach ($kalas as $kala) {
            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
        }         
        foreach ($kalas as $k) {
            $defaultUnit=$k->UName;
            $amountExist=$k->Amount;
        }
        $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$kalaId);
        foreach ($subUnitStuff as $stuff) {
            $secondUnit=$stuff->secondUnit;
            $amountUnit=$stuff->AmountUnit;
        }
        $code=" ";
          for ($i= 1; $i <= 500; $i++) {
            $code.="<span class='d-none'>31</span>
            <span id='Count1_0_239' class='d-none'>".($i*$amountUnit)."</span>
             <span id='CountLarge_0_239' class='d-none'>".$i."</span>
             <input value='' style='display:none' class='SnOrderBYS'/>
             <button style='font-weight: bold;  font-size: 17px;' value='".$i.'_'.$kalaId.'_'.$defaultUnit."' class='setMinSale btn-add-to-cart w-100 mb-2'> ".$i."".$secondUnit."  معادل ".($i*$amountUnit)."".$defaultUnit."</button>
             ";
          }
        return Response::json($code);
    }
    public function setMinimamSaleKala(Request $request)
    {
        $productId=$request->get('kalaId');
        $minSale=$request->get('amountUnit');
        $maxSaleOfAll=0;
        $webSpecialSettings=DB::table("NewStarfood.dbo.star_webSpecialSetting")->select('maxSale')->get();
        foreach ($webSpecialSettings as $special) {
            $maxSaleOfAll=$special->maxSale;
        }
        $checkExistance = DB::select("SELECT * FROM NewStarfood.dbo.star_GoodsSaleRestriction where productId=".$productId);
        if(!(count($checkExistance)>0)){
            DB::insert("INSERT INTO NewStarfood.dbo.star_GoodsSaleRestriction(maxSale, minSale, productId,overLine,callOnSale,zeroExistance) VALUES(".$maxSaleOfAll.", 1, ".$productId.",0,0,0)");

        }else{
            DB::update("UPDATE NewStarfood.dbo.star_GoodsSaleRestriction  SET minSale=".$minSale." WHERE productId=".$productId);
        }
        return Response::json($minSale);
    }


    public function getUnitsForSettingMaxSale(Request $request){
        $kalaId=$request->get('Pcode');
        $secondUnit;
        $defaultUnit;
        $amountUnit;
        $amountExist=0;
        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName,V.Amount FROM Shop.dbo.PubGoods
                    JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
                    JOIN (SELECT * FROM Shop.dbo.ViewGoodExists WHERE ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood WHERE PubGoods.CompanyNo=5 AND PubGoods.GoodSn=".$kalaId);
        
        foreach ($kalas as $kala) {
            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
        }
        
        foreach ($kalas as $k) {
            $defaultUnit=$k->UName;
            $amountExist=$k->Amount;
        }
        $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$kalaId);
        
        foreach ($subUnitStuff as $stuff) {
            $secondUnit=$stuff->secondUnit;
            $amountUnit=$stuff->AmountUnit;
        }
        $code=" ";
        for ($i= 1; $i <= 500; $i++) {
        $code.="<span class='d-none'>31</span>
            <span id='Count1_0_239' class='d-none'>".($i*$amountUnit)."</span>
            <span id='CountLarge_0_239' class='d-none'>".$i."</span>
            <input value='' style='display:none' class='SnOrderBYS'/>
            <button style='font-weight: bold;  font-size: 17px;' value='".$i.'_'.$kalaId.'_'.$defaultUnit."' class='setMaxSale btn-add-to-cart w-100 mb-2'> ".$i."".$secondUnit."  معادل ".($i*$amountUnit)."".$defaultUnit."</button>
            ";
        }
        return Response::json($code);
    }



    public function setMaximamSaleKala(Request $request)
    {
        $productId=$request->get('kalaId');
        $maxSale=$request->get('amountUnit');
        $checkExistance = DB::select("SELECT * FROM NewStarfood.dbo.star_GoodsSaleRestriction where productId=".$productId);
        if(!(count($checkExistance)>0)){
            DB::insert("INSERT INTO NewStarfood.dbo.star_GoodsSaleRestriction(maxSale, minSale, productId,overLine,callOnSale,zeroExistance) VALUES(".$maxSaleOfAll.", 1, ".$productId.",0,0,0)");

        }else{
            DB::update("UPDATE NewStarfood.dbo.star_GoodsSaleRestriction  SET maxSale=".$maxSale." WHERE productId=".$productId);
        }
        return Response::json($maxSale);
    }

    public function getUnits(Request $request)
    {
        $kalaId=$request->get('Pcode');
        $secondUnit;
        $defaultUnit;
        $amountUnit;
        $amountExist=0;
        $costLimit=0;
        $costError="";
        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName,V.Amount FROM Shop.dbo.PubGoods
                        JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
                        JOIN (select SUM(Amount) as Amount,SnGood from Shop.dbo.ViewGoodExistsInStock
                                                    where SnStock=23
													and ViewGoodExistsInStock.FiscalYear=".Session::get("FiscallYear")." and ViewGoodExistsInStock.CompanyNo=5 group by SnGood) V on PubGoods.GoodSn=V.SnGood WHERE PubGoods.CompanyNo=5 AND PubGoods.GoodSn=".$kalaId);
        
        

        foreach ($kalas as $k) {
            $defaultUnit=$k->UName;
            $amountExist=$k->Amount;
        }
        $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$kalaId);
        if(count($subUnitStuff)>0){
            foreach ($subUnitStuff as $stuff) {
                $secondUnit=$stuff->secondUnit;
                $amountUnit=$stuff->AmountUnit;
            }
        }else{
            $secondUnit=$defaultUnit;
            $amountUnit=1;
        }
            $saleAmounts=DB::select("SELECT maxSale from NewStarfood.dbo.star_webSpecialSetting");
            $maxSaleAmount=10;
            $minSaleAmount=1;
            foreach ($saleAmounts as $amount) {
                $maxSaleAmount=$amount->maxSale;
            }
            $testRestriction=DB::select("SELECT * FROM  NewStarfood.dbo.star_GoodsSaleRestriction WHERE productId=".$kalaId);
            $costLimit=10;
            foreach ($testRestriction as $restriction) {
                if($restriction->maxSale>-1){
                $maxSaleAmount=$restriction->maxSale;
                }
                $minSaleAmount=$restriction->minSale;
                $costLimit=$restriction->costLimit;
                $costError=$restriction->costError;
            }
            $maxSale=$maxSaleAmount;
            $minSaleAmount=$minSaleAmount;
        $code=" ";
          for ($i= $minSaleAmount; $i <= $maxSale; $i++) {
            $code.="<span class='d-none'>31</span>
            <span id='Count1_0_239' class='d-none'>".($i*$amountUnit)."</span>
             <span id='CountLarge_0_239' class='d-none'>".$i."</span>
             <input value='' style='display:none' class='SnOrderBYS'/>
             <input value='$amountExist' style='display:none' id='amountExist' class='SnOrderBYS'/>
             <input value='".$costLimit."' style='display:none' id='costLimit' />
             <input value='".$costError."' style='display:none' id='costError' />
             <input value='".$i*$amountUnit."' style='display:none' id='firstUnitTedad' />
             <button style='font-weight: bold;  font-size: 17px;' value='".$i*$amountUnit.'_'.$kalaId.'_'.$defaultUnit."' id='selected_0_239' class='addData btn-add-to-cart w-100 mb-2'> ".$i."".$secondUnit."  معادل ".($i*$amountUnit)."".$defaultUnit."</button>
             ";
          }
        return Response::json(mb_convert_encoding($code, "HTML-ENTITIES", "UTF-8"));
    }

    public function getUnitsForPishKharid(Request $request)
    {
        $kalaId=$request->get('Pcode');
        $secondUnit;
        $defaultUnit;
        $amountUnit;
        $amountExist=0;
        $costLimit=0;
        $costError="";

        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName,V.Amount FROM Shop.dbo.PubGoods
                        JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
                        JOIN (SELECT * FROM Shop.dbo.ViewGoodExists WHERE ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood WHERE PubGoods.CompanyNo=5 AND PubGoods.GoodSn=".$kalaId);
        
        

        foreach ($kalas as $k) {
            $defaultUnit=$k->UName;
            $amountExist=$k->Amount;
        }
        $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$kalaId);
        if(count($subUnitStuff)>0){
            foreach ($subUnitStuff as $stuff) {
                $secondUnit=$stuff->secondUnit;
                $amountUnit=$stuff->AmountUnit;
            }
        }else{
            $secondUnit=$defaultUnit;
            $amountUnit=1;
        }
            $saleAmounts=DB::select("SELECT maxSale from NewStarfood.dbo.star_webSpecialSetting");
            $maxSaleAmount=10;
            $minSaleAmount=1;
            foreach ($saleAmounts as $amount) {
                $maxSaleAmount=$amount->maxSale;
            }
            $testRestriction=DB::select("SELECT * FROM  NewStarfood.dbo.star_GoodsSaleRestriction WHERE productId=".$kalaId);
            $costLimit=10;
            foreach ($testRestriction as $restriction) {
                if($restriction->maxSale>-1){
                $maxSaleAmount=$restriction->maxSale;
                }
                $minSaleAmount=$restriction->minSale;
                $costLimit=$restriction->costLimit;
                $costError=$restriction->costError;
            }
            $maxSale=$maxSaleAmount;
            $minSaleAmount=$minSaleAmount;
        $code=" ";
          for ($i= $minSaleAmount; $i <= $maxSale; $i++) {
            $code.="<span class='d-none'>31</span>
            <span id='Count1_0_239' class='d-none'>".($i*$amountUnit)."</span>
             <span id='CountLarge_0_239' class='d-none'>".$i."</span>
             <input value='' style='display:none' class='SnOrderBYS'/>
             <input value='$amountExist' style='display:none' id='amountExist' class='SnOrderBYS'/>
             <input value='".$costLimit."' style='display:none' id='costLimit' />
             <input value='".$costError."' style='display:none' id='costError' />
             <input value='".$i*$amountUnit."' style='display:none' id='firstUnitTedad' />
             <button style='font-weight: bold;  font-size: 17px;' value='".$i*$amountUnit.'_'.$kalaId.'_'.$defaultUnit."' id='selected_0_239' class='addPishKharid btn-add-to-cart w-100 mb-2'> ".$i."".$secondUnit."  معادل ".($i*$amountUnit)."".$defaultUnit."</button>
             ";
          }
        return Response::json(mb_convert_encoding($code, "HTML-ENTITIES", "UTF-8"));
    }

    public function getUnitsForUpdatePishKharid(Request $request)
    {
        $costAmount=0;
        $amelSn=0;
        $kalaId=$request->get('Pcode');
        $secondUnit;
        $defaultUnit;
        $amountUnit;
        $amountExist=0;
        $costLimit=0;
        $costError="";
        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName,V.Amount FROM Shop.dbo.PubGoods
        JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
        JOIN (SELECT * FROM Shop.dbo.ViewGoodExists WHERE ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood
        where PubGoods.CompanyNo=5 and PubGoods.GoodSn=".$kalaId);

        foreach ($kalas as $kala) {

            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

        }

        foreach ($kalas as $k) {
            $defaultUnit=$k->UName;
            $amountExist=$k->Amount;
        }
        $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$kalaId);
        if(count($subUnitStuff)>0){
            foreach ($subUnitStuff as $stuff) {
                $secondUnit=$stuff->secondUnit;
                $amountUnit=$stuff->AmountUnit;
            }
        }else{
            $secondUnit=$defaultUnit;
            $amountUnit=1;
        }
        $saleAmounts=DB::select("SELECT maxSale from NewStarfood.dbo.star_webSpecialSetting");
            $maxSaleAmount=10;
            $minSaleAmount=1;
            foreach ($saleAmounts as $amount) {
                $maxSaleAmount=$amount->maxSale;
            }
            $testRestriction=DB::select("SELECT * FROM  NewStarfood.dbo.star_GoodsSaleRestriction WHERE productId=".$kalaId);
            foreach ($testRestriction as $restriction) {
                if($restriction->maxSale>-1){
                    $maxSaleAmount=$restriction->maxSale;
                }
                $minSaleAmount=$restriction->minSale;
                $costLimit=$restriction->costLimit;
                $costError=$restriction->costError;
                $costAmount=$restriction->costAmount;
                $amelSn=$restriction->inforsType;
            }
            $maxSale=$maxSaleAmount;
            $minSaleAmount=$minSaleAmount;
        $code=" ";
          for ($i= $minSaleAmount; $i <= $maxSale; $i++) {
            $code.="<span class='d-none'>31</span>
            <span id='Count1_0_239' class='d-none'>".($i*$amountUnit)."</span>
             <span id='CountLarge_0_239' class='d-none'>".$i."</span>
             <input value='' style='display:none' class='SnOrderBYS'/>
             <input value='$amountExist' id='amountExist' style='display:none' class=''/>
             <input value='".$costLimit."' style='display:none' id='costLimit' />
             <input value='".$costError."' style='display:none' id='costError' />
             <input value='".$i*$amountUnit."' style='display:none' id='firstUnitTedad' />
             <button style='font-weight: bold;  font-size: 17px;' value='".$i*$amountUnit.'_'.$kalaId.'_'.$secondUnit.'_'.$defaultUnit.'_'.$i."' id='selected_0_239' class='updatePishKharid btn-add-to-cart w-100 mb-2'> ".$i."".$secondUnit."  معادل ".($i*$amountUnit)."".$defaultUnit."</button>
             ";
          }
        return Response::json(mb_convert_encoding($code, "HTML-ENTITIES", "UTF-8"));

    }

    public function getUnitsForUpdate(Request $request)
    {
        $costAmount=0;
        $amelSn=0;
        $kalaId=$request->get('Pcode');
        $secondUnit;
        $defaultUnit;
        $amountUnit;
        $amountExist=0;
        $costLimit=0;
        $costError="";

        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName,V.Amount FROM Shop.dbo.PubGoods
                    JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
                    JOIN (SELECT * FROM Shop.dbo.ViewGoodExists WHERE ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood
                    where PubGoods.CompanyNo=5 and PubGoods.GoodSn=".$kalaId);

        foreach ($kalas as $kala) {

            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

        }
        
        foreach ($kalas as $k) {
            $defaultUnit=$k->UName;
            $amountExist=$k->Amount;
        }
        $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$kalaId);
        if(count($subUnitStuff)>0){
            foreach ($subUnitStuff as $stuff) {
                $secondUnit=$stuff->secondUnit;
                $amountUnit=$stuff->AmountUnit;
            }
        }else{
            $secondUnit=$defaultUnit;
            $amountUnit=1;
        }
        $saleAmounts=DB::select("SELECT maxSale from NewStarfood.dbo.star_webSpecialSetting");
            $maxSaleAmount=10;
            $minSaleAmount=1;
            foreach ($saleAmounts as $amount) {
                $maxSaleAmount=$amount->maxSale;
            }
            $testRestriction=DB::select("SELECT * FROM  NewStarfood.dbo.star_GoodsSaleRestriction WHERE productId=".$kalaId);
            foreach ($testRestriction as $restriction) {
                if($restriction->maxSale>-1){
                    $maxSaleAmount=$restriction->maxSale;
                }
                $minSaleAmount=$restriction->minSale;
                $costLimit=$restriction->costLimit;
                $costError=$restriction->costError;
                $costAmount=$restriction->costAmount;
                $amelSn=$restriction->inforsType;
            }
            $maxSale=$maxSaleAmount;
            $minSaleAmount=$minSaleAmount;
        $code=" ";
          for ($i= $minSaleAmount; $i <= $maxSale; $i++) {
            $code.="<span class='d-none'>31</span>
            <span id='Count1_0_239' class='d-none'>".($i*$amountUnit)."</span>
             <span id='CountLarge_0_239' class='d-none'>".$i."</span>
             <input value='' style='display:none' class='SnOrderBYS'/>
             <input value='$amountExist' id='amountExist' style='display:none' class=''/>
             <input value='".$costLimit."' style='display:none' id='costLimit' />
             <input value='".$costError."' style='display:none' id='costError' />
             <input value='".$i*$amountUnit."' style='display:none' id='firstUnitTedad' />
             <button style='font-weight: bold;  font-size: 17px;' value='".$i*$amountUnit.'_'.$kalaId.'_'.$secondUnit.'_'.$defaultUnit.'_'.$i."' id='selected_0_239' class='updateData btn-add-to-cart w-100 mb-2'> ".$i."".$secondUnit."  معادل ".($i*$amountUnit)."".$defaultUnit."</button>
             ";
          }
        return Response::json(mb_convert_encoding($code, "HTML-ENTITIES", "UTF-8"));

    }
    public function buySomething(Request $request)
    {
        $packType;
        $packAmount;
        $allPacks;
        $defaultUnit=0;
        $costAmount=0;
        $amelSn=0;
        $kalaId=$request->get('kalaId');
        $checkExistance=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$kalaId)->select("*")->get();
        foreach ($checkExistance as $pr) {
            $costLimit=$pr->costLimit;
            $costAmount=$pr->costAmount;
            $amelSn=$pr->inforsType;
        }
        $secondUnits=DB::select("select SnGoodUnit,AmountUnit from Shop.dbo.GoodUnitSecond where GoodUnitSecond.SnGood=".$kalaId);
        $defaultUnits=DB::select("select defaultUnit from Shop.dbo.PubGoods where PubGoods.GoodSn=".$kalaId);
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
        $amountUnit=$request->get('amountUnit');
        $prices=DB::select("select GoodPriceSale.Price3,GoodPriceSale.Price4 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$kalaId);
        $exactPrice=0;
        $exactPriceFirst=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
            $exactPriceFirst=$price->Price4;
        }
        $allMoney= $exactPrice * $amountUnit;
        $allMoneyFirst=$exactPriceFirst * $amountUnit;
        $maxOrderNo=DB::select("select MAX(OrderNo) as maxNo from NewStarfood.dbo.FactorStar where CompanyNo=5");
        $maxOrder=0;
        if(count($maxOrderNo)>0){

            foreach ($maxOrderNo as $maxNo) {

                $maxOrder=$maxNo->maxNo;

                $maxOrder=$maxOrder+1;
            }

        }else{

            $maxOrder=1;

            }

        $todayDate = Jalalian::forge('today')->format('Y/m/d');
        $todayNow = Jalalian::forge('today')->format("%H:%M:%S");
        $customerId=Session::get('psn');
        $curTime=  Carbon::now();
        $dt=$curTime->format("Y-m-d H:i:s");
        $countOrders=DB::select("SELECT count(SnOrder) AS contOrder from NewStarfood.dbo.FactorStar WHERE OrderStatus=0 AND  CustomerSn=".$customerId);
        $countOrder=0;
        foreach ($countOrders as $countOrder1) {
            $countOrder=$countOrder1->contOrder;
        }
        $factorNumber=DB::select("SELECT MAX(OrderNo) as maxFact from Shop.dbo.OrderHDS WHERE CompanyNo=5");
        $factorNo;
        foreach ($factorNumber as $number) {
            $factorNo=$number->maxFact;
        }
        $factorNo=$factorNo+1;
        if($countOrder<1){
            DB::insert("INSERT INTO NewStarfood.dbo.FactorStar (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)
            VALUES(5,".$factorNo.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
           $maxsFactor=DB::select("SELECT MAX(SnOrder) as maxFactorId from NewStarfood.dbo.FactorStar where CustomerSn=".$customerId);
           $maxsFactorId=0;
           foreach ($maxsFactor as $maxFact) {
            $maxsFactorId=$maxFact->maxFactorId;
           }
           DB::insert("INSERT INTO star_BuyTracking (factorStarId ,orderHDSId ,factorHDSId,customerId,pardakhtType) VALUES (".$maxsFactorId.", 0, 0,".$customerId.",'notYet')");
        }
        $maxOrderSn=DB::select("SELECT MAX(SnOrder) AS mxsn from NewStarfood.dbo.FactorStar WHERE CustomerSn=".$customerId);
        $maxOrder=1;

        if(count($maxOrderSn)>0){

            foreach ($maxOrderSn as $mxsn) {
                $maxOrder=($mxsn->mxsn)+1;
            }

        }else{

            $maxOrder=1;

            }

        $FactorStarStatus=DB::select("SELECT OrderStatus from NewStarfood.dbo.FactorStar where CompanyNo=5 and CustomerSn=".$customerId." and SnOrder=".$maxOrder);
        $orderState=0;
        foreach ($FactorStarStatus as $status) {
            $orderState=$status->OrderStatus;
        }
        if($orderState==1){
                DB::insert("INSERT INTO NewStarfood.dbo.FactorStar (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)

                VALUES(5,".$maxOrder.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
                $FactorStarSn=DB::select("SELECT MAX(SnOrder) as snOrder FROM NewStarfood.dbo.FactorStar WHERE customerSn=".$customerId." and OrderStatus=0");
                $lastOrder;

                if(count($FactorStarSn)>0){

                    foreach ($FactorStarSn as $orderSn) {
                        $lastOrder=$orderSn->snOrder;
                    }

                }else{

                    $lastOrder=1;
                    }

                $fiPack=$exactPrice*$packAmount;
                DB::insert("INSERT INTO NewStarfood.dbo.orderStar(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4,Price1)

                VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".($amountUnit/$packAmount).",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,".$allMoneyFirst.")");
                Session::put('buy',1);
                $snlastBYS=DB::table('NewStarfood.dbo.orderStar')->max('SnOrderBYS');
                if($costLimit>0){
                    //add cost to AmelBYS
                    DB::table('NewStarfood.dbo.star_orderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$snlastBYS,
                    'SnAmel'=>$amelSn,'Price'=>$costLimit,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>"",'IsExport'=>1]);
                }
        }else{
                $FactorStarSn=DB::select("SELECT MAX(SnOrder) as snOrder FROM NewStarfood.dbo.FactorStar WHERE customerSn=".$customerId." and OrderStatus=0");
                $lastOrder;
                if(count($FactorStarSn)>0){

                    foreach ($FactorStarSn as $orderSn) {
                        $lastOrder=$orderSn->snOrder;
                    }

                }else{

                    $lastOrder=1;

                    }

                $fiPack=$exactPrice*$packAmount;
                DB::insert("INSERT INTO NewStarfood.dbo.orderStar(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4,Price1)

                VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".($amountUnit/$packAmount).",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,".$allMoneyFirst.")");
                $snlastBYS=DB::table('NewStarfood.dbo.orderStar')->max('SnOrderBYS');
                if($costLimit<=$amountUnit){
                    //add cost to AmelBYS
                    DB::table('NewStarfood.dbo.star_orderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$snlastBYS,
                    'SnAmel'=>$amelSn,'Price'=>$costAmount,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>"",'IsExport'=>0]);
                }
                $buys=Session::get('buy');
                Session::put('buy',$buys+1);
            }

        return Response::json(mb_convert_encoding($snlastBYS, "HTML-ENTITIES", "UTF-8"));
    }

    public function pishKharidSomething(Request $request)
    {
        $packType;
        $packAmount;
        $allPacks;
        $defaultUnit=0;
        $costAmount=0;
        $amelSn=0;
        $kalaId=$request->get('kalaId');
        $checkExistance=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$kalaId)->select("*")->get();
        foreach ($checkExistance as $pr) {
            $costLimit=$pr->costLimit;
            $costAmount=$pr->costAmount;
            $amelSn=$pr->inforsType;
        }
        $secondUnits=DB::select("select SnGoodUnit,AmountUnit from Shop.dbo.GoodUnitSecond where GoodUnitSecond.SnGood=".$kalaId);
        $defaultUnits=DB::select("select defaultUnit from Shop.dbo.PubGoods where PubGoods.GoodSn=".$kalaId);
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
        $amountUnit=$request->get('amountUnit');
        $prices=DB::select("select GoodPriceSale.Price3 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$kalaId);
        $exactPrice=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
        }
        $allMoney= $exactPrice * $amountUnit;
        $maxOrderNo=DB::select("select MAX(OrderNo) as maxNo from NewStarfood.dbo.star_pishKharidFactor where CompanyNo=5");
        $maxOrder=0;
        if(count($maxOrderNo)>0){

            foreach ($maxOrderNo as $maxNo) {

                $maxOrder=$maxNo->maxNo;

                $maxOrder=$maxOrder+1;
            }

        }else{

            $maxOrder=1;

            }

        $todayDate = Jalalian::forge('today')->format('Y/m/d');
        $todayNow = Jalalian::forge('today')->format("%H:%M:%S");
        $customerId=Session::get('psn');
        $curTime=  Carbon::now();
        $dt=$curTime->format("Y-m-d H:i:s");
        $countOrders=DB::select("SELECT count(SnOrderPishKharid) AS contOrder from NewStarfood.dbo.star_pishKharidFactor WHERE OrderStatus=0 AND  CustomerSn=".$customerId);
        $countOrder=0;
        foreach ($countOrders as $countOrder1) {
            $countOrder=$countOrder1->contOrder;
        }
        $factorNumber=DB::select("SELECT MAX(OrderNo) as maxFact from Shop.dbo.OrderHDS WHERE CompanyNo=5");
        $factorNo;
        foreach ($factorNumber as $number) {
            $factorNo=$number->maxFact;
        }
        $factorNo=$factorNo+1;
        if($countOrder<1){
            DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidFactor (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)
            VALUES(5,".$factorNo.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
           $maxsFactor=DB::select("SELECT MAX(SnOrderPishKharid) as maxFactorId from NewStarfood.dbo.star_pishKharidFactor where CustomerSn=".$customerId);
           $maxsFactorId=0;
           foreach ($maxsFactor as $maxFact) {
            $maxsFactorId=$maxFact->maxFactorId;
           }
        //    DB::insert("INSERT INTO star_BuyTracking (factorStarId ,orderHDSId ,factorHDSId,customerId,pardakhtType) VALUES (".$maxsFactorId.", 0, 0,".$customerId.",'notYet')");
        }
        $maxOrderSn=DB::select("SELECT MAX(SnOrderPishKharid) AS mxsn from NewStarfood.dbo.star_pishKharidFactor WHERE CustomerSn=".$customerId);
        $maxOrder=1;

        if(count($maxOrderSn)>0){

            foreach ($maxOrderSn as $mxsn) {
                $maxOrder=($mxsn->mxsn)+1;
            }

        }else{

            $maxOrder=1;

            }

        $FactorStarStatus=DB::select("SELECT OrderStatus from NewStarfood.dbo.star_pishKharidFactor where CompanyNo=5 and CustomerSn=".$customerId." and SnOrderPishKharid=".$maxOrder);
        $orderState=0;
        foreach ($FactorStarStatus as $status) {
            $orderState=$status->OrderStatus;
        }
        if($orderState==1){
                DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidFactor (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)

                VALUES(5,".$maxOrder.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
                $FactorStarSn=DB::select("SELECT MAX(SnOrderPishKharid) as snOrder FROM NewStarfood.dbo.star_pishKharidFactor WHERE customerSn=".$customerId." and OrderStatus=0");
                $lastOrder;

                if(count($FactorStarSn)>0){

                    foreach ($FactorStarSn as $orderSn) {
                        $lastOrder=$orderSn->snOrder;
                    }

                }else{

                    $lastOrder=1;
                    }

                $fiPack=$allMoney/$packAmount;
                DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidOrder(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".$packAmount.",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                // Session::put('buy',1);
                // $snlastBYS=DB::table('NewStarfood.dbo.star_pishKharidOrder')->max('SnOrderBYSPishKharid');
                // if($costLimit>0){
                //     //add cost to AmelBYS
                //     DB::table('NewStarfood.dbo.star_orderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$snlastBYS,
                //     'SnAmel'=>$amelSn,'Price'=>$costLimit,'FiscalYear'=>".Session::get("FiscallYear").",'DescItem'=>"",'IsExport'=>1]);
                // }
        }else{
                $FactorStarSn=DB::select("SELECT MAX(SnOrderPishKharid) as snOrder FROM NewStarfood.dbo.star_pishKharidFactor WHERE customerSn=".$customerId." and OrderStatus=0");
                $lastOrder;
                if(count($FactorStarSn)>0){

                    foreach ($FactorStarSn as $orderSn) {
                        $lastOrder=$orderSn->snOrder;
                    }

                }else{

                    $lastOrder=1;

                    }

                $fiPack=$allMoney/$packAmount;
                DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidOrder(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".$packAmount.",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                $snlastBYS=DB::table('NewStarfood.dbo.orderStar')->max('SnOrderBYS');
            }

        return Response::json(mb_convert_encoding($snlastBYS, "HTML-ENTITIES", "UTF-8"));
    }

    //پیش خرید
    public function updateOrderPishKharid(Request $request)
    {
        $costAmount=0;
        $amelSn=0;
        $costLimit=0;
        $orderBYSsn=$request->get('orderBYSSn');
        $amountUnit=$request->get('amountUnit');
        $productId=$request->get('kalaId');
        $checkExistance=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$productId)->select("*")->get();
        foreach ($checkExistance as $pr) {
            $costLimit=$pr->costLimit;
            $costAmount=$pr->costAmount;
            $amelSn=$pr->inforsType;
        }
        $prices=DB::select("select GoodPriceSale.Price3 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$productId);
        $exactPrice=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
        }
        $allMoney= $exactPrice * $amountUnit;
        DB::update("UPDATE NewStarfood.dbo.star_pishKharidOrder SET Amount=".$amountUnit." ,Price=".$allMoney." WHERE SnOrderBYSPishKharid=".$orderBYSsn);
        $checkExistance=DB::table("NewStarfood.dbo.star_orderAmelBYS")->where("SnOrder",$orderBYSsn)->count();
        return Response::json('good');
    }

    public function buySomethingFromHome(Request $request)
    {
        $packType;
        $packAmount;
        $allPacks;
        $defaultUnit=0;
        $kalaId=$request->get('kalaId');
        $secondUnits=DB::select("select SnGoodUnit,AmountUnit from Shop.dbo.GoodUnitSecond where GoodUnitSecond.SnGood=".$kalaId);
        $defaultUnits=DB::select("select defaultUnit from Shop.dbo.PubGoods where PubGoods.GoodSn=".$kalaId);
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
        $amountUnit=$request->get('amountUnit')*$packAmount;
        $prices=DB::select("select GoodPriceSale.Price3 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$kalaId);
        $exactPrice=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
        }
        $allMoney= $exactPrice * $amountUnit;
        $maxOrderNo=DB::select("select MAX(OrderNo) as maxNo from NewStarfood.dbo.FactorStar where CompanyNo=5");
        $maxOrder=0;
        if(count($maxOrderNo)>0){

            foreach ($maxOrderNo as $maxNo) {

                $maxOrder=$maxNo->maxNo;

                $maxOrder=$maxOrder+1;
            }

        }else{

            $maxOrder=1;

            }

        $todayDate = Jalalian::forge('today')->format('Y/m/d');
        $todayNow = Jalalian::forge('today')->format("%H:%M:%S");
        $customerId=Session::get('psn');
        $curTime=  Carbon::now();
        $dt=$curTime->format("Y-m-d H:i:s");
        $countOrders=DB::select("SELECT count(SnOrder) AS contOrder from NewStarfood.dbo.FactorStar WHERE OrderStatus=0 AND  CustomerSn=".$customerId);
        $countOrder=0;
        foreach ($countOrders as $countOrder1) {
            $countOrder=$countOrder1->contOrder;
        }
        $factorNumber=DB::select("SELECT MAX(OrderNo) as maxFact from Shop.dbo.OrderHDS WHERE CompanyNo=5");
        $factorNo;
        foreach ($factorNumber as $number) {
            $factorNo=$number->maxFact;
        }
        $factorNo=$factorNo+1;
        if($countOrder<1){
            DB::insert("INSERT INTO NewStarfood.dbo.FactorStar (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)

            VALUES(5,".$factorNo.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
           $maxsFactor=DB::select("SELECT MAX(SnOrder) as maxFactorId from NewStarfood.dbo.FactorStar where CustomerSn=".$customerId);
           $maxsFactorId=0;
           foreach ($maxsFactor as $maxFact) {
            $maxsFactorId=$maxFact->maxFactorId;
           }
           DB::insert("INSERT INTO star_BuyTracking (factorStarId ,orderHDSId ,factorHDSId,customerId,pardakhtType) VALUES (".$maxsFactorId.", 0, 0,".$customerId.",'notYet')");
        }
        $maxOrderSn=DB::select("SELECT MAX(SnOrder) AS mxsn from NewStarfood.dbo.FactorStar WHERE CustomerSn=".$customerId);
        $maxOrder=1;

        if(count($maxOrderSn)>0){

            foreach ($maxOrderSn as $mxsn) {
                $maxOrder=($mxsn->mxsn)+1;
            }

        }else{

            $maxOrder=1;

            }

        $FactorStarStatus=DB::select("SELECT OrderStatus from NewStarfood.dbo.FactorStar where CompanyNo=5 and CustomerSn=".$customerId." and SnOrder=".$maxOrder);
        $orderState=0;
        foreach ($FactorStarStatus as $status) {
            $orderState=$status->OrderStatus;
        }
        if($orderState==1){
            DB::insert("INSERT INTO NewStarfood.dbo.FactorStar (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)

            VALUES(5,".$maxOrder.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
            $FactorStarSn=DB::select("SELECT MAX(SnOrder) as snOrder FROM NewStarfood.dbo.FactorStar WHERE customerSn=".$customerId." and OrderStatus=0");
    $lastOrder;

    if(count($FactorStarSn)>0){

        foreach ($FactorStarSn as $orderSn) {
            $lastOrder=$orderSn->snOrder;
        }

    }else{

        $lastOrder=1;

        }
            $fiPack=$allMoney/$packAmount;
            DB::insert("INSERT INTO NewStarfood.dbo.orderStar(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                                                        VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".$packAmount.",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
    Session::put('buy',1);
}else{
    $FactorStarSn=DB::select("SELECT MAX(SnOrder) as snOrder FROM NewStarfood.dbo.FactorStar WHERE customerSn=".$customerId." and OrderStatus=0");
    $lastOrder;

    if(count($FactorStarSn)>0){

        foreach ($FactorStarSn as $orderSn) {
            $lastOrder=$orderSn->snOrder;
        }

    }else{

        $lastOrder=1;

        }

    $fiPack=$allMoney/$packAmount;
    DB::insert("INSERT INTO NewStarfood.dbo.orderStar(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

    VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".$packAmount.",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
    $snlastBYS=DB::table('NewStarfood.dbo.orderStar')->max('SnOrderBYS');
    $buys=Session::get('buy');
    Session::put('buy',$buys+1);
        }

        return Response::json(mb_convert_encoding($snlastBYS, "HTML-ENTITIES", "UTF-8"));
    }

    public function preBuySomethingFromHome(Request $request)
    {
        $packType;
        $packAmount;
        $allPacks;
        $defaultUnit=0;
        $costAmount=0;
        $amelSn=0;
        $kalaId=$request->get('kalaId');
        $checkExistance=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$kalaId)->select("*")->get();
        foreach ($checkExistance as $pr) {
            $costLimit=$pr->costLimit;
            $costAmount=$pr->costAmount;
            $amelSn=$pr->inforsType;
        }
        $secondUnits=DB::select("select SnGoodUnit,AmountUnit from Shop.dbo.GoodUnitSecond where GoodUnitSecond.SnGood=".$kalaId);
        $defaultUnits=DB::select("select defaultUnit from Shop.dbo.PubGoods where PubGoods.GoodSn=".$kalaId);
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

        $amountUnit=$request->get('amountUnit')*$packAmount;
        $prices=DB::select("select GoodPriceSale.Price3 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$kalaId);
        $exactPrice=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
        }
        $allMoney= $exactPrice * $amountUnit;
        $maxOrderNo=DB::select("select MAX(OrderNo) as maxNo from NewStarfood.dbo.star_pishKharidFactor where CompanyNo=5");
        $maxOrder=0;
        if(count($maxOrderNo)>0){

            foreach ($maxOrderNo as $maxNo) {

                $maxOrder=$maxNo->maxNo;

                $maxOrder=$maxOrder+1;
            }

        }else{

            $maxOrder=1;

            }

        $todayDate = Jalalian::forge('today')->format('Y/m/d');
        $todayNow = Jalalian::forge('today')->format("%H:%M:%S");
        $customerId=Session::get('psn');
        $curTime=  Carbon::now();
        $dt=$curTime->format("Y-m-d H:i:s");
        $countOrders=DB::select("SELECT count(SnOrderPishKharid) AS contOrder from NewStarfood.dbo.star_pishKharidFactor WHERE OrderStatus=0 AND  CustomerSn=".$customerId);
        $countOrder=0;
        foreach ($countOrders as $countOrder1) {
            $countOrder=$countOrder1->contOrder;
        }
        $factorNumber=DB::select("SELECT MAX(OrderNo) as maxFact from Shop.dbo.OrderHDS WHERE CompanyNo=5");
        $factorNo;
        foreach ($factorNumber as $number) {
            $factorNo=$number->maxFact;
        }
        $factorNo=$factorNo+1;
        if($countOrder<1){
            DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidFactor (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)
            VALUES(5,".$factorNo.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
           $maxsFactor=DB::select("SELECT MAX(SnOrderPishKharid) as maxFactorId from NewStarfood.dbo.star_pishKharidFactor where CustomerSn=".$customerId);
           $maxsFactorId=0;
           foreach ($maxsFactor as $maxFact) {
            $maxsFactorId=$maxFact->maxFactorId;
           }
        }
        $maxOrderSn=DB::select("SELECT MAX(SnOrderPishKharid) AS mxsn from NewStarfood.dbo.star_pishKharidFactor WHERE CustomerSn=".$customerId);
        $maxOrder=1;

        if(count($maxOrderSn)>0){

            foreach ($maxOrderSn as $mxsn) {
                $maxOrder=($mxsn->mxsn)+1;
            }

        }else{

            $maxOrder=1;

            }

        $FactorStarStatus=DB::select("SELECT OrderStatus from NewStarfood.dbo.star_pishKharidFactor where CompanyNo=5 and CustomerSn=".$customerId." and SnOrderPishKharid=".$maxOrder);
        $orderState=0;
        foreach ($FactorStarStatus as $status) {
            $orderState=$status->OrderStatus;
        }
        $snlastBYS=0;
        if($orderState==1){
                DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidFactor (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,TimeStamp,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)

                VALUES(5,".$maxOrder.",'".$todayDate."',".$customerId.",'',0,'','','".Session::get("FiscallYear")."',".(DB::raw('CURRENT_TIMESTAMP')).",0,0,3,'','',0,'','',0,0,0,0,0,'','','".$todayDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");
                $FactorStarSn=DB::select("SELECT MAX(SnOrderPishKharid) as snOrder FROM NewStarfood.dbo.star_pishKharidFactor WHERE customerSn=".$customerId." and OrderStatus=0");
                $lastOrder;

                if(count($FactorStarSn)>0){

                    foreach ($FactorStarSn as $orderSn) {
                        $lastOrder=$orderSn->snOrder;
                    }

                }else{

                    $lastOrder=1;
                    }

                $fiPack=$allMoney/$packAmount;
                DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidOrder(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".$packAmount.",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                $snlastBYS=DB::table('NewStarfood.dbo.star_pishKharidOrder')->max('SnOrderBYSPishKharid');
        }else{
                $FactorStarSn=DB::select("SELECT MAX(SnOrderPishKharid) as snOrder FROM NewStarfood.dbo.star_pishKharidFactor WHERE customerSn=".$customerId." and OrderStatus=0");
                $lastOrder;
                if(count($FactorStarSn)>0){

                    foreach ($FactorStarSn as $orderSn) {
                        $lastOrder=$orderSn->snOrder;
                    }

                }else{

                    $lastOrder=1;

                    }

                $fiPack=$allMoney/$packAmount;
                DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidOrder(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,TimeStamp,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                VALUES(5,".$lastOrder.",".$kalaId.",".$packType.",".$packAmount.",".$amountUnit.",".$exactPrice.",".$allMoney.",'',0,'".$todayDate."',12,".(DB::raw('CURRENT_TIMESTAMP')).",0,0,0,".$fiPack.",0,0,0,'',0,0,0,0,0,".$allMoney.",0,0,".$exactPrice.",".$allMoney.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                $snlastBYS=DB::table('NewStarfood.dbo.star_pishKharidOrder')->max('SnOrderBYSPishKharid');
                }
        return Response::json(mb_convert_encoding($snlastBYS, "HTML-ENTITIES", "UTF-8"));
    }

    public function updateOrderBYS(Request $request)
    {
        $costAmount=0;
        $amelSn=0;
        $costLimit=0;
        $defaultUnit;
        $packType;
        $packAmount;
        $orderBYSsn=$request->get('orderBYSSn');
        $amountUnit=$request->get('amountUnit');
        $productId=$request->get('kalaId');

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

        $checkExistance=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$productId)->select("*")->get();
        foreach ($checkExistance as $pr) {
            $costLimit=$pr->costLimit;
            $costAmount=$pr->costAmount;
            $amelSn=$pr->inforsType;
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
        DB::update("UPDATE NewStarfood.dbo.orderStar SET Amount=".$amountUnit.",PackAmount=($amountUnit/$packAmount),Price=".$allMoney.",Price1=".$allMoneyFirst.",FiPack=".$fiPack.",PriceAfterTakhfif=$allMoney WHERE SnOrderBYS=".$orderBYSsn);
        $checkExistance=DB::table("NewStarfood.dbo.star_orderAmelBYS")->where("SnOrder",$orderBYSsn)->count();
        if($checkExistance<1 and $costLimit>0){
            //add cost to AmelBYS
            DB::table('NewStarfood.dbo.star_orderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$orderBYSsn,
            'SnAmel'=>$amelSn,'Price'=>$costAmount,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>"",'IsExport'=>0]);
        }
        return Response::json(mb_convert_encoding($allMoney, "HTML-ENTITIES", "UTF-8"));
    }
    public function updateOrderBYSFromHome(Request $request)
    {
        $orderBYSsn=$request->get('orderBYSSn');
        $amountUnit=$request->get('amountUnit');
        $productId=$request->get('kalaId');
        $defaultUnit;
        $defaultUnits=DB::select("SELECT UName FROM Shop.dbo.PubGoods JOIN Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN WHERE PubGoods.GoodSn=".$productId);
        foreach ($defaultUnits as $unit) {
            $defaultUnit=$unit->UName;
        }
        $secondUnits=DB::select("select SnGoodUnit,AmountUnit from Shop.dbo.GoodUnitSecond where GoodUnitSecond.SnGood=".$productId);
        if(count($secondUnits)>0){
            foreach ($secondUnits as $unit) {
                $packType=$unit->SnGoodUnit;
                $packAmount=$unit->AmountUnit;
            }
        }else{
            $packType=$defaultUnit;
            $packAmount=1;
        }
        $amountUnit=$request->get('amountUnit')*$packAmount;
        $prices=DB::select("select GoodPriceSale.Price3 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$productId);
        $exactPrice=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
        }
        $allMoney= $exactPrice * $amountUnit;
        $fiPack=$exactPrice*$packAmount;
        if($amountUnit>0){
        DB::update("UPDATE NewStarfood.dbo.orderStar SET Amount=".$amountUnit.",PackAmount=$packAmount ,Price=".$allMoney.",FiPack=".$fiPack.",PriceAfterTakhfif=$allMoney WHERE SnOrderBYS=".$orderBYSsn);
        return Response::json('good');
        }else{
        DB::delete('DELETE FROM NewStarfood.dbo.orderStar WHERE SnOrderBYS='.$orderBYSsn);
        return Response::json('good');
        }

    }

    public function updatePreOrderBYSFromHome(Request $request)
    {
        $costAmount=0;
        $amelSn=0;
        $costLimit=0;
        $orderBYSsn=$request->get('orderBYSSn');
        $productId=$request->get('kalaId');
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
        $amountUnit=$request->get('amountUnit')*$packAmount;
        $prices=DB::select("select GoodPriceSale.Price3 from Shop.dbo.GoodPriceSale where GoodPriceSale.SnGood=".$productId);
        $exactPrice=0;
        foreach ($prices as $price) {
            $exactPrice=$price->Price3;
        }
        $allMoney= $exactPrice * $amountUnit;
        if($amountUnit>0){
        DB::update("UPDATE NewStarfood.dbo.star_pishKharidOrder SET Amount=".$amountUnit.",PackAmount=$packAmount  ,Price=".$allMoney." WHERE SnOrderBYSPishKharid=".$orderBYSsn);
        return Response::json('good');
        }else{
            DB::table("NewStarfood.dbo.star_pishKharidOrder")->where("SnOrderBYSPishKharid",$orderBYSsn)->delete();
            return Response::json('good');
        }

    }

    public function deleteBYS(Request $request)
    {
        $snBYS=$request->POST('SnOrderBYS');
        DB::delete('DELETE FROM NewStarfood.dbo.orderStar WHERE SnOrderBYS='.$snBYS);
        $buys=Session::get('buy');
        Session::put('buy',$buys-1);
        return Response::json('good');
    }
    public function setFavorite(Request $request)
    {
        $goodSn=$request->get('goodSn');
        $favorits=DB::select("SELECT * FROM NewStarfood.dbo.star_Favorite WHERE customerSn=".Session::get('psn')." AND goodSn=".$goodSn);
        $msg;
        if(count($favorits)>0){
            DB::delete("DELETE FROM NewStarfood.dbo.star_Favorite WHERE customerSn=".Session::get('psn')." AND goodSn=".$goodSn);
        $msg='deleted_'.$goodSn;
        }else{
            DB::insert("INSERT INTO NewStarfood.dbo.star_Favorite(customerSn,goodSn)
            VALUES(".Session::get('psn').",".$goodSn.")") ;
            $msg='added_'.$goodSn;
        }
        return Response::json(mb_convert_encoding($msg, "HTML-ENTITIES", "UTF-8"));
    }

    public function searchKala($name)
    {
        $kalaName=trim($name);
        $words=explode(" ",$kalaName);
        $queryPart=" ";
        $counter=count($words);
       
        foreach ($words as $word) {
        $counter-=1;
            if($counter>0){
                $queryPart.="GoodName LIKE '%$word%' AND ";
            }else{
                $queryPart.="GoodName LIKE '%$word%'";
            }
                
            
        }
        //without stocks-----------------------------------------------------
        $kalaList=DB::select("SELECT  GoodSn,GoodName,UName,Price3,Price4,SnGoodPriceSale,IIF(csn>0,'YES','NO') favorite,productId,IIF(ISNULL(productId,0)=0,0,1) as requested,IIF(zeroExistance=1,0,IIF(ISNULL(SnOrderBYS,0)=0,SumAmount,BoughtAmount)) Amount,CompanyNo,IIF(ISNULL(SnOrderBYS,0)=0,'No','Yes') bought,callOnSale,SnOrderBYS,BoughtAmount,PackAmount,overLine,secondUnit,freeExistance,activeTakhfifPercent,activePishKharid,GoodGroupSn,firstGroupId FROM(
            SELECT  PubGoods.GoodSn,PubGoods.GoodName,PUBGoodUnits.UName,csn,D.productId,GoodPriceSale.Price3,GoodPriceSale.Price4,GoodPriceSale.SnGoodPriceSale,GoodGroupSn,PubGoods.CompanyNo
            ,SumAmount,E.zeroExistance,E.callOnSale,SnOrderBYS,BoughtAmount,PackAmount,E.overLine,secondUnit,star_GoodsSaleRestriction.freeExistance,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.activePishKharid FROM Shop.dbo.PubGoods
            JOIN NewStarfood.dbo.star_GoodsSaleRestriction ON PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
            JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
            join(select SUM(Amount) as SumAmount,SnGood from Shop.dbo.ViewGoodExistsInStock
            where  ViewGoodExistsInStock.FiscalYear=1399 and ViewGoodExistsInStock.CompanyNo=5 group by SnGood )B on PubGoods.GoodSn=B.SnGood 
            left JOIN (select  SnOrderBYS,SnGood,Amount as BoughtAmount,PackAmount from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=2571 and orderStatus=0)f on f.SnGood=PubGoods.GoodSn
            left join (select goodSn as csn from NewStarfood.dbo.star_Favorite WHERE star_Favorite.customerSn=3609)C on PubGoods.GoodSn=C.csn
            LEFT JOIN (select productId from NewStarfood.dbo.star_requestedProduct where customerId=3609)D on PubGoods.GoodSn=D.productId
            left join (select zeroExistance,callOnSale,overLine,productId from NewStarfood.dbo.star_GoodsSaleRestriction)E on E.productId=PubGoods.GoodSn
            JOIN (SELECT * from Shop.dbo.ViewGoodExists) A ON PubGoods.GoodSn=A.SnGood
            LEFT JOIN Shop.dbo.GoodPriceSale ON GoodPriceSale.SnGood=PubGoods.GoodSn
            left join (select GoodUnitSecond.AmountUnit,SnGood,UName as secondUnit from Shop.dbo.GoodUnitSecond
            join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5)G on G.SnGood=PUbGoods.GoodSn
            
            GROUP BY  PubGoods.CompanyNo,GoodGroupSn,PubGoods.GoodSn,E.zeroExistance,E.callOnSale,E.overLine,BoughtAmount,PackAmount,SnOrderBYS,secondUnit,D.productId,PubGoods.GoodName,SumAmount,GoodPriceSale.Price3,GoodPriceSale.Price4,GoodPriceSale.SnGoodPriceSale,PUBGoodUnits.UName,star_GoodsSaleRestriction.activeTakhfifPercent,
            star_GoodsSaleRestriction.freeExistance,star_GoodsSaleRestriction.activePishKharid,csn 
            ) A 
            JOIN (select min(firstGroupId) as firstGroupId,product_id from NewStarfood.dbo.star_add_prod_group group by product_id)b on A.GoodSn=b.product_id 
            WHERE  GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) and CompanyNo=5
            and $queryPart
            order by SumAmount desc");   
        $currency=1;
        $currencyName="ریال";
        $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
        foreach ($currencyExistance as $cr) {
            $currency=$cr->currency;
        }
        if($currency==10){
            $currencyName="تومان";
        }
		$logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view('kala.kalaFromPart',['kala'=>$kalaList,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos, 'kalaName'=>$kalaName]);
    }

    public function searchKalaBySubGroup(Request $request)
    {
        $subGroupId=$request->get('id');
        $kalas=DB::select("SELECT * from Shop.dbo.PubGoods
                            join NewStarfood.dbo.star_add_prod_group on star_add_prod_group.product_id=PubGoods.GoodSn
                            join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            join Shop.dbo.Goods_App on PubGoods.GoodSn=Goods_App.SnGood
                            join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            where star_add_prod_group.secondGroupId=".$subGroupId);
        return Response::json($kalas);
    }

    public function setDescribeKala(Request $request)
    {
        $kalaId=$request->post('kalaId');
        $discription=$request->post('discription');
        $checkDiscription=DB::select("SELECT COUNT(id) as countDiscription from NewStarfood.dbo.star_desc_product where GoodSn=".$kalaId);
        $checkDisc=0;
        foreach ($checkDiscription as $checkDisc) {
            $checkDisc=$checkDisc->countDiscription;
        }
        if($checkDisc>0){
        DB::update("UPDATE NewStarfood.dbo.star_desc_product set descProduct='".$discription."' WHERE GoodSn=".$kalaId);
        }else{
            DB::insert("INSERT INTO NewStarfood.dbo.star_desc_product  VALUES('".$discription."',".$kalaId.")");
        }
        return Response::json("good");
    }


    public function updateChangedPrice(Request $request)
    {
        $SnHDS=$request->post('SnHDS');
        $orderBYSs=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,orderStar.*,PUBGoodUnits.UName from Shop.dbo.PubGoods join NewStarfood.dbo.orderStar on PubGoods.GoodSn=orderStar.SnGood inner join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN WHERE SnHDS=".$SnHDS);
                    //در صورتیکه قیمت ها تغییر کرده باشند
        foreach ($orderBYSs as $order) {
            $prices=DB::select("SELECT * FROM Shop.dbo.GoodPriceSale where SnGood=".$order->GoodSn);
            foreach ($prices as $price) {
                if($order->Fi != $price->Price3){
                    DB::update("UPDATE NewStarfood.dbo.orderStar SET orderStar.Fi=".$price->Price3.",orderStar.Price=".($price->Price3*$order->Amount).",orderStar.FiPack=".(($price->Price3*$order->Amount)/$order->PackAmount).",orderStar.PriceAfterTakhfif=".($price->Price3*$order->Amount).",orderStar.RealFi=".$price->Price3.",orderStar.RealPrice=".$price->Price3*$order->Amount." WHERE SnHDS=".$SnHDS." and SnGood=".$order->GoodSn);
                }
            }
        }
        return redirect('/carts');

    }
    public function addKalaToGroup(Request $request)
    {
        $addableKalas=$request->post('addedKalaToGroup');
        $removeableKalas=$request->post('removeKalaFromGroup');
        $firstGroupId=$request->post("firstGroupId");
        $secondGroupId=$request->post("secondGroupId");
        if($addableKalas){

            foreach ($addableKalas as $kalaId) {
                list($kalaId,$title)=explode('_',$kalaId);
                DB::insert("INSERT INTO NewStarfood.dbo.star_add_prod_group (firstGroupId, product_id, secondGroupId, thirdGroupId, fourthGroupId)
                VALUES(".$firstGroupId.",".$kalaId.", ".$secondGroupId.", 0, 0)");
            }
        }
        if($removeableKalas){
            //delete data from Group
            foreach ($removeableKalas as $kalaId) {
                list($kalaId,$title)=explode('_',$kalaId);
                DB::delete("DELETE FROM NewStarfood.dbo.star_add_prod_group WHERE product_id=".$kalaId);
            }
        }
        return redirect('/listGroup');
    }
    public function getSubGroupKala(Request $request)
    {
        $subGroupId=$request->get('id');
        $kalas=DB::select("select Shop.dbo.PubGoods.GoodName,Shop.dbo.PubGoods.GoodSn from Shop.dbo.PubGoods join NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=star_add_prod_group.product_id
        where star_add_prod_group.secondGroupId=".$subGroupId." and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )");
        return Response::json($kalas);
    }
    public function getAllKalas(Request $request)
    {
        $mainKalaId=$request->post("mainKalaId");
        $allKalas=DB::select("SELECT * FROM Shop.dbo.PubGoods WHERE GoodName!='' and GoodSn!=0 and GoodSn not in( Select mainId from NewStarfood.dbo.star_assameKala join Shop.dbo.PubGoods on PubGoods.GoodSn=star_assameKala.assameId)");

        return Response::json($allKalas);
    }
    public function getPreBuyAbles(Request $request)
    {
        $allKalas=DB::table("Shop.dbo.PubGoods")->JOIN("NewStarfood.dbo.star_GoodsSaleRestriction","productId","=","GoodSn")->where("activePishKharid",1)->select("GoodSn","GoodName")->get();
        return Response::json($allKalas);
    }
    public function searchPreBuyAbleKalas(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        $allKalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn From Shop.dbo.PubGoods join NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=star_GoodsSaleRestriction.productId where PubGoods.GoodName like '%".$searchTerm."%' and star_GoodsSaleRestriction.activePishKharid=1");
        return Response::json($allKalas);
    }



    public function addKalaToList(Request $request){
        $mainKalaId=$request->get("mainKalaId");
        $addableKalas=$request->get('addedKalaToList');
        $removeableKalas=$request->get('removeKalaFromList');

        if($addableKalas){
            foreach ($addableKalas as $kalaId) {
                list($kalaId,$title)=explode('_',$kalaId);
                DB::insert("INSERT INTO NewStarfood.dbo.star_assameKala (mainId, assameId)
                VALUES(".$mainKalaId.",".$kalaId.")");
            }
        }

       if($removeableKalas){
            //delete data from Group
           foreach ($removeableKalas as $kalaId) {
             if($kalaId !="on"){
              list($id,$title)=explode('_',$kalaId);
             DB::delete("DELETE FROM NewStarfood.dbo.star_assameKala WHERE assameId=".$id." and mainId=".$mainKalaId);
                }
            }
        }
        return Response::json($removeableKalas);

    }

    public function addStockToList(Request $request){
        $kalaId=$request->post("kalaId");
        $stockIds=$request->post("addedStockToList");
        $removableStocks=$request->post("removeStockFromList");
        if($stockIds){
            foreach ($stockIds as $stockId) {
                DB::insert("INSERT INTO NewStarfood.dbo.star_addedStock (productId, stockId)
                VALUES(".$kalaId.",".$stockId.")");
            }
        }
        if($removableStocks){
            foreach ($removableStocks as $stockId) {
                DB::delete("DELETE from NewStarfood.dbo.star_addedStock where stockId=".$stockId);
            }
        }
        return Response::json('good');
    }


    public function addOrDeleteKalaFromSubGroup(Request $request)
    {
        $addbles=$request->get("addables");
        $kalaId=$request->get("ProductId");
        $x=0;
        $removables=$request->get('removables');
        if($addbles){
            foreach ($addbles as $addble) {
                list($subGroupId,$firstGroupId)=explode('_',$addble);
                $exitsanceResult=DB::table("NewStarfood.dbo.star_add_prod_group")
                ->where('firstGroupId',$firstGroupId)
                ->where('secondGroupId',$subGroupId)->where('product_id',$kalaId)->get();
                if(count($exitsanceResult)<1){
                    $x=1;
                    DB::table("NewStarfood.dbo.star_add_prod_group")
                    ->insert(['firstGroupId'=>$firstGroupId,'product_id'=>$kalaId,
                    'secondGroupId'=>$subGroupId,'thirdGroupId'=>0,'fourthGroupId'=>0]);
                }

            }
        }

        if($removables){
            foreach ($removables as $removable) {
            list($subGroupId,$firstGroupId)=explode('_',$removable);
            $exitsanceResult=DB::table("NewStarfood.dbo.star_add_prod_group")
                ->where('firstGroupId',$firstGroupId)
                ->where('secondGroupId',$subGroupId)->where('product_id',$kalaId)->get();
                if(count($exitsanceResult)>0){
                    $x=2;
                    DB::table("NewStarfood.dbo.star_add_prod_group")->where("secondGroupId",$subGroupId)->where('product_id',$kalaId)->delete();
                }

        }
    }
        // return Response::json('done');
     return Response::json($kalaId);
    }


    public function addDescKala(Request $request)
    {
        $kalaId=$request->post("kalaId");
        $discription=$request->post("discription");

        return Response::json('good');
    }
    public function changePictures(Request $request)
    {
        $mainGroups=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=0 order by mainGroupPriority asc");
        return view("admin.changePicture",['mainGroups'=>$mainGroups]);
    }
    public function getKalaWithPic($id)
    {
        $subGroupId=$id;
        $listKala=DB::select("SELECT * FROM Shop.dbo.PubGoods join NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=star_add_prod_group.product_Id where star_add_prod_group.secondGroupId=".$subGroupId);
        return view("admin.changeKalaPic",['listKala'=>$listKala]);
    }
    public function changePriceKala(Request $request)
    {
        $kalaId=$request->get("kalaId");
        $kalaPrice=DB::table("Shop.dbo.GoodPriceSale")->where('SnGood',$kalaId)->where("CompanyNo",5)->where('FiscalYear',Session::get("FiscallYear"))->select("*")->get();
        $changedFirstPrice=str_replace(",", "",$request->get("firstPrice"));
        $changedSecondPrice=str_replace(",", "",$request->get("secondPrice"));


        foreach ($kalaPrice as $price) {
            $firstPrice=$price->Price4;
            $secondPrice=$price->Price3;
        }
        DB::table("NewStarfood.dbo.star_KalaPriceHistory")->insert(
        ['userId'=>Session::get('adminId'),'application'=>'starfood','firstPrice'=>($firstPrice/1),'changedFirstPrice'=>($changedFirstPrice/1),
        'secondPrice'=>($secondPrice/1),'changedSecondPrice'=>($changedSecondPrice/1),'productId'=>$kalaId]);


        DB::update("UPDATE Shop.dbo.GoodPriceSale set Price4=".$changedFirstPrice.", Price3=".$changedSecondPrice." where SnGood=".$kalaId." and CompanyNo=5 and FiscalYear=".Session::get("FiscallYear")."");
        return Response::json(1);
    }
    public function getKalaBySubGroups(Request $request)
    {
        $subGrId=$request->get("subGrId");
        $firstGrId=$request->get("firstGrId");
        $kalas;
        if($subGrId==0 and $firstGrId>0){

            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,ViewGoodExists.Amount,
                            GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                            join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            join Shop.dbo.ViewGoodExists on ViewGoodExists.SnGood=PubGoods.GoodSn
                            join NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=star_add_prod_group.product_Id
                            where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49 and
                            star_add_prod_group.firstGroupId=".$firstGrId);
            foreach ($kalas as $kala) {

                $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
    
            }
        }
        elseif($subGrId==0 and $firstGrId==0){

            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,ViewGoodExists.Amount,
                            GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                            JOIN Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            JOIN Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            JOIN Shop.dbo.ViewGoodExists on ViewGoodExists.SnGood=PubGoods.GoodSn
                            JOIN NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=star_add_prod_group.product_Id
                            where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49 ");

            foreach ($kalas as $kala) {

                $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

            }

        }elseif($subGrId>0 and $firstGrId==0){

            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,ViewGoodExists.Amount,
                            GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                            JOIN Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            JOIN Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            JOIN Shop.dbo.ViewGoodExists on ViewGoodExists.SnGood=PubGoods.GoodSn
                            JOIN NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=star_add_prod_group.product_Id
                            where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49 and
                            star_add_prod_group.secondGroupId=".$subGrId);

            foreach ($kalas as $kala) {

                $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

            }

        }else{

            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,ViewGoodExists.Amount,
                            GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                            join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            join Shop.dbo.ViewGoodExists on ViewGoodExists.SnGood=PubGoods.GoodSn
                            join NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=star_add_prod_group.product_Id
                            where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49 and
                            star_add_prod_group.secondGroupId=".$subGrId);
            
            foreach ($kalas as $kala) {

                $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
    
            }
        }
        return Response::json($kalas);
    }
    public function getKalaBybrandItem($code)
    {
        $brandId=$code;
        $overLine=0;
        $callOnSale=0;
        $zeroExistance=0;
        $activePishKharid=0;
        //without stock -------------------------------------------------------------------
        $kalaList=DB::select("SELECT NewStarfood.dbo.star_add_prod_brands.*,PubGoods.GoodName,A.GoodSn,S.Price4,S.Price3,B.SUNAME secondUnit,UNAME as firstUnit,V.Amount,G.GoodGroupSn,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.freeExistance,firstGroupId
        FROM NewStarfood.dbo.star_add_prod_brands
        JOIN Shop.dbo.PubGoods on star_add_prod_brands.productId=PubGoods.GoodSn
        JOIN NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
        JOIN (SELECT PubGoods.GoodSn,PubGoods.GoodGroupSn FROM Shop.dbo.GoodGroups JOIN Shop.dbo.PubGoods on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn) G on G.GoodSn=NewStarfood.dbo.star_add_prod_brands.productId
        JOIN (SELECT PUBGoodUnits.UName,PubGoods.GoodSn from Shop.dbo.PUBGoodUnits JOIN Shop.dbo.PubGoods on PUBGoodUnits.USN=PubGoods.DefaultUnit) A  on A.GoodSn=NewStarfood.dbo.star_add_prod_brands.productId
        JOIN (SELECT PubGoods.GoodName,PubGoods.GoodSn FROM Shop.dbo.PubGoods) C on c.GoodSn=NewStarfood.dbo.star_add_prod_brands.productId
        JOIN (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on NewStarfood.dbo.star_add_prod_brands.productId=V.SnGood
        JOIN NewStarfood.dbo.star_add_prod_group ON PubGoods.GoodSn=star_add_prod_group.product_id
        JOIN NewStarfood.dbo.Star_Group_DEF ON Star_Group_DEF.id=star_add_prod_group.firstGroupId
        LEFT JOIN (Select GoodPriceSale.Price4,GoodPriceSale.Price3,GoodPriceSale.SnGood from Shop.dbo.GoodPriceSale) S on S.SnGood=NewStarfood.dbo.star_add_prod_brands.productId
        LEFT JOIN (SELECT GoodUnitSecond.SnGoodUnit,PUBGoodUnits.UName as SUNAME,GoodUnitSecond.SnGood from Shop.dbo.GoodUnitSecond join Shop.dbo.PUBGoodUnits on GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN) B on NewStarfood.dbo.star_add_prod_brands.productId=B.SnGood
        where NewStarfood.dbo.star_add_prod_brands.brandId=".$brandId." and star_add_prod_brands.productId not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) order by Amount desc");
        
        foreach ($kalaList as $kala) {

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

        foreach ($kalaList as $kala) {
            $overLine=0;
            $callOnSale=0;
            $zeroExistance=0;
            $exist="NO";
            $favorits=DB::select("SELECT * FROM NewStarfood.dbo.star_Favorite");
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

            $restrictionState=DB::table("NewStarfood.dbo.star_GoodsSaleRestriction")->where("productId",$kala->GoodSn)->select("overLine","callOnSale","zeroExistance",'activePishKharid')->get();
            if(count($restrictionState)>0){
                foreach($restrictionState as $rest){
                    if($rest->overLine==1){
                        $overLine=1;
                    }
                    if($rest->callOnSale==1){
                        $callOnSale=1;
                    }
                    if($rest->zeroExistance==1){
                        $zeroExistance=1;
                    }
                    if($rest->activePishKharid==1){
                        $activePishKharid=1;
                    }
                }
            }
            $boughtKalas=DB::select("select  FactorStar.*,orderStar.* from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and orderStatus=0");
            $orderBYSsn;
            $secondUnit;
            $amount;
            $packAmount;
            foreach ($boughtKalas as $boughtKala) {
                $orderBYSsn=$boughtKala->SnOrderBYS;
                $orderGoodSn=$boughtKala->SnGood;
                $amount=$boughtKala->Amount;
                $packAmount=$boughtKala->PackAmount;
                $secondUnits=DB::select("select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood=".$orderGoodSn);
                foreach ($secondUnits as $unit) {
                    $secondUnit=$unit->UName;
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
            $kala->activePishKharid=$activePishKharid;
            if($zeroExistance==1){
            $kala->Amount=0;
            }
        }
		$logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
            return view('kala.kalaFromPart',['kala'=>$kalaList,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos]);
    }
    public function listPictureName(Request $request)
    {
        $dir = 'resources/assets/images/kala';
        $files = scandir($dir, 0);
        $listCustomerIds=array();
        $picState=$request->get("picState");
        $kalas;

        if($picState==0){

            for($i = 2; $i < count($files); $i++){

                $imageAndNumber="";

                list($customerId,$imageNumber)=explode('_', $files[$i]);

                list($imageNumber,$imageSuffix)=explode('.',$imageNumber);

                if($imageNumber==1){

                    array_push($listCustomerIds,$customerId);

                }

            }

            $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,V.Amount,
                            PubGoods.GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                            JOIN Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                            JOIN Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                            JOIN (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood 
                            where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49
                            and PubGoods.CompanyNo=5 and V.FiscalYear=".Session::get("FiscallYear")." and V.Amount>0 and PubGoods.GoodSn not in(" . implode(',', $listCustomerIds) . ")");

            foreach ($kalas as $kala) {

                $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

            }

    }elseif($picState==1){
            
        for($i = 2; $i < count($files); $i++){

            $imageAndNumber="";

            list($customerId,$imageNumber)=explode('_', $files[$i]);

            list($imageNumber,$imageSuffix)=explode('.',$imageNumber);

            if($imageNumber==1){

                array_push($listCustomerIds,$customerId);

            }

        }

        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,V.Amount,
                        PubGoods.GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                        join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                        join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                        JOIN (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood 
                        where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49
                        and PubGoods.CompanyNo=5 and V.FiscalYear=".Session::get("FiscallYear")." and V.Amount>0 and PubGoods.GoodSn in(" . implode(',', $listCustomerIds) . ")");
        
        foreach ($kalas as $kala) {

            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

        }

    }else{

        for($i = 2; $i < count($files); $i++){

            $imageAndNumber="";

            list($customerId,$imageNumber)=explode('_', $files[$i]);

            list($imageNumber,$imageSuffix)=explode('.',$imageNumber);

            if($imageNumber==1){

                array_push($listCustomerIds,$customerId);

            }
        }

        $kalas=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price3,GoodPriceSale.Price4,V.Amount,
                        PubGoods.GoodCde,GoodGroups.NameGRP,PubGoods.GoodGroupSn from Shop.dbo.PubGoods
                        join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                        join Shop.dbo.GoodPriceSale on PubGoods.GoodSn=GoodPriceSale.SnGood
                        JOIN (select * from Shop.dbo.ViewGoodExists where ViewGoodExists.FiscalYear=".Session::get("FiscallYear").") V on PubGoods.GoodSn=V.SnGood 
                        where GoodName!='' and NameGRP!='' and GoodSn!=0 and PubGoods.GoodGroupSn >49
                        and PubGoods.CompanyNo=5 and V.FiscalYear=".Session::get("FiscallYear")." and V.Amount>0");
        
        foreach ($kalas as $kala) {

            $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

        }

    }
    
    return Response::json(mb_convert_encoding($kalas, "HTML-ENTITIES", "UTF-8"));

}

    public function addPriceHistory(Request $request)
    {
        // DB::table("starfood_akhlaqidbostar_KalaPriceHistory")->insert(
        // ('userId'=>Session::get('adminId')
        // ,'application'=>'starfood'
        // ,'firstPrice'=>
        // ,'changedFirstPrice'=>
        // ,'secondPrice'=>
        // ,'changedSecondPrice'=>)
    }

    public function tempRoute(Request $request)
    {
        //----------------PICS--------------------------
        $pics=DB::select("select COUNT(productId) as countProduct,partPic from NewStarfood.dbo.star_add_homePart_stuff where partPic is not null  group by partPic");
        foreach ($pics as $pic) {
            $onePics=DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff WHERE partPic=".$pic->partPic);
            $i=0;
            foreach ($onePics as $pic) {
                $i+=1;
                DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff SET priority=".$i." where productId=".$pic->productId." and partPic=".$pic->partPic);
            }
        }
        //----------------PARTS------------------------
        $parts=DB::select("select COUNT(productId) as countProduct,homepartId from NewStarfood.dbo.star_add_homePart_stuff where homepartId is not null and productId is not null  group by homepartId");
            foreach ($parts as $pic) {
            $onePart=DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff WHERE homepartId=".$pic->homepartId);
            $i=0;
            foreach ($onePart as $part) {
                $i+=1;
                DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff SET priority=".$i." where productId=".$part->productId." and homepartId=".$pic->homepartId);
            }
        }
        //----------------BRANDS----------------------
        $parts=DB::select("select COUNT(productId) as countProduct,homepartId from NewStarfood.dbo.star_add_homePart_stuff where homepartId is not null and brandId is not null  group by homepartId");
            foreach ($parts as $pic) {
            $oneBrand=DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff WHERE homepartId=".$pic->homepartId);
            $i=0;
            foreach ($oneBrand as $part) {
                $i+=1;
                DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff SET priority=".$i." where brandId=".$part->brandId." and homepartId=".$part->homepartId);
            }
        }
        //----------------GROUPS----------------------
        $parts=DB::select("select COUNT(firstGroupId) as countGroup,homepartId from NewStarfood.dbo.star_add_homePart_stuff where homepartId is not null and firstGroupId is not null  group by homepartId");
        foreach ($parts as $pic) {
            $onePart=DB::select("SELECT * FROM NewStarfood.dbo.star_add_homePart_stuff WHERE homepartId=".$pic->homepartId);
            $i=0;
            foreach ($onePart as $part) {
                $i+=1;
                DB::update("UPDATE NewStarfood.dbo.star_add_homePart_stuff SET priority=".$i." where firstGroupId=".$part->firstGroupId." and homepartId=".$part->homepartId);
            }
        }
        return 1;
    }
    function addRequestedProduct(Request $request){
        $customerId=$request->get("customerId");
        $productId=$request->get("productId");
        DB::table("NewStarfood.dbo.star_requestedProduct")->insert(["customerId"=>$customerId,"productId"=>$productId,'acceptance'=>0]);
        return Response::json("good");
    }


    // public function requestedProducts(Request $request){
    //     $requests=DB::select("SELECT * from(
    //     SELECT countRequest,a.productId,GoodName,GoodSn,TimeStamp FROM(SELECT COUNT(star_requestedProduct.id) AS countRequest,min(TimeStamp) as TimeStamp,productId  FROM NewStarfood.dbo.star_requestedProduct group by productId)a
        
    //     JOIN Shop.dbo.PubGoods ON PubGoods.GoodSn=a.productId )b  order by TimeStamp desc");
    //     return View("admin.requestedKala",['products'=>$requests]);
    // }



    public function displayRequestedKala(Request $request)
    {
        $gsn=$request->get('productId');
        $products=DB::select("SELECT DISTINCT star_requestedProduct.customerId,star_requestedProduct.acceptance,Peopels.PSN,Peopels.Name,
                            a.PhoneStr,Peopels.PCode,Peopels.peopeladdress,GoodName,FORMAT(convert( date,star_requestedProduct.TimeStamp),'yyyy/MM/dd','fa-ir') as TimeStamp
                            FROM NewStarfood.dbo.star_requestedProduct join Shop.dbo.Peopels on Peopels.PSN=star_requestedProduct.customerId 
                            join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
							FROM Shop.dbo.PhoneDetail
							GROUP BY SnPeopel)a on star_requestedProduct.customerId=a.SnPeopel
							join (select GoodName,GoodSn from Shop.dbo.PubGoods where CompanyNo=5)c on c.GoodSn=productId
                            where star_requestedProduct.acceptance=0 and ProductId=".$gsn);
        return Response::json($products);
    }

    public function cancelRequestedProduct(Request $request)
    {
        $psn=$request->get("psn");
        $gsn=$request->get("gsn");
        $deleted=DB::delete("DELETE FROM NewStarfood.dbo.star_requestedProduct WHERE productId=".$gsn." and customerId=".$psn);
        if($deleted){
            return Response::json(mb_convert_encoding($deleted, "HTML-ENTITIES", "UTF-8"));
        }else{
            return Response::json(mb_convert_encoding($deleted, "HTML-ENTITIES", "UTF-8"));
        }
    }
    public function removeRequestedKala(Request $request)
    {
        
        $gsn=$request->get('productId');
        
        $deleted=DB::delete("DELETE FROM NewStarfood.dbo.star_requestedProduct WHERE productId=".$gsn);
        
        $requests=DB::select("SELECT * from(
            SELECT countRequest,a.productId,GoodName,GoodSn,TimeStamp FROM(SELECT COUNT(star_requestedProduct.id) AS countRequest,min(TimeStamp) as TimeStamp,productId  FROM NewStarfood.dbo.star_requestedProduct group by productId)a
                    
                    JOIN Shop.dbo.PubGoods ON PubGoods.GoodSn=a.productId )b  order by TimeStamp desc");
        
        return Response::json($requests);    
}
public function searchRequestedKala(Request $request)
{
    $searchTerm=$request->get("searchTerm");
    $requests=DB::select("SELECT * from(
        SELECT countRequest,a.productId,GoodName,GoodSn,TimeStamp FROM(SELECT COUNT(star_requestedProduct.id) AS countRequest,min(TimeStamp) as TimeStamp,productId  FROM NewStarfood.dbo.star_requestedProduct group by productId)a
                
                JOIN Shop.dbo.PubGoods ON PubGoods.GoodSn=a.productId )b 
                        where b.GoodName LIKE '%".$searchTerm."%'  order by TimeStamp desc");
    return Response::json($requests);
}
// Kala warning point list
public function kalaWarningPoint(){
    return view("admin.kalaWarningPoint");
}

public function getTenLastSales(Request $request)
{
    $kalaId=$request->get("kalaId");
    $lastTenSales=DB::select("SELECT * FROM (
                            SELECT * FROM (
                            SELECT TOP 10 SnGood,Amount,PackType,Fi,Price,SnFact FROM Shop.dbo.FactorBYS WHERE SnGood=".$kalaId." order by FactorBYS.TimeStamp desc)a
                            JOIN (SELECT SerialNoHDS,CustomerSn,FactDate FROM Shop.dbo.FactorHDS)b on a.SnFact=b.SerialNoHDS)c
                            JOIN (SELECT Name,PCode,PSN FROM Shop.dbo.Peopels )d on c.CustomerSn=d.PSN");
    return Response::json($lastTenSales);
}



}
