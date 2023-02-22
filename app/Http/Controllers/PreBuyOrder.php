<?php

namespace App\Http\Controllers;
use DB;
use Session;
use Response;
use Illuminate\Http\Request;

class PreBuyOrder extends Controller
{
    //
    public function deleteOrderPishKharid(Request $request)
    {
        $orderId=$request->get("SnOrderBYS");
        DB::table("NewStarfood.dbo.star_pishKharidOrder")->where("SnOrderBYSPishKharid",$orderId)->delete();
        return Response::json("good");
    }
}
