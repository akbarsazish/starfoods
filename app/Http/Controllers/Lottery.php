<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use Carbon\Carbon;
class Lottery extends Controller
{
    public function showLottery(){
        return view("lottery.lottery");
    }
    public function editLotteryPrize(Request $request){
        $firstPrize=$request->get("firstPrize");
        $secondPrize=$request->get("secondPrize");
        $thirdPrize=$request->get("thirdPrize");
        $fourthPrize=$request->get("fourthPrize");
        $fifthPrize=$request->get("fifthPrize");
        $sixthPrize=$request->get("sixthPrize");
        $seventhPrize=$request->get("seventhPrize");
        $eightthPrize=$request->get("eightthPrize");
        $ninethPrize=$request->get("ninethPrize");
        $teenthPrize=$request->get("teenthPrize");
        $eleventhPrize=$request->get("eleventhPrize");
        $twelvthPrize=$request->get("twelvthPrize");
        $therteenthPrize=$request->get("thirteenthPrize");
        $fourteenthPrize=$request->get("fourteenthPrize");
        $fifteenthPrize=$request->get("fiftheenthPrize");
        $sixteenthPrize=$request->get("sixteenthPrize");
        $lotteryMinBonus=$request->get("lotteryMinBonus");

        $showfirstPrize=self::checkCechedCheckbox($request->get("showfirstPrize"));
        $showsecondPrize=self::checkCechedCheckbox($request->get("showsecondPrize"));
        $showthirdPrize=self::checkCechedCheckbox($request->get("showthirdPrize"));
        $showfourthPrize=self::checkCechedCheckbox($request->get("showfourthPrize"));
        $showfifthPrize=self::checkCechedCheckbox($request->get("showfifthPrize"));
        $showsixthPrize=self::checkCechedCheckbox($request->get("showsixthPrize"));
        $showseventhPrize=self::checkCechedCheckbox($request->get("showseventhPrize"));
        $showeightthPrize=self::checkCechedCheckbox($request->get("showeightthPrize"));
        $showninethPrize=self::checkCechedCheckbox($request->get("showninethPrize"));
        $showteenthPrize=self::checkCechedCheckbox($request->get("showteenthPrize"));
        $showeleventhPrize=self::checkCechedCheckbox($request->get("showeleventhPrize"));
        $showtwelvthPrize=self::checkCechedCheckbox($request->get("showtwelvthPrize"));
        $showtherteenthPrize=self::checkCechedCheckbox($request->get("showtherteenthPrize"));
        $showfourteenthPrize=self::checkCechedCheckbox($request->get("showfifteenthPrize"));
        $showfifteenthPrize=self::checkCechedCheckbox($request->get("showfifteenthPrize"));
        $showsixteenthPrize=self::checkCechedCheckbox($request->get("showsixteenthPrize"));
        $isFilledPrizes=DB::table("NewStarfood.dbo.star_lotteryPrizes")->count();
        DB::table("NewStarfood.dbo.star_webSpecialSetting")->update(['lotteryMinBonus'=>$lotteryMinBonus]);
        if($isFilledPrizes>0){
            DB::table("NewStarfood.dbo.star_lotteryPrizes")->where('id',1)->update(
                ['firstPrize'=>"".$firstPrize.""
                ,'secondPrize'=>"".$secondPrize.""
                ,'thirdPrize'=>"".$thirdPrize.""
                ,'fourthPrize'=>"".$fourthPrize.""
                ,'fifthPrize'=>"".$fifthPrize.""
                ,'sixthPrize'=>"".$sixthPrize.""
                ,'seventhPrize'=>"".$seventhPrize.""
                ,'eightthPrize'=>"".$eightthPrize.""
                ,'ninethPrize'=>"".$ninethPrize.""
                ,'teenthPrize'=>"".$teenthPrize.""
                ,'eleventhPrize'=>"".$eleventhPrize.""
                ,'twelvthPrize'=>"".$twelvthPrize.""
                ,'therteenthPrize'=>"".$therteenthPrize.""
                ,'fourteenthPrize'=>"".$fourteenthPrize.""
                ,'fifteenthPrize'=>"".$fifteenthPrize.""
                ,'sixteenthPrize'=>"".$sixteenthPrize.""
            ,'showfirstPrize'=>$showfirstPrize
            ,'showsecondPrize'=>$showsecondPrize
            ,'showthirdPrize'=>$showthirdPrize
            ,'showfourthPrize'=>$showfourthPrize
            ,'showfifthPrize'=>$showfifthPrize
            ,'showsixthPrize'=>$showsixthPrize
            ,'showseventhPrize'=>$showseventhPrize
            ,'showeightthPrize'=>$showeightthPrize
            ,'showninethPrize'=>$showninethPrize
            ,'showteenthPrize'=>$showteenthPrize
            ,'showeleventhPrize'=>$showeleventhPrize
            ,'showtwelvthPrize'=>$showtwelvthPrize
            ,'showtherteenthPrize'=>$showtherteenthPrize
            ,'showfourteenthPrize'=>$showfourteenthPrize
            ,'showfifteenthPrize'=>$showfifteenthPrize
            ,'showsixteenthPrize'=>$showsixteenthPrize
        ]);

        }else{
            DB::table("NewStarfood.dbo.star_lotteryPrizes")->insert(
                ['firstPrize'=>"".$firstPrize.""
                ,'secondPrize'=>"".$secondPrize.""
                ,'thirdPrize'=>"".$thirdPrize.""
                ,'fourthPrize'=>"".$fourthPrize.""
                ,'fifthPrize'=>"".$fifthPrize.""
                ,'sixthPrize'=>"".$sixthPrize.""
                ,'seventhPrize'=>"".$seventhPrize.""
                ,'eightthPrize'=>"".$eightthPrize.""
                ,'ninethPrize'=>"".$ninethPrize.""
                ,'teenthPrize'=>"".$teenthPrize.""
                ,'eleventhPrize'=>"".$eleventhPrize.""
                ,'twelvthPrize'=>"".$twelvthPrize.""
                ,'therteenthPrize'=>"".$therteenthPrize.""
                ,'fourteenthPrize'=>"".$fourteenthPrize.""
                ,'fifteenthPrize'=>"".$fifteenthPrize.""
                ,'sixteenthPrize'=>"".$sixteenthPrize.""]);

        }
return redirect('/baseBonusSettings');
    }

    public function getLotteryInfo(Request $request)
    {
        $lotteryPrizes=DB::table("NewStarfood.dbo.star_lotteryPrizes")->get();
        return Response::json($lotteryPrizes);
    }
    public function checkCechedCheckbox($getdata)
    {
        if($getdata){
        return 1;
        }else {
        return 0;
        }
    }
    public function setCustomerLotteryHistory(Request $request)
    {
        $product=$request->get("product");
        $customerId=$request->get("customerId");
        //DB::table("NewStarfood.dbo.star_TryLottery")->insert(['customerId'=>$customerId,'lotteryId'=>1,'wonPrize'=>"$product"]);
        return Response::json("success");
    }
    public function lotteryResult(Request $request)
    {
        $lotteryTryResult=DB::select("select Convert(date,timestam) as lastTryDate,Name,PhoneStr,id,PSN,wonPrize,Istaken,customerId,Convert(date,tasviyahDate) as tasviyahDate from NewStarfood.dbo.star_TryLottery join Shop.dbo.Peopels on customerId=PSN
        join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
		FROM Shop.dbo.PhoneDetail
		GROUP BY SnPeopel)e on Peopels.PSN=e.SnPeopel");
        // گیمر لیست کیوری
     $players=DB::select("SELECT * FROM (
        SELECT Row,Name,posId,PosName FROM (
        SELECT ROW_NUMBER() 
                        OVER (ORDER BY id)  AS Row,id,PosName,
          posId
        FROM NewStarfood.dbo.star_game_history
        
        unpivot
        (
          posId
          for PosName IN (firstPosId,secondPosId,thirdPosId,fourthPosId,fifthPosId,sixthPosId,seventhPosId,eightPosId,ninthPosId,teenthPosId)
        ) unpiv
        
        )a 
        
        JOIN Shop.dbo.Peopels ON a.posId=PSN
        
        WHERE a.id=(SELECT MAX(id) AS lastId FROM NewStarfood.dbo.star_game_history)
        )b JOIN 
        (
        SELECT * FROM(
        SELECT Row,PrizeName,prize FROM (
        SELECT ROW_NUMBER() OVER (ORDER BY id)  AS Row,id,PrizeName,
          prize
        FROM NewStarfood.dbo.star_game_history
        
        unpivot
        (
          prize
          for PrizeName in (firstPrize,secondPrize,thirdPrize,fourthPrize,fifthPrize,sixthPrize,seventhPrize,eightthPrize,ninthPrize,teenthPrize)
        ) unpiv
        
        
        )a WHERE a.id=(SELECT MAX(id) AS lastId FROM NewStarfood.dbo.star_game_history)
        )c )d ON b.Row=d.Row");


        return view('admin.lotteryResult',['lotteryTryResult'=>$lotteryTryResult, 'players'=>$players]);
    }
    public function tasviyeahLottery(Request $request)
    {
        $customerId=$request->post("customerId");
        $lotteryId=$request->post("lotteryTryId");

        DB::table("NewStarfood.dbo.star_TryLottery")->where('id',$lotteryId)->where('customerId',$customerId)->update(['Istaken'=>1,'tasviyahDate'=>Carbon::now()]);
        return redirect('/lotteryResult');
    }
}
