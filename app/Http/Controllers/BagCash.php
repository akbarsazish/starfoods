<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use App\Http\Controllers\StarfoodFunLib;
class BagCash extends Controller{
    public function index(Request $request)
    {
        //برای محاسبه کیف تخفیفی
        $counDiscountWallet=new StarfoodFunLib;
        $bonusResult=$counDiscountWallet->customerBonusCalc();
        //کالا برای  امتحان لاتاری
        $porducts=DB::table("NewStarfood.dbo.star_lotteryPrizes")->get();
        //ختم محسبه امتیازات مشتری
        return view('bagCash.bagCash',['monyComTg'=>$bonusResult[0],
        'aghlamComTg'=>$bonusResult[1],'monyComTgBonus'=>$bonusResult[2],
        'aghlamComTgBonus'=>$bonusResult[3],'lotteryMinBonus'=>$bonusResult[4],'allBonus'=>$bonusResult[5],
        'products'=>$porducts]);
    }

    
}