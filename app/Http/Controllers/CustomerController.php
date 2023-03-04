<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Response;
use Session;
use URL;
class CustomerController extends Controller{

// The following method return the form to insert haqiq customer into database
        public function haqiqiCustomerAdd(){
                $customerId=Session::get('psn');
                $checkHaqiqiExist=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();
                $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();

                $exacHaqiqi=array();
                foreach ($haqiqiCustomers as $haqiqiCustomer) {
                    $exacHaqiqi=$haqiqiCustomer;
                }
                $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
                $exactHoqoqi=array();
                foreach ($hoqoqiCustomers as $hoqoqiCustomer) {
                    $exactHoqoqi=$hoqoqiCustomer;
                }
                return View('customer.addCustomerProfile', ['checkHaqiqiExist'=>$checkHaqiqiExist, 'haqiqiCustomers'=>$haqiqiCustomers,'exactHoqoqi'=>$exactHoqoqi, 'exacHaqiqi'=>$exacHaqiqi]);

        }


        public function haqiqiCustomerAdmin($id){
            $customerId=$id;
            $checkHaqiqiExist=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",$id)->count();
            $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', $id)->select("*")->get();

            $exacHaqiqi=array();
            foreach ($haqiqiCustomers as $haqiqiCustomer) {
                $exacHaqiqi=$haqiqiCustomer;
            }

            $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', $id)->select("*")->get();
            $exactHoqoqi=array();
            foreach ($hoqoqiCustomers as $hoqoqiCustomer) {
                $exactHoqoqi=$hoqoqiCustomer;
            }
            $chekCustomerType="haqiqi";
            if(count($haqiqiCustomers)>0){
                $chekCustomerType="haqiqi";
            }else{
                $chekCustomerType="hoqoqi";
            }
            return View('customer.addCustomerProfileAdmin', ['checkHaqiqiExist'=>$checkHaqiqiExist,'id'=>$id,'checkType'=>$chekCustomerType, 'haqiqiCustomers'=>$haqiqiCustomers,'exactHoqoqi'=>$exactHoqoqi, 'exacHaqiqi'=>$exacHaqiqi]);

    }



// The following methods check if the haqiqi customer exist update the current customer else add new customer
        public function storeHaqiqiCustomer(Request $request){

            $customerShopSn=$request->post("customerShopSn");
            $checkExistance=DB::table("NewStarfood.dbo.star_Customer")->where('customerType', $request->post("customerType"))->where('customerShopSn', Session::get('psn'))->count('customerShopSn');

            // if the customer exist already then update the some fields
            if($checkExistance>0){

                    $customerName=$request->post("customerName");
                    $familyName=$request->post("familyName");
                    $codeMilli=$request->post("codeMilli");
                    $codeEqtisadi=$request->post("codeEqtisadi");
                    $codeNaqsh=$request->post("codeNaqsh");
                    $address=$request->post("address");
                    $codePosti=$request->post("codePosti");
                    $email=$request->post("email");
                    $phoneNo=$request->post("phoneNo");
                    $shenasNamahNo=$request->post("shenasNamahNo");
                    $sabetPhoneNo=$request->post("sabetPhoneNo");
                    $customerType=$request->post("customerType");
                    $customerShopSn=$request->post("customerShopSn");


                DB::update("UPDATE star_Customer SET
                    customerName='".$customerName."',
                    familyName='".$familyName."',
                    codeMilli='".$codeMilli."',
                    codeEqtisadi ='".$codeEqtisadi."',
                    codeNaqsh ='".$codeNaqsh."',
                    address='".$address."',
                    codePosti='".$codePosti."',
                    email='".$email."',
                    shenasNamahNo='".$shenasNamahNo."',
                    sabetPhoneNo='".$sabetPhoneNo."',
                    phoneNo='".$phoneNo."',
                    customerType='".$customerType."',
                    customerShopSn='".Session::get("psn")."'
                    where customerType='".$customerType."' and customerShopSn='".Session::get("psn")."'");
                    $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
                    $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",Session::get('psn'))->get();
                    DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".Session::get("psn"));
                    // return View('userProfile.profile', ['haqiqicustomers'=>$haqiqiCustomers]);

                    return redirect('profile');


            }else{
                    $customerName=$request->post("customerName");
                    $familyName=$request->post("familyName");
                    $codeMilli=$request->post("codeMilli");
                    $codeEqtisadi=$request->post("codeEqtisadi");
                    $codeNaqsh=$request->post("codeNaqsh");
                    $address=$request->post("address");
                    $codePosti=$request->post("codePosti");
                    $email=$request->post("email");
                    $phoneNo=$request->post("phoneNo");
                    $shenasNamahNo=$request->post("shenasNamahNo");
                    $sabetPhoneNo=$request->post("sabetPhoneNo");
                    $customerType=$request->post("customerType");
                    $customerShopSn=$request->post(Session::get("psn"));

                DB::table("NewStarfood.dbo.star_Customer")->insert([
                    'customerName'=>"".$customerName."",
                    'familyName'=>"".$familyName."",
                    'codeMilli'=>"".$codeMilli."",
                    'codeEqtisadi'=>"".$codeEqtisadi."",
                    'codeNaqsh'=>"".$codeNaqsh."",
                    'address'=>"".$address."",
                    'codePosti'=>"".$codePosti."",
                    'email'=>"".$email."",
                    'phoneNo'=>"".$phoneNo."",
                    'shenasNamahNo'=>"".$shenasNamahNo."",
                    'sabetPhoneNo'=>"".$sabetPhoneNo."",
                    'customerType'=>"".$customerType."",
                    'customerShopSn'=>''.Session::get("psn").''
                    ]);
                    $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",Session::get('psn'))->get();
                    DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".Session::get("psn"));

                    return redirect('profile');
            }
}

// The following methods check if the haqiqi customer exist update the current customer else add new customer
public function storeHaqiqiCustomerAdmin(Request $request){

    $customerShopSn=$request->post("customerShopSn");
    $checkExistance=DB::table("NewStarfood.dbo.star_Customer")->where('customerType', $request->post("customerType"))->where('customerShopSn', Session::get('psn'))->count('customerShopSn');

    // if the customer exist already then update the some fields
    if($checkExistance>0){

            $customerName=$request->post("customerName");
            $id=$request->post("id");
            $familyName=$request->post("familyName");
            $codeMilli=$request->post("codeMilli");
            $codeEqtisadi=$request->post("codeEqtisadi");
            $codeNaqsh=$request->post("codeNaqsh");
            $address=$request->post("address");
            $codePosti=$request->post("codePosti");
            $email=$request->post("email");
            $phoneNo=$request->post("phoneNo");
            $shenasNamahNo=$request->post("shenasNamahNo");
            $sabetPhoneNo=$request->post("sabetPhoneNo");
            $customerType=$request->post("customerType");
            $customerShopSn=$id;


        DB::update("UPDATE star_Customer SET
            customerName='".$customerName."',
            familyName='".$familyName."',
            codeMilli='".$codeMilli."',
            codeEqtisadi ='".$codeEqtisadi."',
            codeNaqsh ='".$codeNaqsh."',
            address='".$address."',
            codePosti='".$codePosti."',
            email='".$email."',
            shenasNamahNo='".$shenasNamahNo."',
            sabetPhoneNo='".$sabetPhoneNo."',
            phoneNo='".$phoneNo."',
            customerType='".$customerType."',
            customerShopSn='".$id."'
            where customerType='".$customerType."' and customerShopSn='".$id."'");
            $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', $id)->select("*")->get();
            $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$id)->get();
            DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".$id);
            // return View('userProfile.profile', ['haqiqicustomers'=>$haqiqiCustomers]);

            return redirect('/listCustomers');


    }else{
            $customerName=$request->post("customerName");
            $familyName=$request->post("familyName");
            $codeMilli=$request->post("codeMilli");
            $codeEqtisadi=$request->post("codeEqtisadi");
            $codeNaqsh=$request->post("codeNaqsh");
            $address=$request->post("address");
            $codePosti=$request->post("codePosti");
            $email=$request->post("email");
            $phoneNo=$request->post("phoneNo");
            $shenasNamahNo=$request->post("shenasNamahNo");
            $sabetPhoneNo=$request->post("sabetPhoneNo");
            $customerType=$request->post("customerType");
            $customerShopSn=$id;

        DB::table("NewStarfood.dbo.star_Customer")->insert([
            'customerName'=>"".$customerName."",
            'familyName'=>"".$familyName."",
            'codeMilli'=>"".$codeMilli."",
            'codeEqtisadi'=>"".$codeEqtisadi."",
            'codeNaqsh'=>"".$codeNaqsh."",
            'address'=>"".$address."",
            'codePosti'=>"".$codePosti."",
            'email'=>"".$email."",
            'phoneNo'=>"".$phoneNo."",
            'shenasNamahNo'=>"".$shenasNamahNo."",
            'sabetPhoneNo'=>"".$sabetPhoneNo."",
            'customerType'=>"".$customerType."",
            'customerShopSn'=>''.$id.''
            ]);
            $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$id)->get();
            DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".$id);

            return redirect('/listCustomers');


    }
}
// The following method shows the list of hoqoqi customer
    public function showHoqoqiCustomer(){

        return View('admin.customer.customerList');

    }


// The following method return the form to insert hoqoqi customer into database
    public function hoqoqiCustomerAdd(){

        $customerId=Session::get('psn');
        $checkHoqoqiExist=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();

        $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
        $exactHoqoqi=array();
        foreach ($hoqoqiCustomers as $hoqoqiCustomer) {
            $exactHoqoqi=$hoqoqiCustomer;
        }

        return View('customer.partial.hoqoqiList', ['checkHoqoqiExist'=>$checkHoqoqiExist, 'exactHoqoqi'=>$exactHoqoqi]);

    }



// The following methods check if the hoqoqi customer exist update the current customer else add new customer
    public function storeHoqoqiCustomer(Request $request){

    $customerShopSn=$request->post("customerShopSn");
    $checkExistance=DB::table("NewStarfood.dbo.star_Customer")->where('customerType', $request->post("customerType"))->where('customerShopSn', Session::get('psn'))->count('customerShopSn');

    // if the customer exist already then update the some fields
    if($checkExistance>0){

            $companyName=$request->post("companyName");
            $registerNo=$request->post("registerNo");
            $shenasahMilli=$request->post("shenasahMilli");
            $codeEqtisadi=$request->post("codeEqtisadi");
            $codeNaqsh=$request->post("codeNaqsh");
            $address=$request->post("address");
            $codePosti=$request->post("codePosti");
            $email=$request->post("email");
            $phoneNo=$request->post("phoneNo");
            $sabetPhoneNo=$request->post("sabetPhoneNo");
            $customerType=$request->post("customerType");
            $customerShopSn=$request->post("customerShopSn");


        DB::update("UPDATE star_Customer SET
            companyName='".$companyName."',
            registerNo='".$registerNo."',
            shenasahMilli='".$shenasahMilli."',
            codeEqtisadi ='".$codeEqtisadi."',
            codeNaqsh ='".$codeNaqsh."',
            address='".$address."',
            codePosti='".$codePosti."',
            email='".$email."',
            sabetPhoneNo='".$sabetPhoneNo."',
            phoneNo='".$phoneNo."',
            customerType='".$customerType."',
            customerShopSn='".Session::get("psn")."'
            where customerType='".$customerType."' and customerShopSn='".Session::get("psn")."'");
            $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
            $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",Session::get('psn'))->get();
            DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".Session::get("psn"));
            // return View('userProfile.profile', ['hoqoqiCustomers'=>$hoqoqiCustomers]);
            return redirect('profile');



// If the customer is not exist then add new customer
    }else{
            $companyName=$request->post("companyName");
            $registerNo=$request->post("registerNo");
            $shenasahMilli=$request->post("shenasahMilli");
            $codeEqtisadi=$request->post("codeEqtisadi");
            $codeNaqsh=$request->post("codeNaqsh");
            $address=$request->post("address");
            $codePosti=$request->post("codePosti");
            $email=$request->post("email");
            $phoneNo=$request->post("phoneNo");
            $sabetPhoneNo=$request->post("sabetPhoneNo");
            $customerType=$request->post("customerType");
            $customerShopSn=$request->post(Session::get("psn"));

        DB::table("NewStarfood.dbo.star_Customer")->insert([
            'companyName'=>"".$companyName."",
            'registerNo'=>"".$registerNo."",
            'shenasahMilli'=>"".$shenasahMilli."",
            'codeEqtisadi'=>"".$codeEqtisadi."",
            'codeNaqsh'=>"".$codeNaqsh."",
            'address'=>"".$address."",
            'codePosti'=>"".$codePosti."",
            'email'=>"".$email."",
            'phoneNo'=>"".$phoneNo."",
            'sabetPhoneNo'=>"".$sabetPhoneNo."",
            'customerType'=>"".$customerType."",
            'customerShopSn'=>''.Session::get("psn").''
            ]);
            $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",Session::get('psn'))->get();
            DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".Session::get("psn"));
        return redirect('profile');

    }

    }

    public function storeHoqoqiCustomerAdmin(Request $request){
        $customerShopSn=$request->post("customerShopSn");
        $id=$request->post("id");
        $checkExistance=DB::table("NewStarfood.dbo.star_Customer")->where('customerType', $request->post("customerType"))->where('customerShopSn', $id)->count('customerShopSn');

        // if the customer exist already then update the some fields
        if($checkExistance>0){

                $companyName=$request->post("companyName");
                $registerNo=$request->post("registerNo");
                $id=$request->post("id");
                $shenasahMilli=$request->post("shenasahMilli");
                $codeEqtisadi=$request->post("codeEqtisadi");
                $codeNaqsh=$request->post("codeNaqsh");
                $address=$request->post("address");
                $codePosti=$request->post("codePosti");
                $email=$request->post("email");
                $phoneNo=$request->post("phoneNo");
                $sabetPhoneNo=$request->post("sabetPhoneNo");
                $customerType=$request->post("customerType");
                $customerShopSn=$id;


            DB::update("UPDATE star_Customer SET
                companyName='".$companyName."',
                registerNo='".$registerNo."',
                shenasahMilli='".$shenasahMilli."',
                codeEqtisadi ='".$codeEqtisadi."',
                codeNaqsh ='".$codeNaqsh."',
                address='".$address."',
                codePosti='".$codePosti."',
                email='".$email."',
                sabetPhoneNo='".$sabetPhoneNo."',
                phoneNo='".$phoneNo."',
                customerType='".$customerType."',
                customerShopSn='".$id."'
                where customerType='".$customerType."' and customerShopSn='".$id."'");
                $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', $id)->select("*")->get();
                $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$id)->get();
                DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".$id);
                // return View('userProfile.profile', ['hoqoqiCustomers'=>$hoqoqiCustomers]);
                return redirect('/listCustomers');



    // If the customer is not exist then add new customer
        }else{
                $companyName=$request->post("companyName");
                $registerNo=$request->post("registerNo");
                $shenasahMilli=$request->post("shenasahMilli");
                $codeEqtisadi=$request->post("codeEqtisadi");
                $codeNaqsh=$request->post("codeNaqsh");
                $address=$request->post("address");
                $codePosti=$request->post("codePosti");
                $email=$request->post("email");
                $phoneNo=$request->post("phoneNo");
                $sabetPhoneNo=$request->post("sabetPhoneNo");
                $customerType=$request->post("customerType");
                $customerShopSn=$id;

            DB::table("NewStarfood.dbo.star_Customer")->insert([
                'companyName'=>"".$companyName."",
                'registerNo'=>"".$registerNo."",
                'shenasahMilli'=>"".$shenasahMilli."",
                'codeEqtisadi'=>"".$codeEqtisadi."",
                'codeNaqsh'=>"".$codeNaqsh."",
                'address'=>"".$address."",
                'codePosti'=>"".$codePosti."",
                'email'=>"".$email."",
                'phoneNo'=>"".$phoneNo."",
                'sabetPhoneNo'=>"".$sabetPhoneNo."",
                'customerType'=>"".$customerType."",
                'customerShopSn'=>''.$id.''
                ]);
                $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$id)->get();
                DB::update("UPDATE NewStarfood.dbo.star_customerRestriction set activeOfficialInfo=0 where customerId=".$id);
            return redirect('/customerList');

        }

        }



    public function regularLogin(Request $request){
        $customerId=$request->post("customerSn");
        $customers=DB::select("SELECT Peopels.PSN,Peopels.Name from Shop.dbo.Peopels
        where Peopels.CompanyNo=5 and peopels.GroupCode=290 and Peopels.PSN=".$customerId);
        $userName="";
        $psn="";
        foreach ($customers as $customer) {
            $userName=$customer->Name;
            $psn=$customer->PSN;
        }
        Session::put('username', $userName);
        Session::put('psn',$psn);
        return redirect("/home");
    }
    public function regularLogOut(Request $request)
    {
        $customerId=$request->post("customerSn");
        $customers=DB::select("SELECT Peopels.PSN,Peopels.Name from Shop.dbo.Peopels
        where Peopels.CompanyNo=5 and peopels.GroupCode=290 and Peopels.PSN=".$customerId);
        $userName="";
        $psn="";
        foreach ($customers as $customer) {
            $userName=$customer->Name;
            $psn=$customer->PSN;
        }
        Session::forget('username');
        Session::forget('psn');
        return redirect("/listCustomers");
    }

    public function customerDashboard(Request $request)
    {
        $psn=$request->get("csn");
        $adminId=Session::get('asn');
        $customers=DB::select("SELECT * from(
            SELECT * from(         
            SELECT COUNT(Shop.dbo.FactorHDS.SerialNoHDS)as countFactor,CustomerSn FROM Shop.dbo.FactorHDS where FactorHDS.FactType=3  group by CustomerSn)a
            right join (SELECT comment,customerId FROM CRM.dbo.crm_customerProperties)b on a.CustomerSn=b.customerId)c
            right join (SELECT PSN,Name,GroupCode,CompanyNo,peopeladdress FROM Shop.dbo.Peopels)f on c.customerId=f.PSN
            where f.CompanyNo=5 AND f.GroupCode IN (291,297,299,312,313,314) AND f.PSN=".$psn);
        foreach ($customers as $customer) {
            $sabit="";
            $hamrah="";
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                    $sabit.=$phone->PhoneStr."\n";
                }else{
                    $hamrah.=$phone->PhoneStr."\n"; 
                }
            }
            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;
        }
        $exactCustomer=$customers[0];
        $factors=DB::select("SELECT * FROM Shop.dbo.FactorHDS WHERE FactType=3 AND CustomerSn=".$psn." order by FactDate desc");
        $returnedFactors=DB::select("SELECT * FROM Shop.dbo.FactorHDS WHERE FactType=4 AND CustomerSn=".$psn);
        $GoodsDetail=DB::select("SELECT * FROM (SELECT MAX(TimeStamp)as maxTime,SnGood from(
            SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood FROM Shop.dbo.FactorHDS
            JOIN Shop.dbo.FactorBYS on FactorHDS.SerialNoHDS=FactorBYS.SnFact
            where FactorHDS.CustomerSn=".$psn.")g group by SnGood)c
            JOIN (SELECT * FROM Shop.dbo.PubGoods)d on d.GoodSn=c.SnGood");
        $basketOrders=DB::select("SELECT orderStar.TimeStamp,PubGoods.GoodName,orderStar.Amount,orderStar.Fi FROM newStarfood.dbo.FactorStar join newStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS
                                    join Shop.dbo.PubGoods on orderStar.SnGood=PubGoods.GoodSn  where orderStatus=0 and CustomerSn=".$psn);

        $loginInfo=DB::table("NewStarfood.dbo.star_customerTrack")->where("customerId",$psn)->get();
        return Response::json([$exactCustomer,$factors,$GoodsDetail,$basketOrders,$returnedFactors,$loginInfo]);
    }
    public function getFactorDetail(Request $request){
        $fsn=$request->get("FactorSn");
        $orders=DB::select("SELECT FactorBYS.Price AS goodPrice, *  FROM Shop.dbo.FactorHDS
                    JOIN Shop.dbo.FactorBYS ON FactorHDS.SerialNoHDS=FactorBYS.SnFact 
                    JOIN Shop.dbo.Peopels ON FactorHDS.CustomerSn=Peopels.PSN
                    JOIN Shop.dbo.PubGoods ON FactorBYS.SnGood=PubGoods.GoodSn 
                    JOIN Shop.dbo.PUBGoodUnits ON PUBGoodUnits.USN=PubGoods.DefaultUnit
                    where FactorHDS.SerialNoHDS=".$fsn);
        
        foreach ($orders as $order) {
            $sabit="";
            $hamrah="";
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$order->PSN)->get();
            foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                    $sabit.=$phone->PhoneStr."\n";
                }else{
                    $hamrah.=$phone->PhoneStr."\n"; 
                }
            }
            $order->sabit=$sabit;
            $order->hamrah=$hamrah;
        }
        return Response::json($orders);
    }


    }
