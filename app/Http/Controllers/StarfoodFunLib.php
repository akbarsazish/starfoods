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
class StarfoodFunLib extends Controller
{
    public function updateOrInsertOrders($SnHDS,$SnOrderStar)
    {
        $todayDate = Jalalian::fromCarbon(Carbon::today())->format('Y/m/d');
        //اگر قبلا این کالا فرستاده شده بود ویرایش شود
        $updateables=DB::select("SELECT * FROM NewStarfood.dbo.orderStar WHERE SnHDS=$SnOrderStar AND SnGood IN( SELECT SnGood FROM NewStarfood.dbo.OrderBYSS WHERE SnHDS=$SnHDS)");
        foreach ($updateables as $updateable) {
            # code...
            DB::update("UPDATE NewStarfood.dbo.OrderBYSS SET PackAmount+=($updateable->PackAmount),Amount+=$updateable->Amount,Price+=$updateable->Price,PriceAfterTakhfif+=$updateable->PriceAfterTakhfif WHERE SnHDS=$SnHDS and SnGood=$updateable->SnGood");
        }
        //اگر قبلا این کالا فرستاده نشده بود وارد تبدیل شود
        $insertables=DB::select("SELECT * FROM NewStarfood.dbo.orderStar WHERE SnHDS=$SnOrderStar AND SnGood NOT IN(SELECT SnGood FROM NewStarfood.dbo.OrderBYSS WHERE SnHDS=$SnHDS)");
        foreach ($insertables as $order) {
            # code...
            DB::insert("INSERT INTO NewStarfood.dbo.OrderBYSS(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

            VALUES(5,".$SnHDS.",".$order->SnGood.",".$order->PackType.",".($order->PackAmount).",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->PriceAfterTakhfif.",0,0,".$order->Price.",".$order->PriceAfterTakhfif.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
        }
    }

    public function discountWalletCalc()
    {                   
                       //برای محاسبه کیف تخفیفی
                       $defaultPercent=DB::select("SELECT percentTakhfif FROM NewStarfood.dbo.star_webSpecialSetting")[0]->percentTakhfif;
                       $personalPercent=DB::select("SELECT percentTakhfif FROM NewStarfood.dbo.star_customerRestriction where customerId=".Session::get("psn")."")[0]->percentTakhfif;
                       if($personalPercent){
                           $lastPercentTakhfif=$personalPercent;
                       }else{
                           $lastPercentTakhfif=$defaultPercent;
                       }
                       $allMoneyTakhfifResult=0;
                        $lastUsedDate='1401/10/10';
                         $lastUsedHistoryDate=DB::table("NewStarfood.dbo.star_takhfifHistory")->where("customerId",Session::get("psn"))->max("changeDate");
                        if($lastUsedHistoryDate){
                            $lastUsedDate=$lastUsedHistoryDate;
                        }
                        $allMoneyTakhfif=DB::select("SELECT SUM(NetPriceHDS)*$lastPercentTakhfif/100 as SummedAllMoney from Shop.dbo.GetAndPayHDS where CompanyNo=5
                                                    and GetOrPayHDS=1 and FiscalYear=".Session::get("FiscallYear")." and DocDate>'$lastUsedDate' and PeopelHDS=".Session::get("psn")."");
                        $allMoneyTakhfifResult=$allMoneyTakhfif[0]->SummedAllMoney;
                       return $allMoneyTakhfifResult;
    }
    public function discountWalletCase()
    {
        $discountCase=0;
        $discountCase=DB::table("NewStarfood.dbo.star_takhfifHistory")->where("customerId",Session::get("psn"))->where("isUsed",0)->sum("money");
        return $discountCase;
    }
    public function customerBonusCalc()
    {
        $count_All_aghlam=0;//تعداد اقلام خرید شده
        $sumAllFactor=0;//مجموع پول خرید شده
        $aghlamComTg="هیچکدام";
        //آخرین تارگت تکمیل شده مشتری
        $aghlamComTgBonus=0;//امتیازات آخرین تارگت
        $monyComTg="هیچکدام";
        //آخرین تارگت تکمیل شده مبلغ
        $monyComTgBonus=0;//امتیازات آخرین تارگت مبلغ
        $lotteryMinBonus=0;//حد اقل امتیاز برای فعاسازی شانس لاتری
        $allBonus=0;//مجموع امتیازات
        $lastPercentTakhfif=0;
        //محاسبه امتیازات مشتری
        $maxDateOfTryLottery=DB::select("select MAX(timestam) as lastTryDate from NewStarfood.dbo.star_TryLottery where customerId=".Session::get("psn")." group by customerId");
        $lastTryDate='2023-01-07 18:36:08.000';
        if(count($maxDateOfTryLottery)>0){
                $lastTryDate=$maxDateOfTryLottery[0]->lastTryDate;
        }
        $lotteryMinBonus=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get("lotteryMinBonus")[0]->lotteryMinBonus;
        $allMoneyTillNow=DB::select("SELECT SUM(NetPriceHDS) AS allMoney FROM SHop.dbo.FactorHDS WHERE FactType=3 AND FactorHDS.CompanyNo=5 AND CustomerSn=".Session::get("psn")." and CONVERT(date,timestamp)>CONVERT(date,'".$lastTryDate."') GROUP BY CustomerSn");
        if(count($allMoneyTillNow)>0){
            $sumAllFactor=$allMoneyTillNow[0]->allMoney;
        }
        $countAghlam=DB::select("SELECT count(SnGood) AS countGood,CustomerSn FROM(
            SELECT * FROM (SELECT MAX(TimeStamp)AS maxTime,SnGood,CustomerSn FROM(
            SELECT * FROM(
                SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood,CustomerSn FROM Shop.dbo.FactorHDS
                JOIN Shop.dbo.FactorBYS ON FactorHDS.SerialNoHDS=FactorBYS.SnFact)a
                )g WHERE CONVERT(DATE,TimeStamp)>=CONVERT(date,'".$lastTryDate."') GROUP BY SnGood,CustomerSn)c
                )e WHERE CustomerSn=".Session::get("psn")." GROUP BY CustomerSn");
        if(count($countAghlam)>0){
            $count_All_aghlam=$countAghlam[0]->countGood;
        }
        //معیارهای از قبل تعریف شده ای امتیازات
        $targets=DB::table("NewStarfood.dbo.star_customer_baseBonus")->get();
        //تارگت‌های مبلغ خرید

        foreach($targets as $target){
            if($target->id==1){
                $installSelfBonus=$target->firstTargetBonus;
            }
            if($target->id==2){
                if($sumAllFactor >= $target->thirdTarget){
                    $monyComTg="تارگیت سوم";
                    $monyComTgBonus=$target->thirdTargetBonus;
                }else{
                    if($sumAllFactor >= $target->secondTarget){
                        $monyComTg="تارگیت دوم";
                        $monyComTgBonus=$target->secondTargetBonus;
                    }else{
                        if($sumAllFactor >= $target->firstTarget){
                            $monyComTg="تارگیت اول";
                            $monyComTgBonus=$target->firstTargetBonus;
                        }
                    }
                }
            }
                //تارگت‌های اقلام خرید
            if($target->id==3){
                if($count_All_aghlam >= $target->thirdTarget){
                    $aghlamComTg="تارگیت سوم";
                    $aghlamComTgBonus=$target->thirdTargetBonus;
                }else{
                    if($count_All_aghlam >= $target->secondTarget){
                        $aghlamComTg="تارگیت دوم";
                        $aghlamComTgBonus=$target->secondTargetBonus;
                    }else{
                        if($count_All_aghlam >= $target->firstTarget){
                            $aghlamComTg="تارگیت اول";
                            $aghlamComTgBonus=$target->firstTargetBonus;
                        }
                    }
                }
            }
        }
        
        //محاسبه همه تخفیفات
        $allBonus=$aghlamComTgBonus+$monyComTgBonus+$installSelfBonus;
        return [$monyComTg,$aghlamComTg,$monyComTgBonus,$aghlamComTgBonus,$lotteryMinBonus,$allBonus];
    }
}