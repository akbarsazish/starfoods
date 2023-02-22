<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Cookie;
use BrowserDetect;
use Response;
class Logout extends controller{
    public function logout(Request $request)
    {
        DB::delete("DELETE FROM NewStarfood.dbo.star_customerSession1 where   sessionId ='".Session::getId()."' and customerId=".Session::get('psn'));
        Session::forget('psn');
        Session::forget('username');
        return redirect('/login');
    }
    public function login(Request $request)
    {
        return  view('login.login');
    }
    public function checkUser(Request $request)
    {
        $this->validate($request,[
                'username'=>'string|required|max:2000|min:3',
                'password'=>'required|min:3|max:54',
            ],
            [
                'required' => 'فیلد نباید خالی بماند',
                'username.max'=>'متن طویل است طویل است',
                'username.min'=>'متن زیاد کوناه است'
            ]
        );
        $db_ext=DB::connection('sqlsrv1');
        $db_int=DB::connection('sqlsrv');
        $customers=DB::select("SELECT Peopels.PSN,Peopels.Name,Peopels.PeopelEghtesadiCode,PhoneDetail.PhoneStr,customerPss,userName from Shop.dbo.Peopels 
                    join Shop.dbo.PhoneDetail on Peopels.PSN=PhoneDetail.SnPeopel
                    join NewStarfood.dbo.star_CustomerPass on Peopels.PSN=star_CustomerPass.customerId
                    where Peopels.CompanyNo=5 and peopels.GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).")");
        $exist=0;
        $userName;
        $sessions;
        $psn=0;
        foreach ($customers as $customer) {
            if ($customer->userName==$request->post('username') and $customer->customerPss==$request->post('password')){
                $exist=1;
                $userName=$customer->Name;
                $psn=$customer->PSN;
            }
        }
        $allowMobile=0;
        //writ a query to get many mobile after comming at night
        $allowedMobiles=DB::table("NewStarfood.dbo.star_customerRestriction")->where('customerId',$psn)->select("manyMobile")->get();
        foreach ($allowedMobiles as $mobile) {
            $allowMobile=$mobile->manyMobile;
        }


        if($exist==1){
            if($allowMobile>0){
                // if(Session::get('psn')==$psn){
                //     return redirect("/home");
                // }
                $countLogin=$db_int->table('star_customerSession1')->where("customerId",'=',$psn)->get()->count();
                $allowanceCountUser=$db_int->table('star_customerRestriction')->where("customerId",$psn)->select('manyMobile')->get();
                $allowedDevice=1;
                foreach ($allowanceCountUser as $allowanceTedad) {
                    $allowedDevice=$allowanceTedad->manyMobile;
                }
                $sessionKeyId="";
                if($countLogin<$allowedDevice){
                                //set session
                    $fiscallYear=DB::select("SELECT FiscallYear FROM NewStarfood.dbo.star_webSpecialSetting")[0]->FiscallYear;
                    Session::put('FiscallYear',$fiscallYear);
                    Session::put('username', $userName);
                    Session::put('psn',$psn);
                    Session::put('groups',[291,297,299,312,313,314]);
                    $palatform=BrowserDetect::platformFamily();
                    $browser=BrowserDetect::browserFamily();
                    DB::insert("INSERT INTO star_customerSession1(customerId,sessionId,platform,browser) VALUES(".$psn.",'".Session::getId()."','".$palatform."','".$browser."')");
                }else{
                    Session::put('tmpPSN',$psn);
                    Session::put('tmpNAME',$userName);
                    return redirect('/customerConfirmation1');
                }
                $SnLastBuy;
                $SnLastBuy=$db_int->table('factorStar')->where('orderStatus',0)->where('CustomerSn',Session::get('psn'))->get()->max('SnOrder');

                if($SnLastBuy){
                    $allBuys=0;
                    $allBuys=$db_int->table('orderStar')->where('SnHDS',$SnLastBuy)->get()->count();
                    Session::put('buy',$allBuys);
                }else{
                    Session::put('buy',0);
                }
                    return redirect("/home");
            }else{
                //به مشتری اطلاع داده شود که مشکل چیست
                $error="با دفتر شرکت در تماس شوید";
                return view('login.login')->with(['loginError'=>$error]);
            }
        }else{
            //به مشتری اطلاع داده شود که مشکل چیست
            $credentialError="نام کاربری و یا رمز ورود اشتباه است";
            return view('login.login')->with(['loginError'=>$credentialError]);
        }
    }
    public function customerConfirmation1(Request $request)
    {
        $db_int=DB::connection('sqlsrv');
        $sessionKeys=$db_int->table('star_customerSession1')->where('customerId',Session::get('tmpPSN'))->get();
        return view('admin.modalLog',['sessionIds'=>$sessionKeys]);
    }
    public function logOutConfirm(Request $request)
    {
        $db_ext=DB::connection('sqlsrv1');
        $db_int=DB::connection('sqlsrv');
        list($sessionId,$customerId)=explode('_',$request->post('selectedDevice'));
        Session::getHandler()->destroy($sessionId);
        DB::delete("DELETE FROM NewStarfood.dbo.star_customerSession1 where   sessionId ='".$sessionId."' and customerId=".$customerId);
        Session::put('username', Session::get('tmpNAME'));
        Session::put('psn', Session::get('tmpPSN'));
        $fiscallYear=DB::select("SELECT FiscallYear FROM NewStarfood.dbo.star_webSpecialSetting")[0]->FiscallYear;
        Session::put('FiscallYear',$fiscallYear);
        Session::forget('tmpNAME');
        Session::forget('tmpPSN');
        $palatform=BrowserDetect::platformFamily();
        $browser=BrowserDetect::browserFamily();
        DB::insert("INSERT INTO NewStarfood.dbo.star_customerSession1(customerId,sessionId,platform,browser) 		VALUES(".$customerId.",'".Session::getId()."','".$palatform."','".$browser."')");
        
        $orderHDSs=DB::select("SELECT MAX(SnOrder) AS maxorder FROM NewStarfood.dbo.factorStar WHERE orderStatus=0 and CustomerSn=".Session::get('psn'));
        $SnLastBuy=0;
        $SnLastBuy=$orderHDSs[0]->maxorder;
		
        if($SnLastBuy){
			$allBuys=0;
			$orederBYS=DB::select("SELECT COUNT(SnOrderBYS) as allBuys FROM NewStarfood.dbo.orderStar where SnHDS=".$SnLastBuy);
			$allBuys=$orederBYS[0]->allBuys;
			Session::put('buy',$allBuys);
    	}else{
        	Session::put('buy',0);
    	}
        return redirect("/home");
    }
    

    public function guide() {
        return view('login.appGuidence');
    }
}
