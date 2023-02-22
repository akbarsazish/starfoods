<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
use DB;
use Session;
use Peopels;

class Profile extends Controller{

     public function index (Request $request)
    {
        $profiles=DB::select("SELECT Peopels.Name,PhoneDetail.PhoneStr from  Shop.dbo.Peopels join  Shop.dbo.PhoneDetail on Peopels.PSN=PhoneDetail.SnPeopel
        where Peopels.CompanyNo=5 and  Peopels.PSN=".Session::get('psn'));
        $profile;
        foreach ($profiles as $profile1) {
            $profile=$profile1;
        }

        $checkOfficialExistance=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();
        $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",Session::get('psn'))->get();

        $officialstate=0;
        foreach ($officialAllowed as  $off) {
            if($off->activeOfficialInfo==1){
                $officialstate = 1;
            }else {
            $officialstate = 0 ;
            }
        }

        $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
        $exacHaqiqi=array();
        foreach ($haqiqiCustomers as $haqiqiCustomer) {
            $exacHaqiqi=$haqiqiCustomer;
        }

        $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
        $exactHoqoqi=array();
        foreach ($hoqoqiCustomers as $hoqoqicustomer) {
            $exactHoqoqi=$hoqoqicustomer;
        }

        $factors=DB::select("SELECT TOP 5 FactorHDS.* FROM  Shop.dbo.FactorHDS  where FactorHDS.CustomerSn=".Session::get('psn')." AND  FactType=3 ORDER BY FactDate DESC");
        $orders=DB::select("SELECT  NewStarfood.dbo.OrderHDSS.* FROM  NewStarfood.dbo.OrderHDSS WHERE isSent=0 AND isDistroy=0 AND CustomerSn=".Session::get('psn'));
        foreach ($orders as $order) {
            $orederPrice=0;
            $prices=DB::select("SELECT Price FROM  NewStarfood.dbo.OrderBYSS where SnHDS=".$order->SnOrder);
            foreach ($prices as $price) {
                $orederPrice+=$price->Price;
            }
            $order->Price=$orederPrice;
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

        return view('userProfile.profile',['profile'=>$profile, 'exactHoqoqi'=>$exactHoqoqi, 'exacHaqiqi'=>$exacHaqiqi,'checkOfficialExistance'=>$checkOfficialExistance,'factors'=>$factors,'orders'=>$orders,'officialstate'=>$officialstate,'currency'=>$currency,'currencyName'=>$currencyName]);
    }


    // function to return edit page
    public function editMyprofile($id) {
        $customers=DB::select("SELECT peopels.Name,peopels.peopeladdress,PhoneDetail.PhoneStr FROM  Shop.dbo.Peopels
        join  Shop.dbo.PhoneDetail on Peopels.PSN=PhoneDetail.SnPeopel");
        $exactCustomer;
        foreach ($customers as $customer) {
            $exactCustomer=$customer;
        }
        return view('userProfile.editProfile',['customer'=>$exactCustomer, 'checkOfficialExistance'=>$checkOfficialExistance]);
     }


     public function updateCustomer(Request $request, $id){

            $updateCustomer = Profile::find($id);
            $updateCustomer->name = $request->name;
            $updateCustomer->PhoneStr = $request->PhoneStr;
            // $updateCustomer->address = $request->address;
            $updateCustomer->save();
            return redirect()->route('profile.index')
            ->with('success','Info Has Been updated successfully');

    }
    public function listOrders(Request $request)
    {
        $hds=$request->post("factorSn");
        $orders=DB::select("SELECT PubGoods.GoodName,PubGoods.GoodSn,OrderBYSS.Amount,OrderBYSS.Fi,OrderBYSS.Price,OrderBYSS.DateOrder,OrderBYSS.TimeStamp,OrderBYSS.PackAmount,Peopels.Name,Peopels.PSN,A.UName as secondUnit,B.UName AS firstUnit,payedMoney FROM  NewStarfood.dbo.OrderBYSS join  Shop.dbo.PubGoods on OrderBYSS.SnGood=PubGoods.GoodSn
        join  NewStarfood.dbo.OrderHDSS on OrderHDSS.SnOrder=OrderBYSS.SnHDS join  Shop.dbo.Peopels on OrderHDSS.CustomerSn=Peopels.PSN 
        left join (SELECT PUBGoodUnits.UName,PUBGoodUnits.USN,Shop.dbo.GoodUnitSecond.SnGood  from  Shop.dbo.PUBGoodUnits join  Shop.dbo.GoodUnitSecond on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit)A ON A.SnGood=PubGoods.GoodSn
        join (SELECT PUBGoodUnits.UName,PUBGoodUnits.USN from  Shop.dbo.PUBGoodUnits)B on B.USN=PubGoods.DefaultUnit
         where SnHDS=".$hds);
        $currency=1;
        $currencyName="ریال";
        $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
        foreach ($currencyExistance as $cr) {
            $currency=$cr->currency;
        }
        if($currency==10){
            $currencyName="تومان";
        }

        $payedMoney=$orders[0]->payedMoney;

        return View('factors.listOrder',['orders'=>$orders,'currency'=>$currency,'currencyName'=>$currencyName,'orderSn'=>$hds,'payedMoney'=>$payedMoney]);
    }

}


