<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Response;
use Session;
use URL;

class Customer extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = DB::table("NewStarfood.dbo.star_Customer")->select("*")->get();
        // $exactCustomer;
        // // foreach ($customers as $customer) {
        // //     $exactCustomer = $customer;
        // // }
        //  print_r($customers); die;

        return view('customer.index', ['customers'=>$customers]);
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
        $returnedFactors=DB::select("SELECT * FROM Shop.dbo.FactorHDS WHERE FactType=4 AND CustomerSn=".$psn." order by FactDate desc");
        $GoodsDetail=DB::select("SELECT * FROM (SELECT MAX(TimeStamp)as maxTime,SnGood from(
            SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood FROM Shop.dbo.FactorHDS
            JOIN Shop.dbo.FactorBYS on FactorHDS.SerialNoHDS=FactorBYS.SnFact
            where FactorHDS.CustomerSn=".$psn.")g group by SnGood)c
            JOIN (SELECT * FROM Shop.dbo.PubGoods)d on d.GoodSn=c.SnGood order by mxTime desc");
        $basketOrders=DB::select("SELECT orderStar.TimeStamp,PubGoods.GoodName,orderStar.Amount,orderStar.Fi FROM newStarfood.dbo.FactorStar join newStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS
                                    join Shop.dbo.PubGoods on orderStar.SnGood=PubGoods.GoodSn  where orderStatus=0 and CustomerSn=".$psn);

        $loginInfo=DB::table("NewStarfood.dbo.star_customerTrack")->where("customerId",$psn)->get();
        return Response::json([$exactCustomer,$factors,$GoodsDetail,$basketOrders,$returnedFactors,$loginInfo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name            =         $request->post("name");
        $familyName      =         $request->post("familyName");
        $codeMilli       =          $request->post("codeMilli");
        $codeEqtisadi    =          $request->post("codeEqtisadi");
        $codeNaqsh       =         $request->post("codeNaqsh");
        $address         =         $request->post("address");
        $codePosti       =        $request->post("codePosti");
        $email           =          $request->post("email");
        $companyName     =          $request->post("companyName");
        $shenasahMilli   =           $request->post("shenasahMilli");
        $registerNo      =          $request->post("registerNo");
        $customerType    =          $request->post("customerType");

        DB::table("NewStarfood.dbo.star_Customer")->insert([
                    'name'=>"".$name."",
                    'familyName'     =>"".$familyName."",
                    'codeMilli'      =>"".$codeMilli."",
                    'codeEqtisadi'   =>"".$codeEqtisadi."",
                    'codeNaqsh'      =>"".$codeNaqsh."",
                    'address'        =>"".$address."",
                    'registerNo'     =>"".$registerNo."",
                    'codePosti'      =>"".$codePosti."",
                    'email'          =>"".$email."",
                    'companyName'    =>"".$companyName."",
                    'shenasahMilli'  =>"".$shenasahMilli."",
                    'customerType'   =>"".$customerType."",

                    ]);
        return redirect('customer')->with('flash_message', 'معلومات شما ذخیره شد');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
