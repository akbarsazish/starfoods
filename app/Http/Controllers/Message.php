<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use DB;
use Response;
/**
 * undocumented class
 */
class Message extends Controller
{
    public function index(Request $request)
    {
        $messages=DB::select("SELECT * FROM NewStarfood.dbo.star_message join Shop.dbo.Peopels on star_message.customerId=Peopels.PSN  WHERE customerId=".Session::get('psn')." order by id asc");
        foreach ($messages as $message) {
            $replays=DB::select("SELECT * FROM NewStarfood.dbo.star_replayMessage WHERE messageId=".$message->id);
            $message->replay=$replays;
        }
        $newMessages=DB::table("NewStarfood.dbo.star_message")->where("customerId",Session::get("psn"))->get("id");
        foreach ($newMessages as $message) {
            DB::update("update NewStarfood.dbo.star_replayMessage set readState=1 where messageId=".$message->id);
        }
        return view('messages.messageList',['messages'=>$messages]);
    }
    public function doAddMessage(Request $request)
    {
        $messageContent=$request->get('pmContent');
        $customerId=Session::get('psn');
        DB::insert("INSERT INTO NewStarfood.dbo.star_message (messageContent,readState	,customerId)
            VALUES('N".$messageContent."',0,".$customerId.")");
            return Response::json("good");
    }
    public function replayMessage(Request $request)
    {
        $replayContent=$request->get('replayContent');
        $messageId=DB::table("NewStarfood.dbo.star_message")->max("id");
        DB::insert("INSERT INTO NewStarfood.dbo.star_replayMessage(replayContent ,messageId,readState) Values('N".$replayContent."' ,".$messageId.",0)");
        $lastReplay=DB::select("SELECT * FROM NewStarfood.dbo.star_replayMessage where id=(SELECT MAX(id) from star_replayMessage) and messageId=".$messageId);
        $msg="";
        $id;

        foreach ($lastReplay as $replay) {
            $msg='
            <div class="d-flex flex-row justify-content-end mb-2" id="replayDiv">
              <div class="p-3 me-3 border" style="border-radius: 15px; background-color: #fbfbfb;">
                 <p class="small mb-0" style="font-size:1rem;">'.$replay->replayContent.'</p>
            </div>
             <img src="/resources/assets/images/girl.png" alt="avatar 1" style="width: 45px; height: 100%;">
          </div> ';
        }
        return Response::json($msg);
    }

}


