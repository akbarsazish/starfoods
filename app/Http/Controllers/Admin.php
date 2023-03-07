<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Response;
use Session;
use Notification;
use App\Notifications\AlertNotification;

class Admin extends Controller{
    public function index(Request $request)
    {
        $contNewMessage=DB::select("SELECT COUNT(id) as countMessage FROM NewStarfood.dbo.star_message where readState=0");
        $countMessage;
        foreach ($contNewMessage as $countMessage) {
            $countMessage=$countMessage->countMessage;
        }
        Session::put('countMessage');
        return view('admin.dashboard');
    }
    public function listKarbaran(Request $request){
        $admins=DB::select("SELECT * FROM NewStarfood.dbo.admin");
        return view('admin.listKarbaran',['admins'=>$admins]);
    }
    public function listCustomers(Request $request) {
        $withoutRestrictions=DB::select("SELECT * FROM Shop.dbo.Peopels WHERE Peopels.PSN NOT IN(SELECT customerId FROM NewStarfood.dbo.star_customerRestriction) AND CompanyNo=5 AND GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).")");
        if(count($withoutRestrictions)>0){
            foreach ($withoutRestrictions as $customer) {
            DB::insert("INSERT INTO NewStarfood.dbo.star_customerRestriction(pardakhtLive,minimumFactorPrice,exitButtonAllowance,manyMobile,customerId,forceExit,activeOfficialInfo)
                     VALUES(1,0,0,1,".$customer->PSN.",0,0)");
            }
        }
        $withoutPassword=DB::select("SELECT distinct Peopels.PSN,Peopels.Name,Peopels.PeopelEghtesadiCode from Shop.dbo.Peopels
        where Peopels.PSN not in(SELECT customerId FROM NewStarfood.dbo.star_CustomerPass  where customerId is not null) and Peopels.CompanyNo=5 and peopels.GroupCode IN ( 291,297,299,312,313,314) and Name!=''");
        
        if(count($withoutPassword)>0){
            foreach ($withoutPassword as $customer) {
                $phones=DB::select("SELECT PhoneStr FROM Shop.dbo.PhoneDetail where SnPeopel=".$customer->PSN);
                $hamrah=0;
                $sabit=0;
                foreach ($phones as $phone) {
                    if($phone->PhoneType=1){
                        $sabit=$phone->PhoneStr;
                        $customer->PhoneStr=$sabit;
                        break;
                    }else{
                        $hamrah=$phone->PhoneStr;
                        $customer->PhoneStr=$hamrah;
                        break;
                    }
                }
            }

            foreach ($withoutPassword as $customer) {
                    if(isset($customer->PhoneStr)){
                        $pass =substr($customer->PhoneStr, -4);
                        DB::insert("INSERT INTO NewStarfood.dbo.star_CustomerPass(customerId,customerPss,userName)VALUES(".$customer->PSN.",'".$pass."','".$customer->PhoneStr."')");
                    }
            }
        }

        $customers=DB::select("SELECT PSN,PCode,Name,GroupCode,FORMAT(TimeStamp,'yyyy/MM/dd','fa-ir') as TimeStamp,peopeladdress,CRM.dbo.getCustomerPhoneNumbers(PSN) as PhoneStr,CRm.dbo.getCustomerMantagheh(SnMantagheh) as NameRec  FROM Shop.dbo.Peopels
            
        WHERE CompanyNo=5 AND Name !='' AND GroupCode in(291,1297,1297 ,299 ,312 ,313 ,314) and IsActive=1 and not exists(select * from CRM.dbo.crm_inactiveCustomer where state=1 and customerId=PSN) ORDER BY PSN ASC");
        

        $regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and FatherMNM=80");
        $cities=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and Rectype=1 and FatherMNM=79");
        // کیوری اشخاص رسمی 
         $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->select("*")->get();
         $hohoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->select("*")->get();

        return view('admin.listCustomers',['customers'=>$customers,'regions'=>$regions,'cities'=>$cities, 'haqiqiCustomers'=>$haqiqiCustomers, 'hohoqiCustomers'=>$hohoqiCustomers]);
    }



    public function testSpeed(Request $request)
    {
        $customers=DB::select("SELECT * from (
                        SELECT * FROM(
                        SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                        ) a
                        LEFT JOIN (select NameRec,SnMNM from Shop.dbo.MNM)d ON a.SnMantagheh=d.SnMNM)e
                        WHERE e.CompanyNo=5 AND Name !='' AND GroupCode =291 or GroupCode =1297 or GroupCode =1297 or GroupCode =299 or GroupCode =312 or GroupCode =313 ORDER BY PSN ASC");

foreach ($customers as $customer) {

$phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
$hamrah="";
$sabit="";

foreach ($phones as $phone) {

if($phone->PhoneType==1){
    $sabit.="\n".$phone->PhoneStr;
}else{
    $hamrah.="\n".$phone->PhoneStr;
}

}

$customer->sabit=$sabit;
$customer->hamrah=$hamrah;
}

$regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and FatherMNM=80");

$cities=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and Rectype=1 and FatherMNM=79");

return ['customers'=>$customers,'regions'=>$regions,'cities'=>$cities];
    }
    public function searchMantagha(Request $request)
    {
        $cityId=$request->get('cityId');
         $regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and FatherMNM=".$cityId);
        return Response::json($regions);
    }
    public function searchByMantagha(Request $request)
    {
        $monTaghaSn=$request->get("msn");
        if($monTaghaSn!=0){
        $customers=DB::select("SELECT * from(
                        SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                        ) a
                        LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                        WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=".$monTaghaSn." order by PSN asc");
        foreach ($customers as $customer) {
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            $hamrah="";
            $sabit="";
            foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                    $sabit.="\n".$phone->PhoneStr;
                }else{
                    $hamrah.="\n".$phone->PhoneStr;
                }
            }
            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;
        }
            return Response::json($customers);}
            else{
                $customers=DB::select("SELECT * FROM(
                                SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                                ) a
                                LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                                WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=0 order by PSN asc");
                foreach ($customers as $customer) {
                    $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
                    $hamrah="";
                    $sabit="";
                    foreach ($phones as $phone) {
                        if($phone->PhoneType==1){
                            $sabit.="\n".$phone->PhoneStr;
                        }else{
                            $hamrah.="\n".$phone->PhoneStr;
                        }
                    }
                    $customer->sabit=$sabit;
                    $customer->hamrah=$hamrah;
                }
                    return Response::json($customers);
            }
    }
	
public function searchByCity(Request $request)
    {
        $citySn=$request->get("csn");
        if($citySn!=0){
        $customers=DB::select("SELECT *,CRM.dbo.getCustomerPhoneNumbers(PSN) as PhoneStr from(
                        SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnNahiyeh,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                        ) a
                        LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                        WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) and SnNahiyeh=".$citySn." order by PSN asc");

            return Response::json($customers);}
            else{
                $customers=DB::select("SELECT *,,CRM.dbo.getCustomerPhoneNumbers(PSN) as PhoneStr FROM(
                                SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.SnNahiyeh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                                ) a
                                LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=Shop.dbo.MNM.SnMNM
                                WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) and SnNahiyeh=0 order by PSN asc");
                
                    return Response::json($customers);
            }
    }

    public function searchCustomerByName(Request $request)
    {
        $searchTerm=$request->get("searchTerm");

        $customers=DB::select("SELECT * FROM(
                        SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                        ) a
                        LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                        WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) AND Name LIKE '%".$searchTerm."%' order by PSN asc");
        
        foreach ($customers as $customer) {
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            $hamrah="";
            $sabit="";
            foreach ($phones as $phone) {

                if($phone->PhoneType==1){
                    $sabit.="\n".$phone->PhoneStr;
                }else{
                    $hamrah.="\n".$phone->PhoneStr;
                }

            }
            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;
        }

        return Response::json($customers);
    }
    public function searchCustomerByCode(Request $request)
    {
        $searchTerm=$request->get("searchTerm");

        $customers=DB::select("SELECT * FROM(
                        SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                        ) a
                        LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                        WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) AND PCode LIKE '%".$searchTerm."%' order by PSN asc");
        
        foreach ($customers as $customer) {
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            $hamrah="";
            $sabit="";
            foreach ($phones as $phone) {

                if($phone->PhoneType==1){
                    $sabit.="\n".$phone->PhoneStr;
                }else{
                    $hamrah.="\n".$phone->PhoneStr;
                }

            }
            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;
        }

        return Response::json($customers);
    }

    public function searchCustomerByActivation(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        if($searchTerm==1){
            $customers=DB::select("SELECT * FROM(
                            SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                            ) a
                            LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                            WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) and PSN not in(
                            select customerId from NewStarfood.dbo.star_customerRestriction where forceExit=1)");

            foreach ($customers as $customer) {
                $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
                $hamrah="";
                $sabit="";
                foreach ($phones as $phone) {

                    if($phone->PhoneType==1){
                        $sabit.="\n".$phone->PhoneStr;
                    }else{
                        $hamrah.="\n".$phone->PhoneStr;
                    }

                }
                $customer->sabit=$sabit;
                $customer->hamrah=$hamrah;
            }
        }else{
            $customers=DB::select("SELECT * FROM(
                SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                ) a
                LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) and PSN in(
                select customerId from NewStarfood.dbo.star_customerRestriction where forceExit=1)");

            foreach ($customers as $customer) {
                $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
                $hamrah="";
                $sabit="";
                foreach ($phones as $phone) {

                    if($phone->PhoneType==1){
                        $sabit.="\n".$phone->PhoneStr;
                    }else{
                        $hamrah.="\n".$phone->PhoneStr;
                    }

                }
                $customer->sabit=$sabit;
                $customer->hamrah=$hamrah;
            }

        }

        return Response::json($customers);
    }
    public function searchCustomerLocationOrNot(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        if($searchTerm==1){
            $customers=DB::select("SELECT * FROM(
                SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo,LatPers,LonPers FROM Shop.dbo.Peopels
                ) a
                LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) and LatPers>0 and LonPers>0");

            foreach ($customers as $customer) {
                $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
                $hamrah="";
                $sabit="";
                foreach ($phones as $phone) {

                    if($phone->PhoneType==1){
                        $sabit.="\n".$phone->PhoneStr;
                    }else{
                        $hamrah.="\n".$phone->PhoneStr;
                    }

                }
                $customer->sabit=$sabit;
                $customer->hamrah=$hamrah;
            }
        }else{
            $customers=DB::select("SELECT * FROM(
                SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo,LatPers,LonPers FROM Shop.dbo.Peopels
                ) a
                LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) and LatPers<1 and LonPers<1");

            foreach ($customers as $customer) {
                $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
                $hamrah="";
                $sabit="";
                foreach ($phones as $phone) {

                    if($phone->PhoneType==1){
                        $sabit.="\n".$phone->PhoneStr;
                    }else{
                        $hamrah.="\n".$phone->PhoneStr;
                    }

                }
                $customer->sabit=$sabit;
                $customer->hamrah=$hamrah;
            }

        }
        return Response::json($customers);        
    }

    public function orderCustomers(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        if($searchTerm==0){
            $customers=DB::select("SELECT * FROM(
                SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo,LatPers,LonPers FROM Shop.dbo.Peopels
                ) a
                LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) ORDER BY PCode ASC");

            foreach ($customers as $customer) {
                $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
                $hamrah="";
                $sabit="";
                foreach ($phones as $phone) {

                    if($phone->PhoneType==1){
                        $sabit.="\n".$phone->PhoneStr;
                    }else{
                        $hamrah.="\n".$phone->PhoneStr;
                    }

                }
                $customer->sabit=$sabit;
                $customer->hamrah=$hamrah;
            }
        }else{
            $customers=DB::select("SELECT * FROM(
                SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo,LatPers,LonPers FROM Shop.dbo.Peopels
                ) a
                LEFT JOIN Shop.dbo.MNM ON a.SnMantagheh=MNM.SnMNM
                WHERE a.CompanyNo=5 AND Name !='' AND GroupCode IN (291,297,299,312,313,314) ORDER BY Name ASC");

            foreach ($customers as $customer) {
                $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
                $hamrah="";
                $sabit="";
                foreach ($phones as $phone) {

                    if($phone->PhoneType==1){
                        $sabit.="\n".$phone->PhoneStr;
                    }else{
                        $hamrah.="\n".$phone->PhoneStr;
                    }

                }
                $customer->sabit=$sabit;
                $customer->hamrah=$hamrah;
            }

        }
        return Response::json($customers); 
    }

    public function controlMainPage(Request $request){
        $parts=DB::select('select * from NewStarfood.dbo.HomePart ORDER BY priority ASC');
         $specialSettings=DB::select("SELECT * FROM NewStarfood.dbo.star_webSpecialSetting");
        $settings=[];
        foreach ($specialSettings as $setting) {
            $settings=$setting;
        }
        $stocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks where SnStock!=0 and NameStock!='' and CompanyNo=5");
        $addedStocks=DB::table("NewStarfood.dbo.addedStocks")->join("Shop.dbo.Stocks","addedStocks.stockId","=","SnStock")->select("*")->get();
        $regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and SnMNM>82 and FatherMNM=80");
        $cities=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and Rectype=1 and FatherMNM=79");

        // کیوری مربوط به تنظیمات امتیازها 
        $targets=DB::table("NewStarfood.dbo.star_customer_baseBonus")->get();
        $lotteryPrizes=DB::table("NewStarfood.dbo.star_lotteryPrizes")->get();
        $nazarSanjies=DB::table("NewStarfood.dbo.star_nazarsanji")->join("NewStarfood.dbo.star_question","nazarId","=","star_nazarsanji.id")->get();
        $lotteryMinBonus=DB::select("SELECT * FROM NewStarfood.dbo.star_webSpecialSetting")[0]->lotteryMinBonus;
        
        return view('admin.controlMainPage',['parts'=>$parts, 'settings'=>$settings,'stocks'=>$stocks,'addedStocks'=>$addedStocks,'regions'=>$regions,'cities'=>$cities, 
    'targets'=>$targets,'prizes'=>$lotteryPrizes,'nazars'=>$nazarSanjies,'lotteryMinBonus'=>$lotteryMinBonus]);

    }


    public function addNewGroup(Request $request) {
        return view('admin.addGroup');
    }
    

    public function AddAdmin(Request $request)
    {
        return view('admin.addAdmin');
    }



    public function doAddAdmin(Request $request){

        $username=$request->post('userName');

        $password=$request->post('password');

        $name=$request->post("name");

        $lastname=$request->post("lastName");

        $adminType=$request->post("AdminTypeN");

        $sex=$request->post("gender");

        // اگر اطلاعات پایه روشن بود
        $baseInfoN = $request->post("baseInfoN");
        $settingsN;
        $mainPageSetting;
        $specialSettingN;
        $emptyazSettingN;

         if($baseInfoN=="on"){
            $baseInfoN = 1;

            $settingsN = $request->post("settingsN");
            if($settingsN = "on"){
                $settingsN = 1;
                    
                //تنظیمات صفحه اصلی با سه عنصر اش چک گردد
                    $mainPageSetting = $request->post("mainPageSetting");
                    $deletMainPageSettingN = $request->post("deletMainPageSettingN");
                    $editManiPageSettingN = $request->post("editManiPageSettingN");
                    $seeMainPageSettingN = $request->post("seeMainPageSettingN");

                    if($mainPageSetting=="on"){
                        if($deletMainPageSettingN=="on"){
                            $mainPageSetting=2;
                        }elseif($editManiPageSettingN=="on" and $deletMainPageSettingN!="on"){
                            $mainPageSetting=1;
                        }elseif($editManiPageSettingN !="on" and $seeMainPageSettingN =="on"){
                            $mainPageSetting=0;
                        }else{
                            $mainPageSetting=-1;
                        }
                    }else{
                        $mainPageSetting=-1;
                    }

                //تنظیمات اختصاصی با سه عنصر اش چک گردد
                    $specialSettingN = $request->post("specialSettingN");
                    $deleteSpecialSettingN = $request->post("deleteSpecialSettingN");
                    $editSpecialSettingN = $request->post("editSpecialSettingN");
                    $seeSpecialSettingN = $request->post("seeSpecialSettingN");

                    if($specialSettingN=="on"){
                        if($deleteSpecialSettingN=="on"){
                            $specialSettingN=2;
                        }elseif($editSpecialSettingN=="on" and $deleteSpecialSettingN!="on"){
                            $specialSettingN=1;
                        }elseif($editSpecialSettingN !="on" and $seeSpecialSettingN =="on"){
                            $specialSettingN=0;
                        }else{
                            $specialSettingN=-1;
                        }
                    }else{
                        $specialSettingN=-1;
                    }

                //تنظیمات امتیاز با سه عنصر اش چک گردد
                    $emptyazSettingN = $request->post("emptyazSettingN");
                    $deleteEmtyazSettingN = $request->post("deleteEmtyazSettingN");
                    $editEmptyazSettingN = $request->post("editEmptyazSettingN");
                    $seeEmtyazSettingN = $request->post("seeEmtyazSettingN");

                    if($emptyazSettingN=="on"){
                        if($deleteEmtyazSettingN=="on"){
                            $emptyazSettingN=2;
                        }elseif($editEmptyazSettingN=="on" and $deleteEmtyazSettingN!="on"){
                            $emptyazSettingN=1;
                        }elseif($editEmptyazSettingN !="on" and $seeEmtyazSettingN =="on"){
                            $emptyazSettingN=0;
                        }else{
                            $emptyazSettingN=-1;
                        }
                    }else{
                        $emptyazSettingN=-1;
                    }

                }else{
                    $settingsN = -1;
                    $mainPageSetting = -1;
                    $specialSettingN = -1;
                    $emptyazSettingN = -1;
                }

            }else{
                $baseInfoN = -1;
                $settingsN = -1;
                $mainPageSetting = -1;
                $specialSettingN = -1;
                $emptyazSettingN = -1;
            } 
            // ختم اطلاعات پایه 


        // اگر تعریف عناصر روشن بود 
        $defineElementN = $request->post("defineElementN");
        $defineElementN;
        $karbaranN;
        $customersN;
        if($defineElementN = "on"){

             $karbaranN = $request->post("karbaranN");
                if($karbaranN="on"){
                    
                    $customersN = $request->post("customersN");
                    $deleteCustomersN = $request->post("deleteCustomersN");
                    $editCustomerN = $request->post("editCustomerN");
                    $seeCustomersN = $request->post("seeCustomersN");

                        if($customersN=="on"){
                                if($deleteCustomersN="on"){
                                    $customersN=2;
                                }elseif($editCustomerN="on" &&  $deleteCustomersN!="on"){
                                    $customersN=1;    
                                }elseif($seeCustomersN="on" && $editCustomerN!="on"){
                                    $customersN=0;
                                }else{
                                    $customersN=-1;
                                }
                        }else {
                            $customersN=-1;
                        }
                   
                }else{
                  $karbaranN = -1;
                  $customersN=-1;
                }
        }else{
            $defineElementN = -1;
            $karbaranN = -1;
            $customersN=-1;
        }



        // اگر عملیات روشن بود 
        $operationN = $request->post("operationN");
        $operationN;
        $kalasN;
        $kalaListsN;
        $requestedKalaN;
        $fastKalaN;
        $pishKharidN;
        $brandsN;
        $alertedN;
        $kalaGroupN;
        $orderSalesN;
        $messageN;

        if($operationN="on"){
                $kalasN = $request->port("kalasN");
                if($kalasN="on"){
                    // چک کردن لیست کالا ها با سه تا عناصر اش
                        $kalaListsN=$request->post("kalaListsN");
                        $deleteKalaListN=$request->post("deleteKalaListN");
                        $editKalaListN=$request->post("editKalaListN");
                        $seeKalaListN=$request->post("seeKalaListN");

                        if($kalaListsN=="on"){
                                if($deleteKalaListN="on"){
                                    $kalaListsN=2;
                                }elseif($editKalaListN=="on" && $deleteKalaListN!="on"){
                                    $kalaListsN=1;
                                }elseif($seeKalaListN="on" && $editKalaListN!="on"){
                                    $kalaListsN=0;
                                }else{
                                    $kalaListsN=-1;
                                }

                        }else{
                            $kalaListsN=-1;
                        }

                        // چک کردن کالا های درخواستی با سه تا عناصر اش
                        $requestedKalaN=$request->post("requestedKalaN");
                        $deleteRequestedKalaN=$request->post("deleteRequestedKalaN");
                        $editRequestedKalaN=$request->post("editRequestedKalaN");
                        $seeRequestedKalaN=$request->post("seeRequestedKalaN");

                        if($requestedKalaN=="on"){
                                if($deleteRequestedKalaN="on"){
                                    $requestedKalaN=2;
                                }elseif($editRequestedKalaN=="on" && $deleteRequestedKalaN!="on"){
                                    $requestedKalaN=1;
                                }elseif($seeRequestedKalaN="on" && $editRequestedKalaN!="on"){
                                    $requestedKalaN=0;
                                }else{
                                    $requestedKalaN=-1;
                                }

                        }else{
                            $requestedKalaN=-1;
                        }

                     // چک کردن فست کالا با سه تا عناصر اش
                        $fastKalaN=$request->post("fastKalaN");
                        $deleteFastKalaN=$request->post("deleteFastKalaN");
                        $editFastKalaN=$request->post("editFastKalaN");
                        $seeFastKalaN=$request->post("seeFastKalaN");

                        if($fastKalaN=="on"){
                                if($deleteFastKalaN="on"){
                                    $fastKalaN=2;
                                }elseif($editFastKalaN=="on" && $deleteFastKalaN!="on"){
                                    $fastKalaN=1;
                                }elseif($seeFastKalaN="on" && $editFastKalaN!="on"){
                                    $fastKalaN=0;
                                }else{
                                    $fastKalaN=-1;
                                }

                        }else{
                            $fastKalaN=-1;
                        }


                    // چک کردن پیش خرید با سه تا عناصر اش
                        $pishKharidN=$request->post("fastKalaN");
                        $deletePishKharidN=$request->post("deletePishKharidN");
                        $editPishkharidN=$request->post("editPishkharidN");
                        $seePishKharidN=$request->post("seePishKharidN");

                        if($pishKharidN=="on"){
                                if($deletePishKharidN="on"){
                                    $pishKharidN=2;
                                }elseif($editPishkharidN=="on" && $deletePishKharidN!="on"){
                                    $pishKharidN=1;
                                }elseif($seePishKharidN="on" && $editPishkharidN!="on"){
                                    $pishKharidN=0;
                                }else{
                                    $pishKharidN=-1;
                                }

                        }else{
                            $pishKharidN=-1;
                        }

                         // چک کردن  برند ها با سه تا عناصر اش
                        $brandsN=$request->post("fastKalaN");
                        $deleteBrandsN=$request->post("deleteBrandsN");
                        $editBrandN=$request->post("editBrandN");
                        $seeBrandsN=$request->post("seeBrandsN");

                        if($brandsN=="on"){
                                if($deleteBrandsN="on"){
                                    $brandsN=2;
                                }elseif($editBrandN=="on" && $deleteBrandsN!="on"){
                                    $brandsN=1;
                                }elseif($seeBrandsN="on" && $editBrandN!="on"){
                                    $brandsN=0;
                                }else{
                                    $brandsN=-1;
                                }

                        }else{
                            $brandsN=-1;
                        }


                     // چک کردن کالاهای شامل هشدار با سه تا عناصر اش
                        $alertedN=$request->post("fastKalaN");
                        $deleteAlertedN=$request->post("deleteAlertedN");
                        $editAlertedN=$request->post("editAlertedN");
                        $seeAlertedN=$request->post("seeAlertedN");

                        if($alertedN=="on"){
                                if($deleteAlertedN="on"){
                                    $alertedN=2;
                                }elseif($editAlertedN=="on" && $deleteAlertedN!="on"){
                                    $alertedN=1;
                                }elseif($seeAlertedN="on" && $editAlertedN!="on"){
                                    $alertedN=0;
                                }else{
                                    $alertedN=-1;
                                }

                        }else{
                            $alertedN=-1;
                        }


                     // چک کردن  دسته بندی کالاها با سه تا عناصر اش
                        $kalaGroupN=$request->post("kalaGroupN");
                        $deletKalaGroupN=$request->post("deletKalaGroupN");
                        $editKalaGroupN=$request->post("editKalaGroupN");
                        $seeKalaGroupN=$request->post("seeKalaGroupN");
                        if($kalaGroupN=="on"){
                                if($deletKalaGroupN="on"){
                                    $kalaGroupN=2;
                                }elseif($editKalaGroupN=="on" && $deletKalaGroupN!="on"){
                                    $kalaGroupN=1;
                                }elseif($seeKalaGroupN="on" && $editKalaGroupN!="on"){
                                    $kalaGroupN=0;
                                }else{
                                    $kalaGroupN=-1;
                                }

                        }else{
                            $kalaGroupN=-1;
                        }

                     // چک کردن  سفارشات فروش با سه تا عناصر اش
                        $orderSalesN=$request->post("kalaGroupN");
                        $deleteOrderSalesN=$request->post("deleteOrderSalesN");
                        $editOrderSalesN=$request->post("editOrderSalesN");
                        $seeSalesOrderN=$request->post("seeSalesOrderN");
                        if($orderSalesN=="on"){
                                if($deleteOrderSalesN="on"){
                                    $orderSalesN=2;
                                }elseif($editOrderSalesN=="on" && $deleteOrderSalesN!="on"){
                                    $orderSalesN=1;
                                }elseif($seeSalesOrderN="on" && $editOrderSalesN!="on"){
                                    $orderSalesN=0;
                                }else{
                                    $orderSalesN=-1;
                                }

                        }else{
                            $orderSalesN=-1;
                        }

                     // چک کردن پیام ها با سه تا عناصر اش
                        $messageN=$request->post("kalaGroupN");
                        $deleteMessageN=$request->post("deleteMessageN");
                        $editMessageN=$request->post("editMessageN");
                        $seeMessageN=$request->post("seeMessageN");
                        if($messageN=="on"){
                                if($deleteMessageN="on"){
                                    $messageN=2;
                                }elseif($editOrderSalesN=="on" && $deleteMessageN!="on"){
                                    $messageN=1;
                                }elseif($seeMessageN="on" && $editOrderSalesN!="on"){
                                    $messageN=0;
                                }else{
                                    $messageN=-1;
                                }

                        }else{
                            $messageN=-1;
                        }


                }else{
                    $kalasN=-1;
                    $kalaListsN=-1;
                    $requestedKalaN=-1;
                    $fastKalaN=-1;
                    $pishKharidN=-1;
                    $brandsN=-1;
                    $alertedN=-1;
                    $orderSalesN=-1;
                    $messageN=-1;
                }

        }else{
            $operationN =-1;
            $operationN=-1;
            $kalasN=-1;
            $kalaListsN=-1;
            $requestedKalaN=-1;
            $fastKalaN=-1;
            $pishKharidN=-1;
            $brandsN=-1;
            $alertedN=-1;
            $kalaGroupN=-1;
            $orderSalesN=-1;
            $messageN=-1;
        }


        // اگر گزارشات روشن بود 
        $reportN = $request->post("reportN");
        $reportCustomerN;
        $cutomerListN;
        $officialCustomerN;
        $gameAndLotteryN;
        $lotteryResultN;
        $gamerListN;
        $onlinePaymentN;

        if($reportN=="on") {
            // اگر مشتریان روشن بود 
            $reportCustomerN = $request->post();
            if($reportCustomerN=="on"){

                // لیست مشتریان با سه تا عناصرش چک گردد 
                    $cutomerListN=$request->post("kalaGroupN");
                    $deletCustomerListN=$request->post("deletCustomerListN");
                    $editCustomerListN=$request->post("editCustomerListN");
                    $seeCustomerListN=$request->post("seeCustomerListN");
                    if($cutomerListN=="on"){
                            if($deletCustomerListN="on"){
                                $cutomerListN=2;
                            }elseif($editCustomerListN=="on" && $deletCustomerListN!="on"){
                                $cutomerListN=1;
                            }elseif($seeCustomerListN="on" && $editCustomerListN!="on"){
                                $cutomerListN=0;
                            }else{
                                $cutomerListN=-1;
                            }

                    }else{
                        $cutomerListN=-1;
                    }

                // لیست مشتریان با سه تا عناصرش چک گردد 
                    $officialCustomerN=$request->post("kalaGroupN");
                    $deleteOfficialCustomerN=$request->post("deleteOfficialCustomerN");
                    $editOfficialCustomerN=$request->post("editOfficialCustomerN");
                    $seeOfficialCustomerN=$request->post("seeOfficialCustomerN");
                    if($officialCustomerN=="on"){
                        if($deleteOfficialCustomerN="on"){
                            $officialCustomerN=2;
                        }elseif($editOfficialCustomerN=="on" && $deleteOfficialCustomerN!="on"){
                            $officialCustomerN=1;
                        }elseif($seeOfficialCustomerN="on" && $editOfficialCustomerN!="on"){
                            $officialCustomerN=0;
                        }else{
                            $officialCustomerN=-1;
                        }
                    }else{
                        $officialCustomerN=-1;
                    }

            }else{
                $reportCustomerN=-1;
            }


            // اگر گیم و لاتری روشن بود 

             $gameAndLotteryN = $request->post("gameAndLotteryN");
             if($gameAndLotteryN="on"){

                  // نتجه لاتری با سه تا عناصرش چک گردد 
                    $lotteryResultN=$request->post("kalaGroupN");
                    $deletLotteryResultN=$request->post("deletLotteryResultN");
                    $editLotteryResultN=$request->post("editLotteryResultN");
                    $seeLotteryResultN=$request->post("seeLotteryResultN");
                    if($lotteryResultN=="on"){
                            if($deletLotteryResultN="on"){
                                $lotteryResultN=2;
                            }elseif($editLotteryResultN=="on" && $deletLotteryResultN!="on"){
                                $lotteryResultN=1;
                            }elseif($seeLotteryResultN="on" && $editLotteryResultN!="on"){
                                $lotteryResultN=0;
                            }else{
                                $lotteryResultN=-1;
                            }

                    }else{
                        $lotteryResultN=-1;
                    }

                  // لیست گیمر ها با سه تا عناصرش چک گردد
                    $gamerListN=$request->post("kalaGroupN");
                    $deletGamerListN=$request->post("deletGamerListN");
                    $editGamerListN=$request->post("editGamerListN");
                    $seeGamerListN=$request->post("seeGamerListN");
                    if($gamerListN=="on"){
                            if($deletGamerListN="on"){
                                $gamerListN=2;
                            }elseif($editGamerListN=="on" && $deletGamerListN!="on"){
                                $gamerListN=1;
                            }elseif($seeGamerListN="on" && $editGamerListN!="on"){
                                $gamerListN=0;
                            }else{
                                $gamerListN=-1;
                            }
                    }else{
                        $gamerListN=-1;
                    }

             }else{
                $gameAndLotteryN=-1;
             }

            // اگر پرداخت انلاین روشن بود 
                $onlinePaymentN = $request->post("onlinePaymentN");
                $deleteOnlinePaymentN=$request->post("deleteOnlinePaymentN");
                $editOnlinePaymentN=$request->post("editOnlinePaymentN");
                $seeOnlinePaymentN=$request->post("seeOnlinePaymentN");
                if($onlinePaymentN=="on"){
                        if($deleteOnlinePaymentN="on"){
                            $onlinePaymentN=2;
                        }elseif($editOnlinePaymentN=="on" && $deleteOnlinePaymentN!="on"){
                            $onlinePaymentN=1;
                        }elseif($seeOnlinePaymentN="on" && $editOnlinePaymentN!="on"){
                            $onlinePaymentN=0;
                        }else{
                            $onlinePaymentN=-1;
                        }

                }else{
                    $onlinePaymentN=-1;
                }

        }else{
            $reportN = -1;
            $reportCustomerN= -1;
            $cutomerListN= -1;
            $officialCustomerN= -1;
            $gameAndLotteryN= -1;
            $lotteryResultN= -1;
            $gamerListN= -1;
            $onlinePaymentN= -1;
        }

   

        DB::insert("INSERT INTO NewStarfood.dbo.admin (name,lastName,userName,password,activeState,sex,address,adminType)

        VALUES('".$name."','".$lastname."','".$username."','".$password."',1,'$sex','','$adminType')");
        
        $lastId=DB::table("NewStarfood.dbo.admin")->max('id');

        DB::table("NewStarfood.dbo.star_hasAccess")->insert(
        ['adminId'=>$lastId
        ,'homePage'=>$homePage
        ,'karbaran'=>$karbaran
        ,'specialSetting'=>$specialSetting
        ,'kalaList'=>$kalaList
        ,'kalaRequests'=>$kalaRequests
        ,'fastKala'=>$fastKala
        ,'pishKharid'=>$pishKharid
        ,'brand'=>$brand
        ,'alertedKala'=>$alerted
        ,'listGroups'=>$listGroups
        ,'customers'=>$customers
        ,'officials'=>$officials
        ,'messages'=>$messages]);

        $admins=DB::select("SELECT * FROM NewStarfood.dbo.admin");

        return redirect("/listKarbaran");
    }



    public function restrictAdmin(Request $request)
    {
        $id=$request->post('adminId');
        $admins=DB::select("SELECT * FROM NewStarfood.dbo.admin");
        return view('admin.restrictAdmin',['admin'=>$admins]);
    }
    public function getAdminInfo(Request $request){
        $adminId=$request->get("searchTerm");
        $adminInfo=DB::select("SELECT * from NewStarfood.dbo.admin left join NewStarfood.dbo.star_hasAccess on admin.id=star_hasAccess.adminId where admin.id=".$adminId)[0];
    return Response::json($adminInfo);
    }


    public function editAdmin(Request $request){
        $adminId=$request->post("adminId");
        $admin=DB::select("SELECT * FROM NewStarfood.dbo.admin where id=".$adminId);
        foreach ($admin as $ad) {
            $admin=$ad;
        }
        return view('admin.editAdmin',['admin'=>$admin]);
    }
    public function doEditAdmin(Request $request)
    {
        $adminId=$request->post("adminId");
        $activeState=$request->post("activeState");
        if($activeState){
            $activeState=1;
        }else{
            $activeState=0;
        }
        //refactor codes
        $userName=$request->post('userName');

        $password=$request->post('password');

        $name=$request->post("name");

        $lastName=$request->post("lastName");

        $adminType=$request->post("adminType");

        $sex=$request->post("gender");

        DB::update("UPDATE NewStarfood.dbo.admin set name='".$name."',lastName='".$lastName."',userName='".$userName."',password='".$password."',activeState=".$activeState.",adminType='".$adminType."',sex='".$sex."' where id=".$adminId);
        $homePageDelete=$request->post("homeDelete");
        $homePageEdit=$request->post("changeHomePage");
        $homePageSee=$request->post("seeHomePage");

        $homePage=0;

        if($homePageDelete=="on"){

            $homePage=2;

        }elseif($homePageEdit=="on" and $homePageDelete!="on"){

            $homePage=1;

        }elseif($homePageEdit !="on" and $homePageSee =="on"){

            $homePage=0;

        }else{

            $homePage=-1;

        }

        $karbaranDelete=$request->post("karbaranDelete");
        $karbaranEdit=$request->post("changeKarbaran");
        $karbaranSee=$request->post("seeKarbaran");

        $karbaran=0;

        if($karbaranDelete=="on"){
            $karbaran=2;
        }elseif($karbaranEdit=="on" and $karbaranDelete!="on"){
            $karbaran=1;
        }elseif($karbaranEdit !="on" and $karbaranSee =="on"){
            $karbaran=0;
        }else{
            $karbaran=-1;
        }

        $specialSettingDelete=$request->post("specialDelete");
        $specialSettingEdit=$request->post("changeSpecialSetting");
        $specialSettingSee=$request->post("seeSpecialSetting");

        $specialSetting=0;

        if($specialSettingDelete=="on"){
            $specialSetting=2;
        }elseif($specialSettingEdit=="on" and $specialSettingDelete!="on"){
            $specialSetting=1;
        }elseif($specialSettingEdit !="on" and $specialSettingSee =="on"){
            $specialSetting=0;
        }else{
            $specialSetting=-1;
        }

        $kalaListDelete=$request->post("deleteKalaList");
        $kalaListEdit=$request->post("changeKalaList");
        $kalaListSee=$request->post("seeKalaList");

        $kalaList=0;

        if($kalaListDelete=="on"){
            $kalaList=2;
        }elseif($kalaListEdit=="on" and $kalaListDelete!="on"){
            $kalaList=1;
        }elseif($kalaListEdit !="on" and $kalaListSee =="on"){
            $kalaList=0;
        }else{
            $kalaList=-1;
        }

        $kalaRequestsDelete=$request->post("deleteRequestedKala");
        $kalaRequestsEdit=$request->post("changeRequestedKala");
        $kalaRequestsSee=$request->post("seeRequestedKala");

        $kalaRequests=0;

        if($kalaRequestsDelete=="on"){
            $kalaRequests=2;
        }elseif($kalaRequestsEdit=="on" and $kalaRequestsDelete!="on"){
            $kalaRequests=1;
        }elseif($kalaRequestsEdit !="on" and $kalaRequestsSee =="on"){
            $kalaRequests=0;
        }else{
            $kalaRequests=-1;
        }

        $fastKalaDelete=$request->post("deleteFastKala");
        $fastKalaEdit=$request->post("changeFastKala");
        $fastKalaSee=$request->post("seeFastKala");

        $fastKala=0;

        if($fastKalaDelete=="on"){
            $fastKala=2;
        }elseif($fastKalaEdit=="on" and $fastKalaDelete!="on"){
            $fastKala=1;
        }elseif($fastKalaEdit !="on" and $fastKalaSee =="on"){
            $fastKala=0;
        }else{
            $fastKala=-1;
        }

        $pishKharidDelete=$request->post("deletePishKharid");
        $pishKharidEdit=$request->post("changePishKharid");
        $pishKharidSee=$request->post("seePishKharid");

        $pishKharid=0;

        if($pishKharidDelete=="on"){
            $pishKharid=2;
        }elseif($pishKharidEdit=="on" and $pishKharidDelete!="on"){
            $pishKharid=1;
        }elseif($pishKharidEdit !="on" and $pishKharidSee =="on"){
            $pishKharid=0;
        }else{
            $pishKharid=-1;
        }

        $brandDelete=$request->post("deleteBrands");
        $brandEdit=$request->post("changeBrands");
        $brandSee=$request->post("seeBrands");

        $brand=0;

        if($brandDelete=="on"){
            $brand=2;
        }elseif($brandEdit=="on" and $brandDelete!="on"){
            $brand=1;
        }elseif($brandEdit !="on" and $brandSee =="on"){
            $brand=0;
        }else{
            $brand=-1;
        }

        $alertedKalaDelete=$request->post("deleteAlerted");
        $alertedKalaEdit=$request->post("changeAlerted");
        $alertedKalaSee=$request->post("seeAlerted");

        $alerted=0;

        if($alertedKalaDelete=="on"){
            $alerted=2;
        }elseif($alertedKalaEdit=="on" and $alertedKalaDelete!="on"){
            $alerted=1;
        }elseif($alertedKalaEdit !="on" and $alertedKalaSee =="on"){
            $alerted=0;
        }else{
            $alerted=-1;
        }

        $listGroupsDelete=$request->post("deleteGroupList");
        $listGroupsEdit=$request->post("changeGroupList");
        $listGroupsSee=$request->post("seeGroupList");

        $listGroups=0;

        if($listGroupsDelete=="on"){
            $listGroups=2;
        }elseif($listGroupsEdit=="on" and $listGroupsDelete!="on"){
            $listGroups=1;
        }elseif($listGroupsEdit !="on" and $listGroupsSee =="on"){
            $listGroups=0;
        }else{
            $listGroups=-1;
        }

        $customersDelete=$request->post("deleteCustomers");
        $customersEdit=$request->post("changeCustomers");
        $customersSee=$request->post("seeCustomers");

        $customers=0;

        if($customersDelete=="on"){
            $customers=2;
        }elseif($customersEdit=="on" and $customersDelete!="on"){
            $customers=1;
        }elseif($customersEdit !="on" and $customersSee =="on"){
            $customers=0;
        }else{
            $customers=-1;
        }

        $officialsDelete=$request->post("deleteOfficials");
        $officialsEdit=$request->post("changeOfficials");
        $officialsSee=$request->post("seeOfficials");

        $officials=0;

        if($officialsDelete=="on"){
            $officials=2;
        }elseif($officialsEdit=="on" and $officialsDelete!="on"){
            $officials=1;
        }elseif($officialsEdit !="on" and $officialsSee =="on"){
            $officials=0;
        }else{
            $officials=-1;
        }

        $messagesDelete=$request->post("deleteMessages");
        $messagesEdit=$request->post("changeMessages");
        $messagesSee=$request->post("seeMessages");

        $messages=0;

        if($messagesDelete=="on"){
            $messages=2;
        }elseif($messagesEdit=="on" and $messagesDelete!="on"){
            $messages=1;
        }elseif($messagesEdit !="on" and $messagesSee =="on"){
            $messages=0;
        }else{
            $messages=-1;
        }

        DB::table("NewStarfood.dbo.star_hasAccess")->where("adminId",$adminId)->update(
        ['homePage'=>$homePage
        ,'karbaran'=>$karbaran
        ,'specialSetting'=>$specialSetting
        ,'kalaList'=>$kalaList
        ,'kalaRequests'=>$kalaRequests
        ,'fastKala'=>$fastKala
        ,'pishKharid'=>$pishKharid
        ,'brand'=>$brand
        ,'alertedKala'=>$alerted
        ,'listGroups'=>$listGroups
        ,'customers'=>$customers
        ,'officials'=>$officials
        ,'messages'=>$messages]);

        $admins=DB::select("SELECT * FROM NewStarfood.dbo.admin");

        return redirect("/listKarbaran");
    }
    public function webSpecialSettings(Request $request)
    {
        $specialSettings=DB::select("SELECT * FROM NewStarfood.dbo.star_webSpecialSetting");
        $settings=[];
        foreach ($specialSettings as $setting) {
            $settings=$setting;
        }
        $stocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks where SnStock!=0 and NameStock!='' and CompanyNo=5");
        $addedStocks=DB::table("NewStarfood.dbo.addedStocks")->join("Shop.dbo.Stocks","addedStocks.stockId","=","SnStock")->select("*")->get();
        $regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and SnMNM>82 and FatherMNM=80");
        $cities=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and Rectype=1 and FatherMNM=79");
        return view('admin.controlMainPage',['settings'=>$settings,'stocks'=>$stocks,'addedStocks'=>$addedStocks,'regions'=>$regions,'cities'=>$cities]);
    }
    public function takhsisMasirs(Request $request)
    {
        $cityId=$request->get("cityId");
        $regionId=$request->get("regionId");
        $csn=$request->get("csn");
        DB::table("Shop.dbo.Peopels")->where("PSN",$csn)->update(["SnNahiyeh"=>$cityId,"SnMantagheh"=>$regionId,"SnMasir"=>79]);
        $customers=DB::select("select * from(
            SELECT Peopels.PCode,peopels.Name,peopels.PSN,peopels.peopeladdress,Peopels.GroupCode,Peopels.SnMantagheh,Peopels.CompanyNo FROM Shop.dbo.Peopels
                    ) a
                    left join Shop.dbo.MNM on a.SnMantagheh=MNM.SnMNM
                    WHERE a.CompanyNo=5 AND GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") order by PSN asc");
        foreach ($customers as $customer) {
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            $hamrah="";
            $sabit="";
            foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                    $sabit.="\n".$phone->PhoneStr;
                }else{
                    $hamrah.="\n".$phone->PhoneStr;
                }
            }
            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;
        }
        return Response::json($customers);
    }

    public function addMasir(Request $request)
    {
        $mantiqahId=$request->get("mantiqahId");
        $name=$request->get("name");
         DB::table("Shop.dbo.MNM")->insert(['CompanyNo'=>5,'RecType'=>2,'NameRec'=>"".$name."",'FatherMNM'=>$mantiqahId,'SnSellerN'=>0,'Add_Update'=>0 ,'IsExport'=>0,'SnSellerN2'=>0]);
        return Response::json(1);
    }
    public function getMasirs(Request $request)
    {
        $mantiqahId=$request->get("mantiqahId");
        $masirs=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and FatherMNM=".$mantiqahId);
        return Response::json($masirs);
    }

    public function doUpdatewebSpecialSettings(Request $request)
    {
            $maxSale=$request->post("maxSale");
            $minSalePriceFactor=str_replace(",", "",$request->post("minSalePriceFactor"));
           // $lotteryMinBonus=str_replace(",", "",$request->post("lotteryMinBonus"));
            $moorningTimeContent=$request->post("moorningTimeContent");
            $afternoonTimeContent=$request->post("afternoonTimeContent");
            $whatsappNumber=$request->post("whatsappNumber");
            $instagramId=$request->post("instagramId");
            $telegramId=$request->post("telegramId");
            $firstDayMoorningActive=$request->post("firstDayMoorningActive");
            $firstDayAfternoonActive=$request->post("firstDayAfternoonActive");
            $secondDayMoorningActive=$request->post("secondDayMoorningActive");
            $secondDayAfternoonActive=$request->post("secondDayAfternoonActive");
            $favoriteDateMoorningActive=$request->post("favoriteDateMoorningActive");
            $favoriteDateAfternoonActive=$request->post("favoriteDateAfternoonActive");
            $buyFromHome=$request->post("buyFromHome");
            $enamad=$request->post("enamad");
			$logoPosition=$request->post("logoPosition");
            $selectHome=$request->post("selectHome");
            $fiscallYear=$request->post("fiscallYear");
            $currency=$request->post("currency");
            $percentTakhfif=str_replace("/", ".",$request->post("percentTakhfif"));
            $apk=$request->file("uploadAPK");

            $firstPrize=str_replace(",", "",$request->post("firstPrize"));
            $secondPrize=str_replace(",", "",$request->post("secondPrize"));
            $thirdPrize=str_replace(",", "",$request->post("thirdPrize"));
            $fourthPrize=str_replace(",", "",$request->post("fourthPrize"));
            $fifthPrize=str_replace(",", "",$request->post("fifthPrize"));
            $sixthPrize=str_replace(",", "",$request->post("sixthPrize"));
            $seventhPrize=str_replace(",", "",$request->post("seventhPrize"));
            $eightthPrize=str_replace(",", "",$request->post("eightthPrize"));
            $ninthPrize=str_replace(",", "",$request->post("ninthPrize"));
            $teenthPrize=str_replace(",", "",$request->post("teenthPrize"));


            if($apk){
                $fileName=$apk->getClientOriginalName();
            list($a,$b)=explode(".",$fileName);
            $fileName="starfood."."apk";
            $apk->move("resources/assets/apks/",$fileName);
            }
            if($buyFromHome){
                $buyFromHome=1;
            }else{
                $buyFromHome=0;
            }
            if($enamad){
                $enamad=1;
            }else{
                $enamad=0;
            }
            if($firstDayMoorningActive){
                $firstDayMoorningActive=1;
            }else{
                $firstDayMoorningActive=0;
            }
            if($firstDayAfternoonActive){
                $firstDayAfternoonActive=1;
            }else{
                $firstDayAfternoonActive=0;
            }
            if($secondDayMoorningActive){
                $secondDayMoorningActive=1;
            }else{
                $secondDayMoorningActive=0;
            }
            if($secondDayAfternoonActive){
                $secondDayAfternoonActive=1;
            }else{
                $secondDayAfternoonActive=0;
            }
            if($favoriteDateMoorningActive){
                $favoriteDateMoorningActive=1;
            }else{
                $favoriteDateMoorningActive=0;
            }
            if($favoriteDateAfternoonActive){
                $favoriteDateAfternoonActive=1;
            }else{
                $favoriteDateAfternoonActive=0;
            }
            DB::update("UPDATE NewStarfood.dbo.star_webSpecialSetting SET maxSale=".$maxSale.",
            minSalePriceFactor=".$minSalePriceFactor.",moorningTimeContent='".$moorningTimeContent."',
            afternoonTimeContent='".$afternoonTimeContent."',whatsappNumber='".$whatsappNumber."',
            instagramId='".$instagramId."',telegramId='".$telegramId."',
            firstDayMoorningActive=".$firstDayMoorningActive.",firstDayAfternoonActive=".$firstDayAfternoonActive.",
            secondDayMoorningActive=".$secondDayMoorningActive.",secondDayAfternoonActive=".$secondDayAfternoonActive.",
            FavoriteDateMoorningActive=".$favoriteDateMoorningActive.",FavoriteDateAfternoonActive=".$favoriteDateAfternoonActive.",
            defaultStock=23,currency=".$currency.",buyFromHome=".$buyFromHome.",homePage=".$selectHome.",FiscallYear=".$fiscallYear.",
            percentTakhfif=$percentTakhfif,enamad=$enamad,logoPosition=$logoPosition,
            firstPrize=$firstPrize,
            secondPrize=$secondPrize,
            thirdPrize=$thirdPrize,
            fourthPrize=$fourthPrize,
            fifthPrize=$fifthPrize,
            sixthPrize=$sixthPrize,
            seventhPrize=$seventhPrize,
            eightPrize=$eightthPrize,
            ninthPrize=$ninthPrize,
            teenthPrize=$teenthPrize"
        );
            $specialSettings=DB::select("SELECT * FROM NewStarfood.dbo.star_webSpecialSetting");
            $settings=[];
            foreach ($specialSettings as $setting) {
                $settings=$setting;
            }
            $stocks=DB::select("SELECT SnStock,CompanyNo,CodeStock,NameStock from Shop.dbo.Stocks where SnStock!=0 and NameStock!='' and CompanyNo=5");
            $removableStocks=$request->post("removeStocksFromWeb");
            $addableStocks=$request->post("addedStocksToWeb");
            if($addableStocks){
                foreach ($addableStocks as $stock) {
                    list($id,$name)=explode('_',$stock);
                    DB::table("NewStarfood.dbo.addedStocks")->insert(['stockId'=>$id]);
                }
            }
            if($removableStocks){
                foreach ($removableStocks as $stock) {
                    if($stock!='on'){
                    list($id,$name)=explode('_',$stock);
                    DB::table("NewStarfood.dbo.addedStocks")->where('stockId',$id)->delete();
                    }
                }
            }
            $addedStocks=DB::table("NewStarfood.dbo.addedStocks")->join("Shop.dbo.Stocks","addedStocks.stockId","=","SnStock")->select("*")->get();
            return redirect("/controlMainPage");
    }


    public function emptyGame(Request $request){
        $gameHistory=DB::table("NewStarfood.dbo.star_game_score")->orderbydesc("score")->get();
        $prizes=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get();
        $firstPosId=0;
        $secPosId=0;
        $thirdPosId=0;
        $fourthPosId=0;
        $fifthPosId=0;
        $sixthPosId=0;
        $seventhPosId=0;
        $eightthPosId=0;
        $ninthPosId=0;
        $teenthPosId=0;

        if(isset($gameHistory[0]->customerId)){
            $firstPosId=$gameHistory[0]->customerId;
        }
        if(isset($gameHistory[1])){
            $secPosId=$gameHistory[1]->customerId;
        }

        if(isset($gameHistory[2]->customerId)){
            $thirdPosId=$gameHistory[2]->customerId;
        }

        if(isset($gameHistory[3]->customerId)){
            $fourthPosId=$gameHistory[3]->customerId;
        }

        if(isset($gameHistory[4]->customerId)){
            $fifthPosId=$gameHistory[4]->customerId;
        }

        if(isset($gameHistory[5]->customerId)){
            $sixthPosId=$gameHistory[5]->customerId;
        }

        if(isset($gameHistory[6]->customerId)){
            $seventhPosId=$gameHistory[6]->customerId;
        }

        if(isset($gameHistory[7]->customerId)){
            $eightthPosId=$gameHistory[7]->customerId;
        }

        if(isset($gameHistory[8]->customerId)){
            $ninthPosId=$gameHistory[8]->customerId;
        }

        if(isset($gameHistory[9]->customerId)){
            $teenthPosId=$gameHistory[9]->customerId;
        }

        DB::table("NewStarfood.dbo.star_game_history")->insert([ "firstPosId"=>$firstPosId
                                                            ,"secondPosId"=>$secPosId
                                                            ,"thirdPosId"=>$thirdPosId
                                                            ,"fourthPosId"=>$fourthPosId
                                                            ,"fifthPosId"=>$fifthPosId
                                                            ,"sixthPosId"=>$sixthPosId
                                                            ,"seventhPosId"=>$seventhPosId
                                                            ,"eightPosId"=>$eightthPosId
                                                            ,"ninthPosId"=>$ninthPosId
                                                            ,"teenthPosId"=>$teenthPosId
                                                            ,"firstPrize"=>$prizes[0]->firstPrize
                                                            ,"secondPrize"=>$prizes[0]->secondPrize
                                                            ,"thirdPrize"=>$prizes[0]->thirdPrize
                                                            ,"fourthPrize"=>$prizes[0]->fourthPrize
                                                            ,"fifthPrize"=>$prizes[0]->fifthPrize
                                                            ,"sixthPrize"=>$prizes[0]->sixthPrize
                                                            ,"seventhPrize"=>$prizes[0]->seventhPrize
                                                            ,"eightthPrize"=>$prizes[0]->eightPrize
                                                            ,"ninthPrize"=>$prizes[0]->ninthPrize
                                                            ,"teenthPrize"=>$prizes[0]->teenthPrize]);
        DB::table("NewStarfood.dbo.star_game_score")->delete();
        return redirect("/webSpecialSettings");
    }
    public function editCustomer(Request $request)
    {
       $customerSn=$request->post('customerSn');
       $groupCode=$request->post('customerGRP');
       $customer=DB::select("SELECT star_CustomerPass.customerPss,userName,peopels.PCode,Peopels.PSN,Peopels.Name,
                            Peopels.PeopelEghtesadiCode,peopels.peopeladdress,peopels.TimeStamp,PhoneDetail.PhoneStr,A.countLogin,percentTakhfif,discription from Shop.dbo.Peopels
                            join Shop.dbo.PhoneDetail on Peopels.PSN=PhoneDetail.SnPeopel
                            join NewStarfood.dbo.star_CustomerPass on star_CustomerPass.customerId=Peopels.PSN
                            left join (SELECT count(id) as countLogin,customerId FROM NewStarfood.dbo.star_customerSession1 group by customerId)A on A.customerId=Peopels.PSN 
                            join NewStarfood.dbo.star_customerRestriction on Peopels.PSN=star_customerRestriction.customerId
                            left JOIN (select discription,customerId from NewStarfood.dbo.star_takhfifHistory where changeDate=(select MAX(changeDate) from NewStarfood.dbo.star_takhfifHistory where customerId=".$customerSn."))c on c.customerId=Peopels.PSN
                            where Peopels.CompanyNo=5 and Peopels.PSN=".$customerSn." and peopels.GroupCode=".$groupCode);
       $exactCustomer;
       foreach ($customer as $cust) {
            $exactCustomer=$cust;
            $customerRestriction=DB::select("SELECT * FROM NewStarfood.dbo.star_customerRestriction where customerId=".$cust->PSN);
            if(count($customerRestriction)>0){
                foreach ($customerRestriction as $restrict) {
                    $exactCustomer->minimumFactorPrice=$restrict->minimumFactorPrice;
                    $exactCustomer->manyMobile=$restrict->manyMobile;
                    $exactCustomer->exitButtonAllowance=$restrict->exitButtonAllowance;
                    $exactCustomer->pardakhtLive=$restrict->pardakhtLive;
                    $exactCustomer->forceExit=$restrict->forceExit;
                    $exactCustomer->officialInfo=$restrict->activeOfficialInfo;
                }
            }else{
                $exactCustomer->minimumFactorPrice=0;
                $exactCustomer->manyMobile=0;
                $exactCustomer->exitButtonAllowance=0;
                $exactCustomer->pardakhtLive=0;
                $exactCustomer->forceExit=0;
                $exactCustomer->officialInfo=0;
            }
       }
       $defaultPercent=DB::select("SELECT percentTakhfif from NewStarfood.dbo.star_webSpecialSetting")[0]->percentTakhfif;
       $takhfifHistory=DB::select("SELECT * from NewStarfood.dbo.star_takhfifHistory where customerId=".$customerSn);
       return view('admin.editCustomer',['customer'=>$exactCustomer,'takhfifHistory'=>$takhfifHistory,'defaultPercent'=>$defaultPercent]);
    }
    public function afterEditCustomer(Request $request)
    {
       $customerSn=Session::get('tempPSN');
       $customer=DB::select("SELECT star_CustomerPass.customerPss,peopels.PCode,Peopels.PSN,Peopels.Name,
                            Peopels.PeopelEghtesadiCode,peopels.peopeladdress,peopels.TimeStamp,PhoneDetail.PhoneStr,A.countLogin,percentTakhfif,discription from Shop.dbo.Peopels
                            join Shop.dbo.PhoneDetail on Peopels.PSN=PhoneDetail.SnPeopel
                            join NewStarfood.dbo.star_CustomerPass on star_CustomerPass.customerId=Peopels.PSN
                            left join (SELECT count(id) as countLogin,customerId FROM NewStarfood.dbo.star_customerSession1 group by customerId)A on A.customerId=Peopels.PSN 
                            join NewStarfood.dbo.star_customerRestriction on Peopels.PSN=star_customerRestriction.customerId
                            left JOIN (select discription,customerId from NewStarfood.dbo.star_takhfifHistory where changeDate=(select MAX(changeDate) from NewStarfood.dbo.star_takhfifHistory where customerId=".$customerSn."))c on c.customerId=Peopels.PSN
                            where Peopels.CompanyNo=5 and Peopels.PSN=".$customerSn);
       $exactCustomer;
       foreach ($customer as $cust) {
            $exactCustomer=$cust;
            $customerRestriction=DB::select("SELECT * FROM NewStarfood.dbo.star_customerRestriction where customerId=".$cust->PSN);
            if(count($customerRestriction)>0){
                foreach ($customerRestriction as $restrict) {
                    $exactCustomer->minimumFactorPrice=$restrict->minimumFactorPrice;
                    $exactCustomer->manyMobile=$restrict->manyMobile;
                    $exactCustomer->exitButtonAllowance=$restrict->exitButtonAllowance;
                    $exactCustomer->pardakhtLive=$restrict->pardakhtLive;
                    $exactCustomer->forceExit=$restrict->forceExit;
                    $exactCustomer->officialInfo=$restrict->activeOfficialInfo;
                }
            }else{
                $exactCustomer->minimumFactorPrice=0;
                $exactCustomer->manyMobile=0;
                $exactCustomer->exitButtonAllowance=0;
                $exactCustomer->pardakhtLive=0;
                $exactCustomer->forceExit=0;
                $exactCustomer->officialInfo=0;
            }
       }
       $takhfifHistory=DB::select("SELECT customerId,money,discription,FORMAT(changeDate,'yyyy/MM/dd','fa') as changeDate,isUsed,lastPercent from NewStarfood.dbo.star_takhfifHistory where customerId=".$customerSn);
       return view('admin.editCustomer',['customer'=>$exactCustomer,'takhfifHistory'=>$takhfifHistory]);
    }
    public function assignTakhfif(Request $request)
    {
        $psn=$request->post("CustomerSn");
        $discription=$request->post("discription");
        $percentTakhfif=(float)str_replace("/", ".",$request->post("percentTakhfif"));
        $lastPercentTakhfif=0;
        $defaultPercent=DB::select("select percentTakhfif from NewStarfood.dbo.star_webSpecialSetting")[0]->percentTakhfif;
        $personalPercent=DB::select("select percentTakhfif from NewStarfood.dbo.star_customerRestriction where customerId=$psn")[0]->percentTakhfif;
        if($personalPercent){
            $lastPercentTakhfif=$personalPercent;
        }else{
            $lastPercentTakhfif=$defaultPercent;
        }
        $allMoneyTakhfif=0;
        $hasTakhfifHistory=DB::select("select c.customerId,money,changeDate from (
                                        select  money,customerId from NewStarfood.dbo.star_takhfifHistory where changeDate=(select MAX(changeDate)
                                        as changeDate from NewStarfood.dbo.star_takhfifHistory where customerId=$psn and isUsed=0 group by customerId) and customerId=$psn
                                        )a join (select MAX(changeDate) as changeDate,customerId from NewStarfood.dbo.star_takhfifHistory where isUsed=0 group by customerId)c on a.customerId=c.customerId
                                        ");
        //اگر تاریخچه استفاده شده و یا اصلا  نداشت و هنوز ویرایش تخفیف نداشت                                
        if(count($hasTakhfifHistory)<1 and !$personalPercent) { 
            $allMoneyTakhfif=DB::select("select sum(NetPriceHDS/10)*$lastPercentTakhfif/100 as SummedAllMoney from Shop.dbo.GetAndPayHDS where CompanyNo=5
                                        and GetOrPayHDS=1 and FiscalYear=".Session::get("FiscallYear")." and DocDate>'1401/08/30' and PeopelHDS=$psn");
            DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$psn)->update(['percentTakhfif'=>$percentTakhfif]);
            DB::table("NewStarfood.dbo.star_takhfifHistory")->insert(
            [
            'customerId'=>$psn
            ,'money'=>(float)$allMoneyTakhfif[0]->SummedAllMoney
            ,'discription'=>"".$discription.""
            ,'lastPercent'=>$lastPercentTakhfif
            ,'isUsed'=>0]);
        }
// اگر تاریخچه تخفیف داشت و لی استفاده شده بود.
        if(count($hasTakhfifHistory)<1 and $personalPercent) {   
            $allMoneyTakhfif=DB::select("select sum(NetPriceHDS/10)*$lastPercentTakhfif/100 as SummedAllMoney
                                        From Shop.dbo.GetAndPayHDS where CompanyNo=5 and GetOrPayHDS=1 and FiscalYear=".Session::get("FiscallYear")." and
                                        PeopelHDS=".Session::get("psn")." and DocDate>"."(select FORMAT(CAST(Max(changeDate) AS DATE), 'yyyy/MM/dd', 'fa') from NewStarfood.dbo.star_takhfifHistory where isUsed=1 and customerId=".Session::get("psn")." group by customerId)
                                        ");

            DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$psn)->update(['percentTakhfif'=>$percentTakhfif]);
            DB::table("NewStarfood.dbo.star_takhfifHistory")->insert(
            [
            'customerId'=>$psn
            ,'money'=>(float)$allMoneyTakhfif[0]->SummedAllMoney
            ,'discription'=>"".$discription.""
            ,'lastPercent'=>$lastPercentTakhfif
            ,'isUsed'=>0]);
        }

        // اگر تاریخچه تخفیف داشت و  استفاده نشده بود.
        if(count($hasTakhfifHistory)>0 and $personalPercent) {   
            $allMoneyTakhfif=DB::select("select sum(NetPriceHDS/10)*$lastPercentTakhfif/100 as SummedAllMoney
                                        From Shop.dbo.GetAndPayHDS where CompanyNo=5 and GetOrPayHDS=1 and FiscalYear=".Session::get("FiscallYear")." and
                                        PeopelHDS=".Session::get("psn")." and DocDate>"."(select FORMAT(CAST(Max(changeDate) AS DATE), 'yyyy/MM/dd', 'fa') from NewStarfood.dbo.star_takhfifHistory where isUsed=0 and customerId=".Session::get("psn")." group by customerId)
                                        ");
            DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",$psn)->update(['percentTakhfif'=>$percentTakhfif]);
            DB::table("NewStarfood.dbo.star_takhfifHistory")->insert(
            [
            'customerId'=>$psn
            ,'money'=>(float)$allMoneyTakhfif[0]->SummedAllMoney
            ,'discription'=>"".$discription.""
            ,'lastPercent'=>$lastPercentTakhfif
            ,'isUsed'=>0]);
        }

        Session::put("tempPSN",$psn);
        return redirect('/afterEditCustomer');
    }
    public function restrictCustomer(Request $request)
    {
        $eqtisadiCode=$request->get("EqtisadiCode");
		$userName=$request->get("userName");
        $pardakhtLive=$request->get("PardakhtLive");
        $factorMinPrice=str_replace(",", "",$request->get("FactorMinPrice"));
        $exitAllowance=$request->get("ExitAllowance");
        $manyMobile=$request->get("ManyMobile");
        $customerId=$request->get("CustomerId");
        $forceExit=$request->get("ForceExit");
        $officialInfo=$request->get("officialInfo");
        $userExistance=DB::select("SELECT count(id) as countExist FROM NewStarfood.dbo.star_customerRestriction where customerId=".$customerId);
        $customerExist=0;
        DB::update("UPDATE NewStarfood.dbo.star_CustomerPass SET customerPss='".$eqtisadiCode."',userName='".$userName."' where customerId=".$customerId);
        foreach ($userExistance as $customer) {
            $customerExist=$customer->countExist;
        }
        if($customerExist>0){
            if($forceExit==1){
                $manyMobile=0;
                $sessions=DB::select("SELECT sessionId FROM NewStarfood.dbo.star_customerSession1 WHERE customerId=".$customerId);
                $x=$customerId;
                foreach ($sessions as $sess) {
                    Session::getHandler()->destroy("".trim($sess->sessionId)."");
                }
                
            //    DB::delete(" DELETE FROM NewStarfood.dbo.star_customerSession1 where customerId=".$customerId);
            //    DB::update("UPDATE NewStarfood.dbo.star_customerRestriction SET manyMobile=0 WHERE customerId=".$customerId);
            }
            DB::update("UPDATE NewStarfood.dbo.star_customerRestriction SET pardakhtLive=".$pardakhtLive.",manyMobile=".$manyMobile.",	minimumFactorPrice=".$factorMinPrice."
            ,exitButtonAllowance=".$exitAllowance.",forceExit=".$forceExit.",activeOfficialInfo=".$officialInfo." WHERE customerId=".$customerId);
            $countLogedInUsers=DB::select("SELECT COUNT(id) as countLogedIn FROM NewStarfood.dbo.star_customerSession1 WHERE customerId=".$customerId);
            $countLogedIns=0;
            foreach ($countLogedInUsers as $logedIn) {
                $countLogedIns=$logedIn->countLogedIn;
            }
            if($manyMobile >= $countLogedIns){
                //no action need
            }else{
                $tobeDelete=$countLogedIns-$manyMobile;
                $logedInUsers=DB::select("SELECT sessionId FROM NewStarfood.dbo.star_customerSession1 WHERE customerId=".$customerId);
                $tbdel=0;
                foreach ($logedInUsers as $logedIn) {
                    $tbdel+=1;
                    Session::getHandler()->destroy("".trim($logedIn->sessionId)."");
                    DB::delete("DELETE FROM NewStarfood.dbo.star_customerSession1 WHERE sessionId='".($logedIn->sessionId)."' AND customerId=".$customerId);
                    
                    if($tbdel==$tobeDelete){
                        break;
                    }
                }
            }

        }else{
            DB::insert("INSERT INTO NewStarfood.dbo.star_customerRestriction(pardakhtLive,minimumFactorPrice,exitButtonAllowance,manyMobile,customerId,activeOfficialInfo)
            VALUES(".$pardakhtLive.",".$factorMinPrice.",".$exitAllowance.",".$manyMobile.",".$customerId.",".$officialInfo.")");
        }
        return Response::json("good");
    }
    public function messages(Request $request)
    {
        $messages=DB::select("SELECT  PhoneStr,Shop.dbo.Peopels.Name,Shop.dbo.Peopels.PSN,star_message.messageContent,convert(Date,star_message.messageDate) as messageDate,unread as countUnread,Didread as countRead,countAll
		from NewStarfood.dbo.star_message join (select MAX(star_message.id) as messageId,customerId from NewStarfood.dbo.star_message group by star_message.customerId)a on star_message.id=a.messageId
        left join ( select COUNT(star_message.id) AS Didread,star_message.customerId FROM NewStarfood.dbo.star_message where star_message.readState=1 group by customerId )b on b.customerId=a.customerId
        left join ( select COUNT(star_message.id) AS unread,star_message.customerId FROM NewStarfood.dbo.star_message where star_message.readState=0 group by customerId )c on c.customerId=a.customerId
        left join ( select COUNT(star_message.id) AS countAll,star_message.customerId FROM NewStarfood.dbo.star_message group by customerId )d on d.customerId=a.customerId

        join Shop.dbo.Peopels on a.customerId=Peopels.PSN
		join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
		FROM Shop.dbo.PhoneDetail
		GROUP BY SnPeopel)e on Peopels.PSN=e.SnPeopel
		order by star_message.id asc");

        return view('admin.messages',['messages'=>$messages]);
    }
    public function getMessages(Request $request){
        $messages=DB::select("SELECT * FROM NewStarfood.dbo.star_message
        join Shop.dbo.Peopels on star_message.customerId=Peopels.PSN  WHERE customerId=".$request->get('customerSn')." order by id asc");
        DB::update("UPDATE NewStarfood.dbo.star_message SET readState=1 WHERE customerId=".$request->get('customerSn'));
        $msg="";
        $conversation="";
        foreach ($messages as $message) {
            $firstPart="";
            $secondPart="";
            $firstPart='
                   <h3>'.$message->Name.'</h3>
                    <div class="d-flex flex-row justify-content-start mb-2">
                        <img src="/resources/assets/images/boy.png" alt="avatar 1" style="width: 45px;">
                          <div class="p-3 ms-2" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">
                        <input id="customerSn" type="text" style="display:none" value="'.$request->get('customerSn').'"/>
                            <p class="small mb-0" style="font-size:1rem;"> '.$message->messageContent.'</p>
                        </div>
                    </div>';

            $secondPart='';

                $replays=DB::select("SELECT * FROM NewStarfood.dbo.star_replayMessage WHERE messageId=".$message->id);
                $msg="";
                foreach ($replays as $replay) {
                        $msg.='
                      <div class="d-flex flex-row justify-content-end mb-2" id="replayDiv'.$replay->id.'">
                          <div class="p-3 me-3 border" style="border-radius: 15px; background-color: #fbfbfb;">
                             <p class="small mb-0" style="font-size:1rem;">'.$replay->replayContent.' </p>
                          </div>
                           <img src="/resources/assets/images/girl.png" alt="avatar 1" style="width: 45px;">
                       </div>';
                }
            $conversation.=$firstPart.$msg.$secondPart;
            }

        return Response::json($conversation);
    }
    public function loginAdmin(Request $request)
    {
        
            Session::forget("adminName");
            Session::forget("adminId");
        return view('admin.login');
    }
    public function checkAdmin(Request $request)
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
        $username=$request->post("username");
        $password=$request->post("password");
        $admins=DB::select("SELECT * FROM NewStarfood.dbo.admin where userName='".$username."' and password=".$password);
        $exist=0;
        $adminName="";
        $adminId="";
        foreach ($admins as $admin) {
            $adminName=$admin->userName;
            $adminId=$admin->id;
        }
        if(count($admins)>0){
            
            $fiscallYear=DB::select("SELECT FiscallYear FROM NewStarfood.dbo.star_webSpecialSetting")[0]->FiscallYear;
                Session::put('adminName',$adminName);
                Session::put('adminId',$adminId);
                Session::put('FiscallYear',$fiscallYear);

            $alarmStuff=DB::select("SELECT * FROM(
                                SELECT * FROM(
                                SELECT GoodSn FROM Shop.dbo.PubGoods WHERE GoodSn
                                in( SELECT productId FROM NewStarfood.dbo.star_GoodsSaleRestriction WHERE AlarmAmount>0))a
                                JOIN Shop.dbo.ViewGoodExists on a.GoodSn=ViewGoodExists.SnGood WHERE ViewGoodExists.CompanyNo=5 and ViewGoodExists.FiscalYear=".Session::get("FiscallYear").")b 
                                JOIN (SELECT AlarmAmount,productId FROM NewStarfood.dbo.star_GoodsSaleRestriction)c on b.GoodSn=c.productId");

            foreach ($alarmStuff as $kala) {

                $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;

            }

            $alarmedKala=array();
        
            foreach ($alarmStuff as $stuff) {
        
                if($stuff->AlarmAmount >= $stuff->Amount ){
            
                    array_push($alarmedKala,$stuff->productId);
            
                }
        
            }
            $alarmedKalas=array();

            if(count($alarmedKala)>0){

                $alarmedKalas=DB::select("SELECT GoodName,Amount,GoodSn FROM Shop.dbo.PubGoods
                                    JOIN Shop.dbo.ViewGoodExists on PubGoods.GoodSn=ViewGoodExists.SnGood 
                                    WHERE ViewGoodExists.CompanyNo=5 and ViewGoodExists.FiscalYear=".Session::get("FiscallYear")."
                                    AND GoodSn in(".implode(',',$alarmedKala).")");
                
                foreach ($alarmedKalas as $kala) {

                    $kala->Amount+=DB::select("select SUM(Amount) as SumAmount from Shop.dbo.ViewGoodExistsInStock where ViewGoodExistsInStock.SnStock in(select stockId from NewStarfood.dbo.star_addedStock where productId=".$kala->GoodSn.") and SnGood=".$kala->GoodSn)[0]->SumAmount;
    
                } 
            }
            return view("admin.alarmModal",["alarmedKalas"=>$alarmedKalas]);
        }else{
            return redirect('loginAdmin');
        }
    }
    public function searchCustomer(Request $request){
        $searchText=$request->get("searchText");
        // $searchResult=DB::select("SELECT Name,PCode,peopeladdress FROM Peopels where Name like'%".$searchText."%'  and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).")");
        $searchResult=DB::select("SELECT * FROM Shop.dbo.Peopels where (Name like'%".$searchText."%' or PCode like '%".$searchText."%' or peopeladdress like '%".$searchText."%') and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).")");
        return Response::json($searchResult);
    }
    public function notifyUser(Request $request)
    {
        return view('admin.notifyUser');
    }
    public function logout(Request $request)
    {
        Session::forget('adminId');
        Session::forget('adminName');
        redirect('/loginAdmin');
    }



//for official customer
// the following function show customer list.

public function listOfficialCustomer(Request $request) {
    $checkOfficialExistance=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();
    $customers = DB::table("NewStarfood.dbo.star_Customer")->select("*")->where('customerShopSn', Session::get('psn'))->get();
        $exactCustomer=array();
        $hoquqFlag=0;
        foreach ($customers as $customer) {
            if($customer->customerType=='hoqoqi'){
                $hoquqFlag=1;
            }else{
                $hoquqFlag=0;
            }
            $exactCustomer = $customer;
        }
        $haqiqiCustomers = DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
        $hoqoqiCustomers = DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
        return View('admin.listOfficialCustomer', ['haqiqicustomers'=>$haqiqiCustomers,'hoqoqicustomers'=>$hoqoqiCustomers, 'exactCustomer'=>$exactCustomer, 'checkOfficialExistance'=>$checkOfficialExistance]);
}



// function returning page for adding customers

public function addOfficialCustomer(){

    $customerId=Session::get('psn');
    $customers=DB::table("NewStarfood.dbo.star_Customer")->where('customerShopSn', Session::get('psn'))->select("*")->get();
    $exactCustomer=array();
        $hoquqFlag=0;
    foreach ($customers as $customer) {
        $exactCustomer=$customer;
    }
    $checkOfficialExistance=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();
    return view('admin.addOfficialCustomer', ['exactCustomer'=>$exactCustomer, 'checkOfficialExistance'=>$checkOfficialExistance]);
}




// store the customer data into database
public function doAddOfficialCustomer(Request $request){

    $profiles=DB::select("SELECT Peopels.Name,PhoneDetail.PhoneStr from Shop.dbo.Peopels join Shop.dbo.PhoneDetail on Peopels.PSN=PhoneDetail.SnPeopel where Peopels.CompanyNo=5 and  Peopels.PSN=".Session::get('psn'));
    $profile;
    foreach ($profiles as $profile1) {
        $profile=$profile1;
    }
    $checkOfficialExistance=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();
    $factors=DB::select("SELECT FactorHDS.* FROM Shop.dbo.FactorHDS  where FactorHDS.CustomerSn=".Session::get('psn'));
    $orders=DB::table("Shop.dbo.OrderHDS")->where("CustomerSn",Session::get('psn'))->get();
    foreach ($orders as $order) {
        $orederPrice=0;
        $prices=DB::select("SELECT Price FROM Shop.dbo.OrderBYS where SnHDS=".$order->SnOrder);
        foreach ($prices as $price) {
            $orederPrice+=$price->Price;
        }
        $order->Price=$orederPrice;
    }

     $customerShopSn=$request->post("customerShopSn");
    $checkExistance=DB::table("NewStarfood.dbo.star_Customer")->where('customerType', $request->post("customerType"))->where('customerShopSn', Session::get('psn'))->count('customerShopSn');


// if the customer exist already then update the needed fields
if($checkExistance>0){

        $customerName=$request->post("customerName");
        $familyName=$request->post("familyName");
        $codeMilli=$request->post("codeMilli");
        $codeEqtisadi=$request->post("codeEqtisadi");
        $codeNaqsh=$request->post("codeNaqsh");
        $address=$request->post("address");
        $codePosti=$request->post("codePosti");
        $email=$request->post("email");
        $companyName=$request->post("companyName");
        $shenasahMilli=$request->post("shenasahMilli");
        $registerNo=$request->post("registerNo");
        $phoneNo=$request->post("phoneNo");
        $shenasNamahNo=$request->post("shenasNamahNo");
        $sabetPhoneNo=$request->post("sabetPhoneNo");
        $customerType=$request->post("customerType");
        $customerShopSn=$request->post("customerShopSn");

        $customers = DB::table("NewStarfood.dbo.star_Customer")->where('customerShopSn', Session::get('psn'))->select("*")->get();

        $exactCustomer;
        $hoquqFlag=0;
        foreach ($customers as $customer) {
            if($customer->customerType=='hoqoqi'){
                $hoquqFlag=1;
            }else{
                $hoquqFlag=0;
            }
            $exactCustomer = $customer;
        }

$type = strval($exactCustomer->customerType);
// check if it is haqiqi then update the following  fields
    if($customerType=== "haqiqi"){
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
            $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
            $checkOfficialExistance=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();

            return View('admin.listOfficialCustomer', ['haqiqicustomers'=>$haqiqiCustomers,'hoqoqicustomers'=>$hoqoqiCustomers, 'profile'=>$profile,'factors'=>$factors,'exactCustomer'=>$exactCustomer, 'checkOfficialExistance'=>$checkOfficialExistance, 'orders'=>$orders]);

    // check if it is hoqoqi then updated the following fields
     }else {

    DB::update("UPDATE star_Customer set
            codeEqtisadi='".$codeEqtisadi."',
            codeNaqsh='".$codeNaqsh."',
            address='".$address."',
            codePosti='".$codePosti."',
            email='".$email."',
            companyName='".$companyName."',
            shenasahMilli='".$shenasahMilli."',
            registerNo='".$registerNo."',
            sabetPhoneNo='".$sabetPhoneNo."',
            phoneNo='".$phoneNo."',
            customerType='".$customerType."',
            customerShopSn='".Session::get("psn")."'
            where customerType='".$customerType."' and customerShopSn='".Session::get("psn")."'");
            $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
            $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
            $checkOfficialExistance=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();
             $officialAllowed=DB::table("NewStarfood.dbo.star_customerRestriction")->where("customerId",Session::get('psn'))->get();
             DB::update("UPDATE star_customerRestriction set activeOfficialInfo=0 where customerId=".Session::get("psn"));

            return View('admin.listOfficialCustomer', ['haqiqicustomers'=>$haqiqiCustomers,'hoqoqicustomers'=>$hoqoqiCustomers, 'profile'=>$profile,'factors'=>$factors,'orders'=>$orders, 'exactCustomer'=>$exactCustomer, 'checkOfficialExistance'=>$checkOfficialExistance]);
    }

// if the customer is not exist the add new customer
}else{

    // if the customer is not already exist and it is  haqiqi add the following fields to database
     if($request->post("customerType") === "haqiqi"){
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
            'sabetPhoneNo'=>"".$shenasNamahNo."",
            'customerType'=>"".$customerType."",
            'customerShopSn'=>''.Session::get("psn").''
            ]);

// if the customer is not already exist and it is  hoqoqi add the following fields to database
        }else {

            $codeEqtisadi=$request->post("codeEqtisadi");
            $codeNaqsh=$request->post("codeNaqsh");
            $address=$request->post("address");
            $codePosti=$request->post("codePosti");
            $email=$request->post("email");
            $companyName=$request->post("companyName");
            $shenasahMilli=$request->post("shenasahMilli");
            $registerNo=$request->post("registerNo");
            $phoneNo=$request->post("phoneNo");
            $sabetPhoneNo=$request->post("sabetPhoneNo");
            $shenasNamahNo=$request->post("shenasNamahNo");
            $customerType=$request->post("customerType");
            $customerShopSn=$request->post(Session::get("psn"));

         DB::table("NewStarfood.dbo.star_Customer")->insert([
            'companyName'=>"".$companyName,
            'shenasahMilli'=>"".$shenasahMilli,
            'registerNo'=>"".$registerNo,
            'codeEqtisadi'=>"".$codeEqtisadi."",
            'codeNaqsh'=>"".$codeNaqsh."",
            'address'=>"".$address."",
            'codePosti'=>"".$codePosti."",
            'email'=>"".$email."",
            'phoneNo'=>"".$phoneNo."",
            'shenasNamahNo'=>"".$shenasNamahNo."",
            'sabetPhoneNo'=>"".$shenasNamahNo."",
            'customerType'=>"".$customerType."",
            'customerShopSn'=>''.Session::get("psn").''
            ]);
        }
    }
            $customerId=Session::get('psn');
            $customers=DB::table("NewStarfood.dbo.star_Customer")->where('customerShopSn', Session::get('psn'))->select("*")->get();

            $exactCustomer;
                $hoquqFlag=0;
            foreach ($customers as $customer) {
                $exactCustomer=$customer;
            }
            $haqiqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','haqiqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
            $hoqoqiCustomers=DB::table("NewStarfood.dbo.star_Customer")->where('customerType','hoqoqi')->where('customerShopSn', Session::get('psn'))->select("*")->get();
            $checkOfficialExistance=DB::table("NewStarfood.dbo.star_Customer")->where("customerShopSn",Session::get('psn'))->count();

             $orders=DB::table("Shop.dbo.OrderHDS")->where("CustomerSn",Session::get('psn'))->get();
              DB::update("UPDATE star_customerRestriction set activeOfficialInfo=0 where customerId=".Session::get("psn"));
        return View('admin.listOfficialCustomer', ['haqiqicustomers'=>$haqiqiCustomers,'hoqoqicustomers'=>$hoqoqiCustomers, 'profile'=>$profile,'factors'=>$factors,'exactCustomer'=>$exactCustomer, 'checkOfficialExistance'=>$checkOfficialExistance, 'orders'=>$orders]);

}
public function aboutUs(Request $request)
{
    return View("admin.aboutUs");
}
public function siteInfo(Request $request)
{
    $witchSubmit=$request->post("submitInfo");
    if($witchSubmit=="aboutUs"){
        $fileObj=$request->file("info");
        if($fileObj){
            $files = glob('resources/assets/docs/aboutUs'.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    unlink($file);
            }
            $fileName=$fileObj->getClientOriginalName();
            list($a,$b)=explode(".",$fileName);
            $fileName="aboutUs.".$b;
            $fileObj->move("resources/assets/docs/aboutUs/",$fileName);
        }else{
        }

    }
    if($witchSubmit=="privacyRegion"){
        $fileObj=$request->file("info");
        if($fileObj){
            $files = glob('resources/assets/docs/privacy'.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    unlink($file);
            }
            $fileName=$fileObj->getClientOriginalName();
            list($a,$b)=explode(".",$fileName);
            $fileName="privacy.".$b;
            $fileObj->move("resources/assets/docs/privacy/",$fileName);
        }else{

        }
    }
    if($witchSubmit=="aboutStore"){
        $fileObj=$request->file("info");
        if($fileObj){
            $files = glob('resources/assets/docs/aboutStore'.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    unlink($file);
            }
            $fileName=$fileObj->getClientOriginalName();
            list($a,$b)=explode(".",$fileName);
            $fileName="aboutStore.".$b;
            $fileObj->move("resources/assets/docs/aboutStore/",$fileName);
        }else{

        }
    }
    if($witchSubmit=="condationsAndRules"){
        $fileObj=$request->file("info");
        if($fileObj){
            $files = glob('resources/assets/docs/rules'.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    unlink($file);
            }
            $fileName=$fileObj->getClientOriginalName();
            list($a,$b)=explode(".",$fileName);
            $fileName="rules.".$b;
            $fileObj->move("resources/assets/docs/rules/",$fileName);
        }else{

        }
    }
    if($witchSubmit=="more"){
        $fileObj=$request->file("info");
        if($fileObj){
            $files = glob('resources/assets/docs/aboutStore'.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    unlink($file);
            }
            $fileName=$fileObj->getClientOriginalName();
            list($a,$b)=explode(".",$fileName);
            $fileName="more.".$b;
            $fileObj->move("resources/assets/docs/aboutUs/",$fileName);
        }else{

        }
    }
    return redirect("/controlMainPage");
}

public function karbarRoles(){
    return view('admin.karbaranRole');
}
public function loginAkhlaqi(){
    return view('login.loginAkhlaqi');
}
public function loginStarfood(){
    return view('login.login');
}
public function loginAdminStarfood(){
    return view('login.loginStarfoodAdmin');
}
public function loginPishrow(){
    return view('login.loginPishrow');
}
public function loginCrm(){
    return view('login.loginCrm');
}
public function crmLogin(Request $request)
{
    $customer=DB::table("Shop.dbo.Peopels")->where("PSN",$request->get("psn"))->first();
    $fiscallYear=DB::select("SELECT FiscallYear FROM NewStarfood.dbo.star_webSpecialSetting")[0]->FiscallYear;
    Session::put('FiscallYear',$fiscallYear);
    Session::put('username', $customer->Name);
    Session::put('psn',$customer->PSN);
    Session::put('otherUserInfo',(string)trim($request->get("otherName")));
    return redirect("https://starfoods.ir");
}
public function getAllCustomersToMNM(Request $request)
{
    $mantiqahId=$request->get("MantiqahId");
    $customers=DB::select("select Name,PCode,PSN,SnMantagheh,peopeladdress from Shop.dbo.Peopels where CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and Name!='' and SnMantagheh=0");
    $addedCustomers=DB::select("select Name,PCode,PSN,SnMantagheh,peopeladdress from Shop.dbo.Peopels where CompanyNo=5 and SnMantagheh=".$mantiqahId);
    return Response::json([$customers,$addedCustomers]);
}
public function searchCustomerByAddressMNM(Request $request)
{
    $searchTerm=$request->get("searchTerm");
    $customers=DB::select("SELECT Name,peopeladdress,PCode,PSN FROM Shop.dbo.Peopels WHERE peopeladdress LIKE '%".$searchTerm."%' AND CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=0");
    return Response::json($customers);
}
public function searchCustomerByNameMNM(Request $request)
{
    $searchTerm=$request->get("searchTerm");
    $customers=DB::select("SELECT Name,peopeladdress,PCode,PSN FROM Shop.dbo.Peopels WHERE Name LIKE '%".$searchTerm."%' AND CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=0");
    return Response::json($customers);
}
public function searchCustomerAddedAddressMNM(Request $request)
{
    $searchTerm=$request->get("searchTerm");
    $mantiqahId=$request->get("mantiqahId");
    $customers=DB::select("SELECT Name,peopeladdress,PCode,PSN FROM Shop.dbo.Peopels WHERE peopeladdress LIKE '%".$searchTerm."%' AND CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=".$mantiqahId);
    return Response::json($customers);
}
public function searchCustomerAddedNameMNM(Request $request)
{
    $searchTerm=$request->get("searchTerm");
    $mantiqahId=$request->get("mantiqahId");
    $customers=DB::select("SELECT Name,peopeladdress,PCode,PSN FROM Shop.dbo.Peopels WHERE Name LIKE '%".$searchTerm."%' AND CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=".$mantiqahId);
    return Response::json($customers);
}
public function addCustomerToMantiqah(Request $request)
{
    $customerIDs=$request->get("customerIDs");
    $mantiqahId=$request->get("mantiqahId");
    $cityId=$request->get("cityId");
    foreach ($customerIDs as $id) {
        DB::table("Shop.dbo.Peopels")->where('PSN',$id)->update(["SnMantagheh"=>$mantiqahId,'SnNahiyeh'=>$cityId,'SnMasir'=>79]);
    }
    $addedCustomers=DB::select("SELECT Name,peopeladdress,PCode,PSN FROM Shop.dbo.Peopels WHERE CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=".$mantiqahId);
    $customers=DB::select("select Name,PCode,PSN,SnMantagheh,peopeladdress from Shop.dbo.Peopels where CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and Name!='' and SnMantagheh=0");
    return Response::json([$customers,$addedCustomers]);
}
public function removeCustomerFromMantiqah(Request $request)
{
    $customerIds=$request->get("customerIDs");
    $mantiqahId=$request->get("mantiqahId");
    foreach ($customerIds as $id) {
        DB::table("Shop.dbo.Peopels")->where("PSN",$id)->update(["SnMantagheh"=>0]);
    }
    $addedCustomers=DB::select("SELECT Name,peopeladdress,PCode,PSN FROM Shop.dbo.Peopels WHERE CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and SnMantagheh=".$mantiqahId);
    $customers=DB::select("select Name,PCode,PSN,SnMantagheh,peopeladdress from Shop.dbo.Peopels where CompanyNo=5 and GroupCode IN ( ".implode(",",[291,297,299,312,313,314]).") and Name!='' and SnMantagheh=0");
    return Response::json([$customers,$addedCustomers]);
}
public function getCityInfo(Request $request)
{
    $cityId=$request->get("id");
    $cities=DB::table("Shop.dbo.MNM")->where("SnMNM",$cityId)->get()->first();
    return Response::json($cities);
}
public function editCity(Request $request)
{
    $cityName=$request->get("cityName");
    $cityId=$request->get("cityIdEdit");
    DB::table("Shop.dbo.MNM")->where("SnMNM",$cityId)->update(["NameRec"=>"".$cityName.""]);
    $cities=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and Rectype=1 and FatherMNM=79");
    return Response::json($cities);
}
public function addCity(Request $request)
{
    $cityName=$request->get("cityName");
    DB::table("Shop.dbo.MNM")->insert(['CompanyNo'=>5,'RecType'=>1,'NameRec'=>"".$cityName."",'FatherMNM'=>79,'SnSellerN'=>0,'Add_Update'=>0,'IsExport'=>0,'SnSellerN2'=>0]);
    $cities=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and Rectype=1 and FatherMNM=79");
    return Response::json($cities);
}
public function deleteCity(Request $request)
{
   $cityId=$request->get("id");
   DB::table("Shop.dbo.MNM")->where("SnMNM",$cityId)->delete();
   $cities=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and Rectype=1 and FatherMNM=79");
   return Response::json($cities); 
}
public function getMantaghehInfo(Request $request)
{
    $id=$request->get("id");
    $mantiqah=DB::table("Shop.dbo.MNM")->where("SnMNM",$id)->get()->first();
    return Response::json($mantiqah);
}
public function editMantagheh(Request $request)
{
    $name=$request->get("Name");
    $id=$request->get("mantaghehIdEdit");
    $cityId=$request->get("cityId");
    DB::table("Shop.dbo.MNM")->where("SnMNM",$id)->update(['NameRec'=>"".$name.""]);
    $mantiqah=DB::table("Shop.dbo.MNM")->where("FatherMNM",$cityId)->get();
    return Response::json($mantiqah);
}
public function addMantiqah(Request $request)
{
    $cityId=$request->get("cityId");
    $name=$request->get("name");
     DB::table("Shop.dbo.MNM")->insert(['CompanyNo'=>5,'RecType'=>2,'NameRec'=>"".$name."",'FatherMNM'=>$cityId,'SnSellerN'=>0,'Add_Update'=>0 ,'IsExport'=>0,'SnSellerN2'=>0]);
     $mantiqah=DB::table("Shop.dbo.MNM")->where("FatherMNM",$cityId)->get();
     return Response::json($mantiqah);
}
public function deleteMantagheh(Request $request)
{
    $cityId=$request->get("cityId");
    $mantiqahId=$request->get("mantiqahId");
    DB::table("Shop.dbo.MNM")->where("SnMNM",$mantiqahId)->delete();
    $mantiqah=DB::table("Shop.dbo.MNM")->where("FatherMNM",$cityId)->get();
    return Response::json($mantiqah);
}
public function loginCrm2(Request $request)
{
    return view('admin.crmLogin');
}
public function downloadApk(Request $request)
{
	$headers =  [
            'Content-Type'=>'application/vnd.android.package-archive',
            'Content-Disposition'=> 'attachment; filename="starfood.apk"',
        ];
   // return response()->download(base_path('resources\\assets\\apks\\starfood.apk'));
	return response()->file(base_path('resources\\assets\\apks\\starfood.apk') , $headers);
}
public function addGamePrize(Request $request)
{
    $firstPrize=$request->post("firstPrize");
    $secondPrize=$request->post("secondPrize");
    $thirdPrize=$request->post("thirdPrize");
    $fourthPrize=$request->post("fourthPrize");
    $fifthPrize=$request->post("fifthPrize");
    $sixthPrize=$request->post("sixthPrize");
    $seventhPrize=$request->post("seventhPrize");
    $eightthPrize=$request->post("eightthPrize");
    $ninthPrize=$request->post("ninthPrize");
    $teenthPrize=$request->post("teenthPrize");
    $countPrizes=DB::table("NewStarfood.dbo.star_game_prize")->count();
   
    if($countPrizes>0){
        DB::table("NewStarfood.dbo.star_game_prize")->update(['firstPrize'=>$firstPrize
                            ,'secondPrize'=>$secondPrize
                            ,'thirdPrize'=>$thirdPrize
                            ,'fourthPrize'=>$fourthPrize
                            ,'fifthPrize'=>$fifthPrize
                            ,'sixthPrize'=>$sixthPrize
                            ,'seventhPrize'=>$seventhPrize
                            ,'eightPrize'=>$eightthPrize
                            ,'ninthPrize'=>$ninthPrize
                            ,'teenthPrize'=>$teenthPrize]);
    }else{
        DB::table("NewStarfood.dbo.star_game_prize")->insert(['firstPrize'=>$firstPrize
                            ,'secondPrize'=>$secondPrize
                            ,'thirdPrize'=>$thirdPrize
                            ,'fourthPrize'=>$fourthPrize
                            ,'fifthPrize'=>$fifthPrize
                            ,'sixthPrize'=>$sixthPrize
                            ,'seventhPrize'=>$seventhPrize
                            ,'eightPrize'=>$eightthPrize
                            ,'ninthPrize'=>$ninthPrize
                            ,'teenthPrize'=>$teenthPrize]);
    }
    return redirect("/webSpecialSettings");
}
}
