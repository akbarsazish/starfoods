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
class OnlineAccount extends Controller {

    public function index(Request $request)
    {
        $pays=DB::select("SELECT SerialNoHDS,id, Name,PSN,payedMoney,FactNo,FORMAT(payedOnline.TimeStamp,'yyyy/0M/0d','FA-IR') as payedDate,payedOnline.isSent,Convert(VARCHAR(10),payedOnline.TimeStamp,8) as TimeStamp from NewStarfood.dbo.payedOnline
        JOIN Shop.dbo.FactorHDS on payedOnline.factorSn=FactorHDS.SerialNoHDS
        JOIN SHop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN where CONVERT(date,payedOnline.TimeStamp)=CONVERT(date,current_timestamp)");
        return view("accounting.payedOnline",['pays'=>$pays]);
    }
    public function sendPayToHisabdari(Request $request)
    {
        $paySn=$request->get("paySn");
        $payState=$request->get("payState");
        DB::table("NewStarfood.dbo.payedOnline")->where("id",$paySn)->update(['isSent'=>$payState]);
        $pays=DB::select("SELECT SerialNoHDS,id, Name,PSN,payedMoney,FactNo,FORMAT(payedOnline.TimeStamp,'yyyy/0M/0d','FA-IR') as payedDate,payedOnline.isSent,Convert(VARCHAR(10),payedOnline.TimeStamp,8) as TimeStamp from NewStarfood.dbo.payedOnline
        JOIN Shop.dbo.FactorHDS on payedOnline.factorSn=FactorHDS.SerialNoHDS
        JOIN SHop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN where CONVERT(date,payedOnline.TimeStamp)=CONVERT(date,current_timestamp)");
        return Response::json($pays);
    }
    public function remainedPays(Request $request)
    {
        $payState=$request->get("payState");
        $pays=DB::select("SELECT SerialNoHDS,id, Name,PSN,payedMoney,FactNo,FORMAT(payedOnline.TimeStamp,'yyyy/0M/0d','FA-IR') as payedDate,payedOnline.isSent,Convert(VARCHAR(10),payedOnline.TimeStamp,8) as TimeStamp from NewStarfood.dbo.payedOnline
        JOIN Shop.dbo.FactorHDS on payedOnline.factorSn=FactorHDS.SerialNoHDS
        JOIN SHop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN where isSent=$payState");
        return Response::json($pays);
    }
    public function paysFromDate(Request $request)
    {
        $payState=$request->get("payState");
        $fromDate=$request->get("fromDate");
        $pays=DB::select("SELECT SerialNoHDS,id, Name,PSN,payedMoney,FactNo,FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') as payedDate,payedOnline.isSent,Convert(VARCHAR(10),payedOnline.TimeStamp,8) as TimeStamp from NewStarfood.dbo.payedOnline
        JOIN Shop.dbo.FactorHDS on payedOnline.factorSn=FactorHDS.SerialNoHDS
        JOIN SHop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN where isSent=1 and FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') >= '$fromDate'");
        return Response::json($pays);
    }
    public function getPayedOnline(Request $request)
    {
        $queryPart="";
        $payState=$request->get("payState");
        $fromDate=$request->get("fromDate");
        $toDate=$request->get("toDate");
        $name=$request->get("name");
        $pCode=$request->get("pCode");

        if($payState==1 and strlen($fromDate)>3 and strlen($toDate)<3 ){
            $queryPart="isSent=1  AND Name like '%$name%' AND PCode like '%$pCode%' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') >= '$fromDate'";
        }



        if($payState==2 and strlen($fromDate)<3 and strlen($toDate)<3 ){
            $queryPart="(isSent=0 or isSent=1)  AND Name like '%$name%' AND PCode like '%$pCode%'";
        }
        if($payState==2 and strlen($fromDate)>3 and strlen($toDate)>3 ){
            $queryPart="(isSent=0 or isSent=1)  AND Name like '%$name%' AND PCode like '%$pCode%' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') >= '$fromDate' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') <= '$toDate'";
        }
        if($payState==2 and strlen($fromDate)>3 and strlen($toDate)<3 ){
            $queryPart="(isSent=0 or isSent=1)  AND Name like '%$name%' AND PCode like '%$pCode%' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') >= '$fromDate'";
        }



        if($payState==1 and strlen($fromDate)<3 and strlen($toDate)<3 ){
            $queryPart="isSent=1  AND Name like '%$name%' AND PCode like '%$pCode%'";
        }

        if($payState==0 and strlen($fromDate)<3 and strlen($toDate)<3 ){
            $queryPart="isSent=0  AND Name like '%$name%' AND PCode like '%$pCode%'";
        }
        
        if($payState==1 and strlen($fromDate)>3 and strlen($toDate)>3 ){
            $queryPart="isSent=1  AND Name like '%$name%' AND PCode like '%$pCode%' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') >= '$fromDate' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') <= '$toDate'";
        }

        if($payState==0 and strlen($fromDate)>3 and strlen($toDate)<3 ){
            $queryPart="isSent=0 AND Name like '%$name%' AND PCode like '%$pCode%' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') >= '$fromDate' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') <= '$toDate'";
        }

        if($payState==0 and strlen($fromDate)>3 and strlen($toDate)>3 ){
            $queryPart="isSent=0  AND Name like '%$name%' AND PCode like '%$pCode%' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') >= '$fromDate' AND FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') <= '$toDate'";
        }

        $pays=DB::select("SELECT SerialNoHDS,id, Name,PSN,payedMoney,FactNo,FORMAT(payedOnline.TimeStamp,'yyyy/M/0d','FA-IR') as payedDate,payedOnline.isSent,Convert(VARCHAR(10),payedOnline.TimeStamp,8) as TimeStamp from NewStarfood.dbo.payedOnline
        JOIN Shop.dbo.FactorHDS ON payedOnline.factorSn=FactorHDS.SerialNoHDS
        JOIN SHop.dbo.Peopels ON FactorHDS.CustomerSn=Peopels.PSN WHERE $queryPart");
        return Response::json($pays);
    }
}