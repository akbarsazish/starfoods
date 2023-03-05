<?php
namespace App\Http\Controllers;
use Omalizadeh\MultiPayment\Facades\PaymentGateway;
use Omalizadeh\MultiPayment\Invoice;
use Illuminate\Http\Request;
use DB;
use Session;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
use Response;
use DateTime;
use Pasargad\Pasargad;
use Pasargad\Classes\PaymentItem;
use App\Http\Controllers\StarfoodFunLib;



class Shipping extends Controller {
	public $amount;
	public $lastTransactionId;
    public function __construct()
    {
        $this->amount =20000;
        $this->invoiceNumber=111111;
    }
	
    public function setFactorSessions(Request $request)
    {
        Session::forget("allMoneyToSend");
		//set sessions of Factor before pay online
        Session::put("recivedTime",$request->get("recivedTime"));
        Session::put("takhfif",$request->get("takhfif"));
        Session::put("receviedAddress",$request->get("receviedAddress"));
        Session::put("allMoneyToSend",$request->get("allMoneyToSend"));
        Session::put("hasOrderSent",$request->get("isSent"));
        Session::put("orderSn",$request->get("orderSn"));
        return Response::json(Session::get("allMoneyToSend"));
	}
    public function starfoodFromPayment(Request $request)
    {
        $allMoney=(str_replace(',', '',Session::get('allMoneyToSend')))*10;
		try {
			
		$pasargad = new Pasargad(
		  "5015060",
		  "2263969",
		  "https://starfoods.ir/sucessPay",
		  "C:/inetpub/vhosts/starfoods.ir/httpdocs/key.xml");
		$pasargad->setAmount($allMoney); 
			$lastInvoiceNumber=DB::table("NewStarfood.dbo.star_paymentResponds")->max("InvoiceNumber");
			if($lastInvoiceNumber){
			$lastInvoiceNumber+=1;
			$this->invoiceNumber=$lastInvoiceNumber;
			}else{
				$this->invoiceNumber=111111;
			}
			

		$pasargad->setInvoiceNumber($this->invoiceNumber);

		$pasargad->setInvoiceDate("".Carbon::now()->format('Y/m/d H:i:s')."");
		$redirectUrl = $pasargad->redirect();

		return redirect($redirectUrl);

		} catch (\Exception $ex) {
			  return "اتصال با درگاه بانک بر قرار نشد";
		}
    }

    public function successPay(Request $request)
    {
        if(Session::get("hasOrderSent")==0){
        $allMoney=str_replace(',', '',Session::get('allMoneyToSend'))*10;
		try {
            $pasargad = new Pasargad(
                          "5015060",
                          "2263969",
                          "https://starfoods.ir/sucessPay",
                          "C:/inetpub/vhosts/starfoods.ir/httpdocs/key.xml");
            
            $pasargad->setTransactionReferenceId($request->get("tref")); 
            $pasargad->setInvoiceNumber($request->get("iN"));
            $pasargad->setInvoiceDate($request->get("iD"));
            $payResults=$pasargad->checkTransaction();
            if($payResults['IsSuccess']=="true"){
                
                $pasargad->setAmount($allMoney); 
                
                $pasargad->setInvoiceNumber($request->get("iN"));
                
                $pasargad->setInvoiceDate($request->get("iD"));
                
                if($pasargad->verifyPayment()["IsSuccess"]=='true'){
                    
                    // درج فاکتور از اینجا شروع می شود که در صورت موفقیت درج به صفحه موفقیت هدایت می شود   
                    
                    $takhfif=Session::get("takhfif")*10;
                    
                    list($pmOrAmSn,$orderDate)=explode(",",Session::get("recivedTime"));
                    
                    if($takhfif>0){
                        DB::update("UPDATE NewStarfood.dbo.star_takhfifHistory set isUsed=1 where customerId=".$customerSn);
                    }
    
            $orderDate=Jalalian::fromCarbon(Carbon::createFromFormat('Y-m-d H:i:s',$orderDate))->format("Y/m/d");
    //اطلاعات مربوط هزینه های اضافی فروش لیست می شود
            $totalCostNaql=0;
    
            $totalCostNasb=0;
    
            $totalCostMotafariqa=0;
    
            $totalCostBrgiri=0;
    
            $totalCostTarabari=0;
    
            $orderDescription=" ";
            $maxsOrderId=0;
            $lastOrderSnWeb=0;
            $maxsOrderIdWeb=0;
    
    //اطلاعاتی مربوط به پشتیبان وارد کننده و داشتن پشتیبان
            if(Session::get("otherUserInfo")){
                // $orderDescription.=Session::get("otherUserInfo");
                $orderDescription=' توسط:ادمین ثبت شد';
            }
            
            $poshtibanInfo=DB::select('SELECT CONCAT(convert(varchar,name),convert(varchar,lastName)) as nameLastName from 
                                        CRM.dbo.crm_admin join CRM.dbo.crm_customer_added on 
                                        crm_admin.id=crm_customer_added.admin_id where customer_id='.Session::get("psn").'
                                        and returnState=0');
            if(count($poshtibanInfo)>0){
                $poshtibanInformation=(string) $poshtibanInfo[0]->nameLastName;
            
                $orderDescription.=' '.$poshtibanInformation;
            }else{
                $orderDescription.="پشتیبان ندارد";
            }
           
            $orderDescription=(string)$orderDescription;
            
            // $recivedDate= Jalalian::fromCarbon(Carbon::parse($recivedDate))->format('Y/m/d');
            $customerSn=Session::get('psn');
            $recivedAddress=Session::get("receviedAddress");
            list($recivedAddress,$addressSn)=explode("_",Session::get("receviedAddress"));
            
            //گرفتن شماره سفارش سمت دفتر حساب برای ثبت شماره سفارش بعدی
            $factorNumber=DB::select("SELECT MAX(OrderNo) as maxFact from NewStarfood.dbo.OrderHDSS WHERE CompanyNo=5");
            $factorNo=0;
            $current = Carbon::today();
            $sabtTime = Carbon::now()->format("H:i:s");
            $todayDate = Jalalian::fromCarbon($current)->format('Y/m/d');
            foreach ($factorNumber as $number) {
                $factorNo=$number->maxFact;
            }
    
            $factorNo=$factorNo+1;
            //وارد فاکتور دفتر حساب می شود                                           
            // DB::insert("INSERT INTO Shop.dbo.OrderHDS (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder,OrderErsalTime,OrderSnAddress)
            // VALUES(5,".$factorNo.",'".$orderDate."',".$customerSn.",'$orderDescription',0,'','','".Session::get("FiscallYear")."',0,0,3,'".$recivedAddress."','',0,'','',0,0,0,0,0,'','','','',$takhfif,0,0,'',0,'".$sabtTime."',0,0,0,0,0,0,'','',0,0,0,0,0,'','',".$pmOrAmSn.",$addressSn)");
           
           //وارد فاکتور سفارشات وب می شود
           DB::insert("INSERT INTO NewStarfood.dbo.OrderHDSS (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder,OrderErsalTime,OrderSnAddress,isSent,isPayed,payedMoney,InVoiceNumber)
           VALUES(5,".$factorNo.",'".$orderDate."',".$customerSn.",'$orderDescription',0,'','','".Session::get("FiscallYear")."',0,0,3,'".$recivedAddress."','',0,'','',0,0,0,0,0,'','','','',$takhfif,0,0,'',0,'".$sabtTime."',0,0,0,0,0,0,'','',0,0,0,0,0,'','',".$pmOrAmSn.",$addressSn,0,1,$allMoney,".$request->get('iN').")");
           
    
           //برای ثبت آیتم های مربوط به سفارش شماره سفارش گرفته شود
            // $maxsOrders=DB::select("SELECT MAX(SnOrder) as maxOrderId from Shop.dbo.OrderHDS where CustomerSn=".$customerSn);
            
            // foreach ($maxsOrders as $maxOrder) {
            //     $maxsOrderId=$maxOrder->maxOrderId;
            // }
            //سر شاخه سبد خرید خوانده می شود.
            $lastOrdersStar=DB::select("SELECT MAX(SnOrder) as orderSn from NewStarfood.dbo.FactorStar WHERE CustomerSn=".$customerSn." and OrderStatus=0");
            $lastOrderSnStar=0;
            foreach ($lastOrdersStar as $lastOrder) {
                $lastOrderSnStar=$lastOrder->orderSn;
            }
    
            //آخرین فاکتور سفارش که در سمت وب داده شده
            $lastOrdersWeb=DB::select("SELECT MAX(SnOrder) as orderSn from NewStarfood.dbo.OrderHDSS WHERE CustomerSn=".$customerSn." and OrderStatus=0");
    
            foreach ($lastOrdersWeb as $lastOrder) {
                if($lastOrder->orderSn){
                    $lastOrderSnWeb=$lastOrder->orderSn;
                    $maxsOrderIdWeb=$lastOrder->orderSn;
                }
            }
    
            //آیتم های سبد خرید خوانده می شود
            $orederBYS=DB::select("SELECT * FROM NewStarfood.dbo.orderStar where SnHDS=".$lastOrderSnStar);
            $item=0;
            foreach ($orederBYS as $order) {
                // برای ثبت اطلاعات مربوط به هزینه انتقال و نصب و غیره
                $item=$item+1;
                $costExistance=DB::table("NewStarfood.dbo.star_orderAmelBYS")->where("SnOrder",$order->SnOrderBYS)->select("*")->get();
                if(count($costExistance)>0){
                    foreach ($costExistance as $cost) {
                        switch ($cost->SnAmel) {
                            case 142:
                                $totalCostNaql+=$cost->Price;
                                break;
                            case 143:
                                $totalCostNasb+=$cost->Price;
                                break;
                            case 144:
                                $totalCostMotafariqa+=$cost->Price;
                                break;
                            case 168:
                                $totalCostBrgiri+=$cost->Price;
                                break;
                            case 188:
                                $totalCostTarabari+=$cost->Price;
                                break;
                        }
                    }
                }     
            //سبد خرید وارد سفارشات دفتر حساب میشود.                                                      
                // DB::insert("INSERT INTO Shop.dbo.OrderBYS(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)
    
                // VALUES(5,".$maxsOrderId.",".$order->SnGood.",".$order->PackType.",".($order->Amount/$order->PackAmount).",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->PriceAfterTakhfif.",0,0,".$order->Price.",".$order->PriceAfterTakhfif.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
           
           //وارد جدول زیر شاخه فاکتور های سمت استارفود می شود.
                DB::insert("INSERT INTO NewStarfood.dbo.OrderBYSS(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)
    
                VALUES(5,".$lastOrderSnWeb.",".$order->SnGood.",".$order->PackType.",".($order->Amount/$order->PackAmount).",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->PriceAfterTakhfif.",0,0,".$order->Price.",".$order->PriceAfterTakhfif.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
    
            }
    
            
            //هزینه های فروش
            if($totalCostNaql>0){
                DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>142,'Price'=>$totalCostNaql,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
            }
            if($totalCostNasb>0){
                DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>143,'Price'=>$totalCostNasb,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
            }
            if($totalCostMotafariqa>0){
                DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>144,'Price'=>$totalCostMotafariqa,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
            }
            if($totalCostBrgiri>0){
                DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>168,'Price'=>$totalCostBrgiri,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
            }
            if($totalCostTarabari>0){
                DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>188,'Price'=>$totalCostTarabari,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
            }
            //سرشاخه سبد خرید سمت مشتری به عنوان تخلیه شده ثبت میشود.
            DB::update("update   NewStarfood.dbo.FactorStar set OrderStatus=1 WHERE  SnOrder=".$lastOrderSnStar);
            //تعداد کالاهای سبد خرید صفر می شود.
            Session::put('buy',0);
            //آیتم های سفارشی از دفتر حساب خوانده می شود که مربوط به این خرید بوده است.
            $factorBYS=DB::select("SELECT OrderBYSS.*,PubGoods.GoodName FROM NewStarfood.dbo.OrderBYSS join Shop.dbo.PubGoods on OrderBYSS.SnGood=PubGoods.GoodSn WHERE SnHDS=".$lastOrderSnWeb);
            $amountUnit=1;
            $defaultUnit;
            //اطلاعات مثل واحدات کالا و غیره برای صفحه موفقیت در اینجا بدست می آید.
            foreach ($factorBYS as $buy) {
                $kala=DB::select('SELECT PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName FROM Shop.dbo.PubGoods INNER JOIN Shop.dbo.PUBGoodUnits
                ON PubGoods.DefaultUnit=PUBGoodUnits.USN WHERE PubGoods.CompanyNo=5 AND PubGoods.GoodSn='.$buy->SnGood);
                foreach ($kala as $k) {
                $defaultUnit=$k->UName;
                $defaultUnit=$k->UName;
                }
                $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                    ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$buy->SnGood);
                if(count($subUnitStuff)>0){
                    foreach ($subUnitStuff as $stuff) {
                    $secondUnit=$stuff->secondUnit;
                    $amountUnit=$stuff->AmountUnit;
                    }
                }else{
                    $secondUnit=$defaultUnit;
                }
                $buy->firstUnit=$defaultUnit;
                $buy->secondUnit=$secondUnit;
                $buy->amountUnit=$amountUnit;
            }
    
            //  ختم موفق بودن خرید.
            //برای نمایش  واحد پول
            $currency=1;
            $currencyName="ریال";
            $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
            foreach ($currencyExistance as $cr) {
                $currency=$cr->currency;
            }
            if($currency==10){
                $currencyName="تومان";
            }
            $takhfif=Session::get("takhfif");
        //برای ثبت مشخصات واریزی در جدول جواب بانک
        $payResults=$pasargad->checkTransaction();		
        DB::table("NewStarfood.dbo.star_paymentResponds")->insert(["customerId"=>Session::get("psn")
           ,"TraceNumber"=>"".$payResults["TraceNumber"].""
           ,"ReferenceNumber"=>"".$payResults["ReferenceNumber"].""
           ,"TransactionDate"=>"".$payResults["TransactionDate"].""
           ,"TransactionReferenceID"=>"".$payResults["TransactionReferenceID"].""
           ,"InvoiceNumber"=>"".$payResults["InvoiceNumber"].""
           ,"InvoiceDate"=>"".$payResults["InvoiceDate"].""
           ,"Amount"=>"".$payResults["Amount"].""
           ,"TrxMaskedCardNumber"=>"".$payResults["TrxMaskedCardNumber"].""
           ,"IsSuccess"=>$payResults["IsSuccess"]
           ,"Message"=>"".$payResults["Message"].""]);
    
            return view("shipping.successPay",['factorNo'=>$factorNo,'factorBYS'=>$factorBYS,
                                           'profit'=>$profit,'currency'=>$currency,'currencyName'=>$currencyName,
                                           'payResults'=>$pasargad->checkTransaction()]);
                    
                }else{
                    
                    return redirect("/carts");
                    
                }
            }else{
                return redirect("/carts");
            }
            } catch (\Exception $ex) {
                  return redirect("/carts");
            }
        }else{
            //پرداخت طوری صورت بگیرد که سفارش ارسال شده ویرایش شود.
            $orderSn=Session::get("orderSn");
            try {
                $pasargad = new Pasargad(
                              "5015060",
                              "2263969",
                              "https://starfoods.ir/sucessPay",
                              "C:/inetpub/vhosts/starfoods.ir/httpdocs/key.xml");
                
                $pasargad->setTransactionReferenceId($request->get("tref")); 
                $pasargad->setInvoiceNumber($request->get("iN"));
                $pasargad->setInvoiceDate($request->get("iD"));
                $payResults=$pasargad->checkTransaction();
                if($payResults['IsSuccess']=="true"){
                    
                    $pasargad->setAmount($allMoney); 
                    
                    $pasargad->setInvoiceNumber($request->get("iN"));
                    
                    $pasargad->setInvoiceDate($request->get("iD"));
                    
                    if($pasargad->verifyPayment()["IsSuccess"]=='true'){

                        DB::table("NewStarfood.dbo.OrderHDSS")->where("CustomerSn",Session::get("psn"))->where("SnOrder",$orderSn)->update(["payedMoney"=>str_replace(',', '',(Session::get('allMoneyToSend'))*10)]);
                        return redirect("/profile");

                    }
                }
            } catch (\Exception $ex) {
                if(Session::get("")==1){
                return redirect("/carts");
                }else{
                    return redirect("/profile");
                }
          }
            
        }
            
	 
    }
    public function index(Request $request)
    {
        $customerId=Session::get('psn');
        $allMoney=$request->post('allMoney');
        $profit=$request->post('profit');
        $customers=DB::select('SELECT Peopels.* FROM Shop.dbo.Peopels WHERE PSN='.$customerId);
        $customer;
        foreach ($customers as $customer) {
            $customer=$customer;
        }
        $tomorrowDate= Carbon::now()->addDays(1);
        
        $tomorrow=$tomorrowDate->dayOfWeek;

        $afterTomorrowDate = Carbon::now();
        $twoDaysLater = $afterTomorrowDate->addDays(2);
        $afterTomorrow= $twoDaysLater->dayOfWeek;

        if($tomorrow==5){
            $tomorrowDate = $tomorrowDate->addDays(1);
            $twoDaysLater = $twoDaysLater->addDays(1);
        }
        $tomorrow=$tomorrowDate->dayOfWeek;
        $afterTomorrow= $twoDaysLater->dayOfWeek;

        if($afterTomorrow==5){
            $twoDaysLater = $twoDaysLater->addDays(1);
        }
        $afterTomorrow= $twoDaysLater->dayOfWeek;
        $day1;
        switch ($tomorrow) {
            case 6:
                $day1='شنبه';
                break;
            case 0:
                $day1='یکشنبه';
                break;
            case 1:
                $day1='دوشنبه';
                break;
            case 2:
                $day1='سه شنبه';
                break;
            case 3:
                $day1='چهار شنبه';
                break;
            case 4:
                $day1='پنج شنبه';
                break;
            case 5:
                $day1='جمعه';
                break;

            default:
                # code...
                break;
        }
        $day2;
        switch ($afterTomorrow) {
            case 6:
                $day2='شنبه';
                break;
            case 0:
                $day2='یکشنبه';
                break;
            case 1:
                $day2='دوشنبه';
                break;
            case 2:
                $day2='سه شنبه';
                break;
            case 3:
                $day2='چهار شنبه';
                break;
            case 4:
                $day2='پنج شنبه';
                break;
            case 5:
                $day2='جمعه';
                break;
            default:
                # code...
                break;
        }
        $customerRestrictions=DB::select("SELECT * FROM  NewStarfood.dbo.star_customerRestriction where customerId=".Session::get('psn'));
        $pardakhtLive=0;
        foreach ($customerRestrictions as $restrict) {
            $pardakhtLive=$restrict->pardakhtLive;
        }
        $webSpecialSettings=DB::table('NewStarfood.dbo.star_webSpecialSetting')->select("*")->get();
        $moorningActive=0;
        $afternoonActive=0;
        $settings=array();
        foreach ($webSpecialSettings as  $setting) {
            // $moorningActive=$settings->moorningActive;
            // $afternoonActive=$settings->afternoonActive;
            // $moorningContent=$settings->moorningTimeContent;
            // $afternoonContent=$settings->afternoonTimeContent;
            $settings=$setting;
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
        //برای محاسبه کیف تخفیفی
        $counDiscountWallet=new StarfoodFunLib;
        $allMoneyTakhfifResult=$counDiscountWallet->discountWalletCase();

        $addresses=DB::select("SELECT * FROM Shop.dbo.PeopelAddress where  SnPeopel=".Session::get('psn'));
        return view ('shipping.shipping',['date1'=>$day1,'date2'=>$day2,'customer'=>$customer,'takhfifCase'=>$allMoneyTakhfifResult
        ,'allMoney'=>$allMoney,'profit'=>$profit, 'tomorrowDate'=>$tomorrowDate,'afterTomorrowDate'=>$twoDaysLater
        ,'pardakhtLive'=>$pardakhtLive,'setting'=>$settings,'currency'=>$currency,'currencyName'=>$currencyName,
        'addresses'=>$addresses]);
    }
    public function addFactor(Request $request)
    {

        $lastOrderSnStar=0;
                // DB::beginTransaction();

        // try {
        $customerSn=Session::get('psn');

        $lastOrdersStar=DB::select("SELECT MAX(SnOrder) as orderSn from NewStarfood.dbo.FactorStar WHERE CustomerSn=".$customerSn." and OrderStatus=0");
        if($lastOrdersStar){
            //سفارش در سمت مشتری وجود داشته باشد.
        $pardakhtType=$request->post('pardakhtType');
        //تخفیف گنگ است
        list($pmOrAmSn,$orderDate)=explode(",",$request->post("recivedTime"));
        $orderDate=Jalalian::fromCarbon(Carbon::createFromFormat('Y-m-d H:i:s',$orderDate))->format("Y/m/d");
        $totalCostNaql=0;
        $totalCostNasb=0;
        $totalCostMotafariqa=0;
        $totalCostBrgiri=0;
        $totalCostTarabari=0;
        $orderDescription=" ";
        $maxsOrderId=0;
        $lastOrderSnWeb=0;
        $maxsOrderIdWeb=0;
        
        //اعلان ثبت مشخصات ورود جعلی و نمایش پشتیبان

        if(Session::get("otherUserInfo")){
            $orderDescription=' توسط:ادمین ثبت شد';
        }
        
		$poshtibanInfo=DB::select('SELECT CONCAT(convert(varchar,name),convert(varchar,lastName)) AS nameLastName FROM 
									CRM.dbo.crm_admin JOIN CRM.dbo.crm_customer_added ON 
									crm_admin.id=crm_customer_added.admin_id WHERE customer_id='.Session::get("psn").'
									and returnState=0');
        if(count($poshtibanInfo)>0){
            $poshtibanInformation=(string) $poshtibanInfo[0]->nameLastName;
			$orderDescription.=' '.$poshtibanInformation;
		}else{
			$orderDescription.="پشتیبان ندارد";
		}
       
        $orderDescription=(string)$orderDescription;
		//ختم ثبت مشخصات پشتیبان و جعلی

        $recivedAddress=$request->post('customerAddress');
        list($recivedAddress,$addressSn)=explode("_",$recivedAddress);
        $allMoney=$request->post('allMoney');
        // شماره فاکتور های سفارشی سمت دفتر حساب
        $factorNumber=DB::select("SELECT MAX(OrderNo) AS maxFact FROM NewStarfood.dbo.OrderHDSS WHERE CompanyNo=5");
        $factorNo=0;
        $current = Carbon::today();
        $sabtTime = Carbon::now()->format("H:i:s");
        $todayDate = Jalalian::fromCarbon($current)->format('Y/m/d');
        foreach ($factorNumber as $number) {
            $factorNo=$number->maxFact;
        }

        $factorNo=$factorNo+1;

        //آخرین سفارش فاکتور که در سمت وب داده شده
            
            foreach ($lastOrdersStar as $lastOrder) {
                $lastOrderSnStar=$lastOrder->orderSn;
            }

            //وارد فاکتور سفارشات دفتر حساب می شود                               
                    
            DB::insert("INSERT INTO Shop.dbo.OrderHDS (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder,OrderErsalTime,OrderSnAddress)
            VALUES(5,".$factorNo.",'".$orderDate."',".$customerSn.",'$orderDescription',0,'','','".Session::get("FiscallYear")."',0,0,3,'".$recivedAddress."','',0,'','',0,0,0,0,0,'','','','',0,0,0,'',0,'".$sabtTime."',0,0,0,0,0,0,'','',0,0,0,0,0,'','',".$pmOrAmSn.",$addressSn)");
            // در صورتیکه یک سفارش ارسال نشده در سمت سفارشات فروش قبلا موجود باشد.
            $orderExist=DB::table("NewStarfood.dbo.OrderHDSS")->where("CustomerSn",$customerSn)->where("isSent",0)->where("isDistroy",0)->get(); 
            if(count($orderExist)){
                 $lastOrderSnWeb=$orderExist[0]->SnOrder;
                 $maxsOrderIdWeb=$orderExist[0]->SnOrder;
            } 
            //آخرین فاکتور سفارش که در سمت دفتر حساب داده شده
            $lastOrders=DB::select("SELECT MAX(SnOrder) as orderSn from Shop.dbo.OrderHDS WHERE CustomerSn=".$customerSn." and OrderStatus=0");
            $lastOrderSn=0;
            foreach ($lastOrders as $lastOrder) {
                if($lastOrder->orderSn){
                    $lastOrderSn=$lastOrder->orderSn;
                    $maxsOrderId=$lastOrder->orderSn;
                }
            }
           
            if($lastOrderSnWeb<1 or $orderExist[0]->OrderSnAddress != $addressSn){//اگر سفارشی در لیست سفارشات از قبل وجود ندارد.

                DB::insert("INSERT INTO NewStarfood.dbo.OrderHDSS (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder,OrderErsalTime,OrderSnAddress)
                VALUES(5,".$factorNo.",'".$orderDate."',".$customerSn.",'$orderDescription',0,'','','".Session::get("FiscallYear")."',0,0,3,'".$recivedAddress."','',0,'','',0,0,0,0,0,'','','','',0,0,0,'',0,'".$sabtTime."',0,0,0,0,0,0,'','',0,0,0,0,0,'','',".$pmOrAmSn.",$addressSn)");
                      
                //آخرین فاکتور سفارش که در سمت وب داده شده
                $lastOrdersWeb=DB::select("SELECT MAX(SnOrder) as orderSn from NewStarfood.dbo.OrderHDSS WHERE CustomerSn=".$customerSn." and OrderStatus=0");
                foreach ($lastOrdersWeb as $lastOrder) {
                    if($lastOrder->orderSn){
                        $lastOrderSnWeb=$lastOrder->orderSn;
                        $maxsOrderIdWeb=$lastOrder->orderSn;
                    }
                }
                //  آیتم های سفارش را از سمت وب می خواند
                $orederBYS=DB::select("SELECT * FROM NewStarfood.dbo.orderStar where SnHDS=".$lastOrderSnStar);
                //
                foreach ($orederBYS as $order) {
                    $costExistance=DB::table("NewStarfood.dbo.star_orderAmelBYS")->where("SnOrder",$order->SnOrderBYS)->select("*")->get();
                    if(count($costExistance)>0){
                        foreach ($costExistance as $cost) {
                            switch ($cost->SnAmel) {
                                case 142:
                                    $totalCostNaql+=$cost->Price;
                                    break;
                                case 143:
                                    $totalCostNasb+=$cost->Price;
                                    break;
                                case 144:
                                    $totalCostMotafariqa+=$cost->Price;
                                    break;
                                case 168:
                                    $totalCostBrgiri+=$cost->Price;
                                    break;
                                case 188:
                                    $totalCostTarabari+=$cost->Price;
                                    break;
                            }
                        }
                    }    
                    //وارد جدول زیر شاخه فاکتور های سمت دفتر حساب می شود.
                    DB::insert("INSERT INTO Shop.dbo.OrderBYS(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                    VALUES(5,".$lastOrderSn.",".$order->SnGood.",".$order->PackType.",".($order->PackAmount).",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->PriceAfterTakhfif.",0,0,".$order->Price.",".$order->PriceAfterTakhfif.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                
                    //اگر کالاهای سفارش داده شده بود
                    //وارد جدول زیر شاخه فاکتور های سمت استارفود می شود.
                    DB::insert("INSERT INTO NewStarfood.dbo.OrderBYSS(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                    VALUES(5,".$lastOrderSnWeb.",".$order->SnGood.",".$order->PackType.",".($order->PackAmount).",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->Price.",0,0,".$order->Price.",".$order->Price.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                }
                
                if($totalCostNaql>0){
                    DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>142,'Price'=>$totalCostNaql,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
                }
                if($totalCostNasb>0){
                    DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>143,'Price'=>$totalCostNasb,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
                }
                if($totalCostMotafariqa>0){
                    DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>144,'Price'=>$totalCostMotafariqa,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
                }
                if($totalCostBrgiri>0){
                    DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>168,'Price'=>$totalCostBrgiri,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
                }
                if($totalCostTarabari>0){
                    DB::table('Shop.dbo.OrderAmelBYS')->insert(['CompanyNo'=>5,'SnOrder'=>$maxsOrderIdWeb,'SnAmel'=>188,'Price'=>$totalCostTarabari,'FiscalYear'=>Session::get("FiscallYear"),'DescItem'=>'','IsExport'=>'False']);
                }
            }else{
                $updateOrdeleteOrder=new StarfoodFunLib;
                $updateOrdeleteOrder->updateOrInsertOrders($lastOrderSnWeb,$lastOrderSnStar);

                //وارد کردن داده ها به جدول دفتر حساب
                //  آیتم های سفارش را از سمت وب می خواند
                $orederBYS=DB::select("SELECT * FROM NewStarfood.dbo.orderStar where SnHDS=".$lastOrderSnStar);
                foreach ($orederBYS as $order) {
                //وارد جدول زیر شاخه فاکتور های سمت دفتر حساب می شود.
                    DB::insert("INSERT INTO Shop.dbo.OrderBYS(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4)

                    VALUES(5,".$lastOrderSn.",".$order->SnGood.",".$order->PackType.",".($order->PackAmount).",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->PriceAfterTakhfif.",0,0,".$order->Price.",".$order->PriceAfterTakhfif.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0)");
                }
            }




            DB::update("UPDATE   NewStarfood.dbo.FactorStar SET OrderStatus=1 WHERE  SnOrder=".$lastOrderSnStar);
            Session::put('buy',0);

            $factorBYS=DB::select("SELECT orderStar.*,PubGoods.GoodName FROM NewStarfood.dbo.orderStar join Shop.dbo.PubGoods on orderStar.SnGood=PubGoods.GoodSn WHERE SnHDS=".$lastOrderSnStar);
            $amountUnit=1;
            $defaultUnit;
            foreach ($factorBYS as $buy) {
                $kala=DB::select('select PubGoods.GoodName,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods inner join Shop.dbo.PUBGoodUnits
                on PubGoods.DefaultUnit=PUBGoodUnits.USN where PubGoods.CompanyNo=5 and PubGoods.GoodSn='.$buy->SnGood);
                foreach ($kala as $k) {
                $defaultUnit=$k->UName;
                $defaultUnit=$k->UName;
                }
                $subUnitStuff= DB::select("SELECT GoodUnitSecond.AmountUnit,PUBGoodUnits.UName AS secondUnit FROM Shop.dbo.GoodUnitSecond JOIN Shop.dbo.PUBGoodUnits
                                    ON GoodUnitSecond.SnGoodUnit=PUBGoodUnits.USN WHERE GoodUnitSecond.SnGood=".$buy->SnGood);
                if(count($subUnitStuff)>0){
                    foreach ($subUnitStuff as $stuff) {
                    $secondUnit=$stuff->secondUnit;
                    $amountUnit=$stuff->AmountUnit;
                    }
                }else{
                    $secondUnit=$defaultUnit;
                }
                $buy->firstUnit=$defaultUnit;
                $buy->secondUnit=$secondUnit;
                $buy->amountUnit=$amountUnit;
            }

            //used because of پیش خرید
            $lastOrdersStarPishKharids=DB::select("SELECT MAX(SnOrderPishKharid) as orderSn from   NewStarfood.dbo.star_pishKharidFactor WHERE CustomerSn=".$customerSn." and OrderStatus=0");
            $lastOrderSnStarPishKharid=0;
            foreach ($lastOrdersStarPishKharids as $lastOrder) {
                if($lastOrder->orderSn){
                $lastOrderSnStarPishKharid=$lastOrder->orderSn;
                }
            }
            $pishKharidOrderAfters=DB::select("SELECT * FROM   NewStarfood.dbo.star_pishKharidOrder where SnHDS=".$lastOrderSnStarPishKharid);
            if(count($pishKharidOrderAfters)>0){

                $factorNumberPishKharid=DB::select("SELECT MAX(OrderNo) as maxFact from  NewStarfood.dbo.star_pishKharidFactorAfter WHERE CompanyNo=5");
                $factorNoPishKharid=0;
                $current = Carbon::today();
                $todayDate = Jalalian::fromCarbon($current)->format('Y/m/d');
                foreach ($factorNumberPishKharid as $number) {
                    $factorNoPishKharid=$number->maxFact;
                }

                $factorNoPishKharid=$factorNoPishKharid+1;

                DB::insert("INSERT INTO NewStarfood.dbo.star_pishKharidFactorAfter (CompanyNo,OrderNo,OrderDate,CustomerSn,OrderDesc,OrderStatus,LastDatePay,LastDateTrue,FiscalYear,BazarYab,CountPrintOrder,SnUser,OrderAddress,OtherCustName,TahvilType,TahvilDesc,RanandehName,MashinNo,BarNameNo,IsExport,SnOrderHDSRecived,OrderOrPishFactor,OtherMobile,OtherTel,DateNovbat,TimeNovbat,Takhfif,OrderNo2,SnOrder2,NextOrderDate,LastOrderCust,SaveTimeOrder,LatOrder,LonOrder,Sal_SnCust,Sal_SnBazaryab,IsFax,FaxUser,FaxDate,FaxTime,PayTypeOrder,SnHDSTablet_O,SnSellerTablet_O,EzafatOrder,KosoratOrder,NameEzafatOrder,NameKosoratOrder)
                VALUES(5,".$factorNoPishKharid.",'".$todayDate."',".$customerSn.",'',0,'','','".Session::get("FiscallYear")."',0,0,3,'','',0,'','',0,0,0,0,0,'','','".$orderDate."','',0,0,0,'',0,'',0,0,0,0,0,0,'','',0,0,0,0,0,'','')");


                $lastOrderSnPishKharid=DB::table("NewStarfood.dbo.star_pishKharidFactorAfter")->max("SnOrderPishKharidAfter");
                $item=0;
                foreach ($pishKharidOrderAfters as $order) {
                    $item=$item+1;
                    DB::insert("INSERT INTO  NewStarfood.dbo.star_pishKharidOrderAfter(CompanyNo,SnHDS,SnGood,PackType,PackAmount,Amount,Fi,Price,DescRecord,StatusBys,DateOrder,SnUser,FactorFew,ExportType,SendToKarkhaneh,FiPack,IsExport,SnOrderDetailRecived,OrderTo,GoodName2,JozePack,SaleType,PriceMaliyat,PercentTakhFif,PriceTakhfif,PriceAfterTakhfif,PercentReval,PriceReval,RealFi,RealPrice,Price3PercentMaliat,PercentSood,Tedad,Tol,Arz,Zekhamat,SnOrderBys2,SnOrderHds2,OrderNo2,OrderDate2,SnBazaryab2,FewExit,TimeTasviyeInOrder,ItemNo,TakhfifDetail1,TakhfifDetail2,TakhfifDetail3,TakhfifDetail4,PriceTakhfifDetail1,PriceTakhfifDetail2,PriceTakhfifDetail3,PriceTakhfifDetail4,FiAfterTakhfifDetail1,FiAfterTakhfifDetail2,FiAfterTakhfifDetail3,FiAfterTakhfifDetail4,preBuyState)
                    VALUES(5,".$lastOrderSnPishKharid.",".$order->SnGood.",".$order->PackType.",".$order->PackAmount.",".$order->Amount.",".$order->Fi.",".$order->Price.",'',0,'".$todayDate."',12,0,0,0,".$order->FiPack.",0,0,0,'',0,0,0,0,0,".$order->PriceAfterTakhfif.",0,0,".$order->Price.",".$order->PriceAfterTakhfif.",0,0,0,0,0,0,0,0,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0)");
                }
                DB::update("update  NewStarfood.dbo.star_pishKharidFactor set OrderStatus=1 WHERE  SnOrderPishKharid=".$lastOrderSnStarPishKharid);
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
            $profit=$request->post("profit");
            return view('success.success',['factorNo'=>$factorNo,'factorBYS'=>$factorBYS,'profit'=>$profit,'currency'=>$currency,'currencyName'=>$currencyName]);
        
        }else{
            return redirect("/allGroups");
        }

    //     DB::commit();
    //     // all good
    // } catch (\Exception $e) {
    //     DB::rollback();
    //     // something went wrong
    // }
    }


    public function wallet()
    {
    $counDiscountWallet=new StarfoodFunLib;
    $allMoneyTakhfifResult=$counDiscountWallet->discountWalletCalc();
    $nazars=DB::select("SELECT * FROM NewStarfood.dbo.star_nazarsanji join NewStarfood.dbo.star_question on star_nazarsanji.id=star_question.nazarId where nazarId=(select max(id) from NewStarfood.dbo.star_nazarsanji)");
    return view('shipping.wallet',['moneyTakhfif'=>$allMoneyTakhfifResult,'nazars'=>$nazars]);
    }
    public function starfoodStar()
    {
                //برای محاسبه کیف تخفیفی
                $counDiscountWallet=new StarfoodFunLib;
                $bonusResult=$counDiscountWallet->customerBonusCalc();
                //ختم محسبه امتیازات مشتری
                return view('shipping.starfoodStar',['monyComTg'=>$bonusResult[0],
                'aghlamComTg'=>$bonusResult[1],'monyComTgBonus'=>$bonusResult[2],
                'aghlamComTgBonus'=>$bonusResult[3],'lotteryMinBonus'=>$bonusResult[4],'allBonus'=>$bonusResult[5]]);
    }
    public function addMoneyToCase(Request $request)
    {
        
        $customerSn=Session::get("psn");
        $takhfif=$request->get("takhfif");
        $personalPercentTakhfif=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$customerSn)->get()[0]->percentTakhfif;
        if($personalPercentTakhfif){
            $takhfifPercent=$personalPercentTakhfif;
        }else{
            $takhfifPercent= DB::table("NewStarfood.dbo.star_webSpecialSetting")->get()[0]->percentTakhfif;
        }
        $answer1=$request->get("answer1");
        $answer2=$request->get("answer2");
        $answer3=$request->get("answer3");
        $nazarId=$request->get("nazarId");
        DB::table("NewStarfood.dbo.star_answers")->insert([
            "answer1"=>"".$answer1.""
        ,"answer2"=>"".$answer2.""
        ,"answer3"=>"".$answer3.""
        ,"customerId"=>$customerSn
        ,"nazarId"=>$nazarId
        ]);
        DB::table("NewStarfood.dbo.star_takhfifHistory")->insert(['customerId'=>$customerSn
                            ,'money'=>$takhfif
                            ,'discription'=>""
                            ,'changeDate'=>"".Jalalian::fromCarbon(Carbon::now())->format("Y/m/d").""
                            ,'lastPercent'=>$takhfifPercent
                            ,'isUsed'=>0]);
        return redirect("/home");
    }
}
