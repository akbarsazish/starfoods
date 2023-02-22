<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use BrowserDetect;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
use Response;

class BaseBonus extends Controller
{
    public function baseBonusSetting(Request $request)
    {
        $targets=DB::table("NewStarfood.dbo.star_customer_baseBonus")->get();
        $lotteryPrizes=DB::table("NewStarfood.dbo.star_lotteryPrizes")->get();
        $nazarSanjies=DB::table("NewStarfood.dbo.star_nazarsanji")->join("NewStarfood.dbo.star_question","nazarId","=","star_nazarsanji.id")->get();
        $lotteryMinBonus=DB::select("SELECT * FROM NewStarfood.dbo.star_webSpecialSetting")[0]->lotteryMinBonus;
        return view("baseBonus.basebonusSettings",['targets'=>$targets,'prizes'=>$lotteryPrizes,'nazars'=>$nazarSanjies,'lotteryMinBonus'=>$lotteryMinBonus]);
    }
    public function getTargetInfo(Request $request)
    {
            $targetId=$request->get('targetId');
            $target=DB::table("NewStarfood.dbo.star_customer_baseBonus")->where('id',$targetId)->get();
            return Response::json($target);
    }
    public function editTarget(Request $request)
    {
        $targetId=$request->get("targetId");
        $firstTarget=str_replace(",","",$request->get("firstTarget"));
        $secondTarget=str_replace(",","",$request->get("secondTarget"));
        $thirdTarget=str_replace(",","",$request->get("thirdTarget"));
        $firstTargetBonus=$request->get("firstTargetBonus");
        $secondTargetBonus=$request->get("secondTargetBonus");
        $thirdTargetBonus=$request->get("thirdTargetBonus");
        DB::table("NewStarfood.dbo.star_customer_baseBonus")->where('id','=',$targetId)->update([
            'firstTarget'=>$firstTarget
            ,'secondTarget'=>$secondTarget
            ,'thirdTarget'=>$thirdTarget
            ,'firstTargetBonus'=>$firstTargetBonus
            ,'secondTargetBonus'=>$secondTargetBonus
            ,'thirdTargetBonus'=>$thirdTargetBonus]);
        $targets=DB::table("NewStarfood.dbo.star_customer_baseBonus")->get();

        return Response::json($targets); 
    }

    public function addNazarSanji(Request $request){
        $nazarSanjiName=$request->get("nazarSanjiName");
        $qContent1=$request->get("content1");
        $qContent2=$request->get("content2");
        $qContent3=$request->get("content3");
        DB::table("NewStarfood.dbo.star_nazarSanji")->insert(["Name"=>"".$nazarSanjiName.""]);
        $maxId=DB::table("NewStarfood.dbo.star_nazarSanji")->max("id");
        DB::table("NewStarfood.dbo.star_question")->insert(["question1"=>"".$qContent1."","question2"=>"".$qContent2."","question3"=>"".$qContent3."","nazarId"=>$maxId]);
        $nazarSanjies=DB::table("NewStarfood.dbo.star_nazarsanji")->join("NewStarfood.dbo.star_question","nazarId","=","star_nazarsanji.id")->get();
        return Response::json($nazarSanjies);
    }

    public function getQAnswers(Request $request){
        $nazarId=$request->get("nazarId");
        $qNumber=$request->get("question");
	
        $answerNumber=1;
        switch ($qNumber) {
            case 1:
                $answerNumber="answer1";
                break;
            case 2:
                $answerNumber="answer2";
                break;
            case 3:
                $answerNumber="answer3";
                break;
            default:
            $answerNumber="answer1";
                break;
        }
        $answers=DB::select("SELECT $answerNumber as answer,Name,star_answers.id,nazarId, star_answers.TimeStamp FROM NewStarfood.dbo.star_answers join Shop.dbo.Peopels on customerId=PSN where nazarId=".$nazarId);
        return Response::json($answers);
    }
	
	
  public function editNazar(Request $request){
		 $nazarId=$request->get("nazarId");
		 $questions=DB::table("NewStarfood.dbo.star_question")->join("NewStarfood.dbo.star_nazarsanji","nazarId","=","star_nazarsanji.id")
			-> where("nazarId",$nazarId)->get();
		 return Response::json($questions);
	
	}

	public function updateQuestion(Request $request){
		$nazarId=$request->get("nazarId");
		$nazarSanjiName=$request->get("nazarSanjiName");
        $qContent1=$request->get("content1");
        $qContent2=$request->get("content2");
        $qContent3=$request->get("content3");
        DB::table("NewStarfood.dbo.star_nazarSanji")->where("id",$nazarId)->update(["Name"=>"".$nazarSanjiName.""]);
        DB::table("NewStarfood.dbo.star_question")->where("nazarId",$nazarId)->update(["question1"=>"".$qContent1."","question2"=>"".$qContent2."","question3"=>"".$qContent3.""]);
        $updateQuestion=DB::table("NewStarfood.dbo.star_nazarsanji")->join("NewStarfood.dbo.star_question","nazarId","=","star_nazarsanji.id")->get();
        return Response::json($updateQuestion);
	
	}
	
}
