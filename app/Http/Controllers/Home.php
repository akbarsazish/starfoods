<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use BrowserDetect;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
class Home extends Controller{

     public function index (Request $request)
    {
        if(!Session('psn')){
            return redirect('/login');
        }
       if(!Session::get("otherUserInfo")){
            $lastReferesh=Carbon::parse(DB::table("NewStarfood.dbo.star_customerTrack")->where("customerId",SESSION::get("psn"))->select("visitDate")->max("visitDate"))->diffInHours(Carbon::now());
            $logedIns=DB::table("NewStarfood.dbo.star_customerTrack")->where("customerId",SESSION::get("psn"))->select("visitDate")->get();
            if($lastReferesh>0 or count($logedIns)<1){
                $palatform=BrowserDetect::platformFamily();
                $browser=BrowserDetect::browserFamily();
                DB::table("NewStarfood.dbo.star_customerTrack")->insert(['platform'=>$palatform,'customerId'=>SESSION::get("psn"),'browser'=>$browser]);
            }
        }
		
        $slider=DB::select("SELECT star_add_homePart_stuff.id,star_add_homePart_stuff.homepartId,star_add_homePart_stuff.firstPic,star_add_homePart_stuff.secondPic,star_add_homePart_stuff.thirdPic,star_add_homePart_stuff.fourthPic,star_add_homePart_stuff.fifthPic FROM NewStarfood.dbo.HomePart INNER JOIN NewStarfood.dbo.star_add_homePart_stuff ON HomePart.id=star_add_homePart_stuff.homepartId WHERE HomePart.partType=3");
        $smallSlider=DB::select("SELECT star_add_homePart_stuff.id,star_add_homePart_stuff.homepartId,star_add_homePart_stuff.firstPic,star_add_homePart_stuff.secondPic,star_add_homePart_stuff.thirdPic,star_add_homePart_stuff.fourthPic,star_add_homePart_stuff.fifthPic,HomePart.activeOrNot FROM NewStarfood.dbo.HomePart INNER JOIN NewStarfood.dbo.star_add_homePart_stuff ON HomePart.id=star_add_homePart_stuff.homepartId WHERE HomePart.partType=4");
        $productGroups = DB::select("select * from NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=0 order by mainGroupPriority asc");
        //برای جابجایی و نمایش بخش های صفحه اصلی نوشته می شود
        $partStringForView="";//به ویو فرستاده میشود
        $partStringLocal="";//بخش را در بر می گیرد
        $counterAsId=0;
        $shegeftAngizColorFlag=0;
        $countPartGroup=0;
        $buyFromHome=0;
        $homePageType=1;
		$webSettings=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get();
        $logoPos=$webSettings[0]->logoPosition;
		$showLogo=true;
		if($logoPos == 0){
			$logoClass="topLeft";
		}else{
			$logoClass="topRight";
			}
        $parts=DB::select("SELECT DISTINCT NewStarfood.dbo.HomePart.title,NewStarfood.dbo.HomePart.showPercentTakhfif,NewStarfood.dbo.HomePart.partColor,NewStarfood.dbo.HomePart.showAll,NewStarfood.dbo.HomePart.showTedad,NewStarfood.dbo.HomePart.showOverLine,NewStarfood.dbo.HomePart.id AS partId,NewStarfood.dbo.HomePart.priority AS PartPriority,NewStarfood.dbo.HomePart.partType,NewStarfood.dbo.star_homepart_pictures.homepartId FROM NewStarfood.dbo.HomePart LEFT JOIN NewStarfood.dbo.star_homepart_pictures ON NewStarfood.dbo.HomePart.id=NewStarfood.dbo.star_homepart_pictures.homepartId where activeOrNot=1 and  partType!=3 and  partType!=4 ORDER BY NewStarfood.dbo.HomePart.priority ASC");
        $currency=1;
        $currencyName="ریال";
        $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
        $currency=$currencyExistance[0]->currency;
        if($currency==10){
            $currencyName="تومان";
        }
        $buyFromHome=$webSettings[0]->buyFromHome;
        $homePageType=$webSettings[0]->homePage;
        $showBuys="none";
        if($buyFromHome==1){
            $showBuys="flex";
        }
            foreach ($parts as $part) {
                $counterAsId+=1;

            if($part->partType == 1){
                    $countPartGroup=$countPartGroup+1;
                    //گروهی از گروه ها
                    $groups=DB::select("SELECT Star_Group_Def.id as groupId,Star_Group_Def.title FROM NewStarfood.dbo.Star_Group_Def INNER JOIN NewStarfood.dbo.star_add_homePart_stuff ON Star_Group_Def.id=star_add_homePart_stuff.firstGroupId WHERE star_add_homePart_stuff.homepartId=".$part->partId);
                    $firstPartStringLocal=
                "<section class='product-wrapper container lastBanner'>
                        <div class='headline'><h3>".$part->title."</h3></div>
                        <div id='brandslider".$countPartGroup."' class='swiper-container swiper-container-horizontal swiper-container-rtl'>
                        <div class='product-box swiper-wrapper' style='transform: translate3d(547.5px, 0px, 0px); transition-duration: 0ms;'>";
                    $partId=$part->partId;
                    $cart="";
                    $allGroups=DB::select("SELECT  TOP ".$part->showTedad."  star_add_homePart_stuff.priority, Star_Group_Def.id as groupId,Star_Group_Def.title FROM NewStarfood.dbo.Star_Group_Def INNER JOIN NewStarfood.dbo.star_add_homePart_stuff ON Star_Group_Def.id=star_add_homePart_stuff.firstGroupId WHERE star_add_homePart_stuff.homepartId=".$part->partId." order by priority asc");
                    foreach ($allGroups as $group) {
                        $cart.="<div class='swiper-slide'>
                        <div class='emptySlider'>
                            <a href='/listKala/groupId/".$group->groupId."' >
                                <img class='mt-0'style='max-height: 110px; width:110px; margin-bottom:-10px;' src='resources/assets/images/mainGroups/" . $group->groupId . ".jpg' alt='' ></a >
                            <a class='title text-dark fw-bold' style='height: 15%;' href='/listKala/groupId/".$group->groupId."'>".$group->title."</a>
                        </div>
                    </div>
                    ";
                    }
                    $secondPartStringLocal="
                    </section>
                    ";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                if($part->partType == 12){
                    //در صورتیکه برند باشد
                    $countPartGroup=$countPartGroup+1;
                    $allBrands=DB::select("SELECT star_add_homePart_stuff.brandId,star_add_homePart_stuff.homePartId from NewStarfood.dbo.star_add_homePart_stuff where homePartId=".$part->partId." order by priority asc");
                    $firstPartStringLocal="
                    <section class='product-wrapper container lastBanner'>
                    <div class='headline'><h3>".$part->title."</h3></div>
                    <div id='brandslider".$countPartGroup."' class='swiper-container swiper-container-horizontal swiper-container-rtl'>
                        <div class='product-box swiper-wrapper align-items-start' style='transform: translate3d(272px, 0px, 0px); transition-duration: 0ms;'>";
                    $partId=$part->partId;
                    $cart="";
                    foreach ($allBrands as $brand) {
                        $cart.="<div class='swiper-slide'>
                        <div class='emptySlider'>
                        <a href='/listKala/brandCode/".$brand->brandId."'>
                            <img src='resources/assets/images/brands/".$brand->brandId. ".jpg' alt='تصویر' style='width:110px; height:110px;'></a >
                        </div>
                       </div>
                    ";
                    }
                    $secondPartStringLocal="
                    </section>";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }


                    if($part->partType == 13){
                        //لیستی از پیش خرید ها
                        $showAll="";
                        if($part->showAll==1){
                            $showAll="<a href='/getAllKala/".$part->partId."' style='display:inline;'>مشاهده همه</a>";
                        }else{
                            $showAll="";
                        }
                    $firstPartStringLocal="
                            <section class='product-wrapper container'>
                                            <div class='headline two-headline'><h4>".$part->title."</h4>".$showAll."</div>
                                            <div id='newpslider".$counterAsId."' class='swiper-container swiper-container-horizontal swiper-container-rtl'>
                                        <div class='product-box swiper-wrapper' style='transform: translate3d(1088px, 0px, 0px); transition-duration: 0ms;'>";
                                        $partId=$part->partId;
                                        $cart="";
                                        $allKalas=DB::select("SELECT TOP ".$part->showTedad." * FROM (
                                            SELECT  star_add_homePart_stuff.priority,GoodGroups.GoodGroupSn,UName,
                                                    star_add_homePart_stuff.productId,PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price4,GoodPriceSale.Price3,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.callOnSale FROM Shop.dbo.PubGoods
                                                    JOIN NewStarfood.dbo.star_add_homePart_stuff ON PubGoods.GoodSn=star_add_homePart_stuff.productId
                                                    JOIN Shop.dbo.GoodPriceSale on star_add_homePart_stuff.productId=GoodPriceSale.SnGood
                                                    JOIN Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                                                    JOIN Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=PubGoods.defaultUnit
                                                    JOIN NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                                                    WHERE star_add_homePart_stuff.homepartId=$partId
                                                    and star_add_homePart_stuff.productId not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) )a 
                                                    
                                                    JOIN (select min(firstGroupId) as firstGroupId,product_id from NewStarfood.dbo.star_add_prod_group group by product_id)b on a.productId=b.product_id order by priority asc");

                                        foreach ($allKalas as $kala) {
                                            $boughtKalas=DB::select("select  star_pishKharidFactor.*,star_pishKharidOrder.* from NewStarfood.dbo.star_pishKharidFactor join NewStarfood.dbo.star_pishKharidOrder on star_pishKharidFactor.SnOrderPishKharid=star_pishKharidOrder.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and  orderStatus=0");
                                            $orderBYSsn;
                                            $secondUnit;
                                            $amount;
                                            $packAmount;
                                            foreach ($boughtKalas as $boughtKala) {
                                                $orderBYSsn=$boughtKala->SnOrderBYSPishKharid;
                                                $orderGoodSn=$boughtKala->SnGood;
                                                $amount=$boughtKala->Amount;
                                                $packAmount=$boughtKala->PackAmount;
                                                $secondUnits=DB::select('select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods
                                                join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood
                                                join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn);
                                                if(count($secondUnits)>0){
                                                    $secondUnit=$secondUnits[0]->UName;
                                                }else{
                                                    $secondUnit=$kala->UName;
                                                }
                                            }
                                            if(count($boughtKalas)>0){
                                                $kala->bought="Yes";
                                                $kala->SnOrderBYS=$orderBYSsn;
                                                $kala->secondUnit=$secondUnit;
                                                $kala->Amount=$amount;
                                                $kala->PackAmount=$packAmount;
                                            }else{
                                                $kala->bought="No";
                                            }
                                        }
                                        foreach ($allKalas as $kala) {
                                            $orderBYSsn=0;
                                            $boughtKala="blue";
                                            $showOverLine="none";
                                            $showTakhfifPercent="none";
                                            if($kala->bought=="Yes"){
                                                $orderBYSsn=$kala->SnOrderBYS;
                                                $amount=$kala->Amount/$kala->PackAmount;
                                                $boughtKala="green";
                                                $fontSize = "27px";
                                            }else{
                                                $orderBYSsn=0;
                                                $amount=0;
                                            }
                                            if($part->showPercentTakhfif==1){
                                                $showOverLine="flex";
                                                $showTakhfifPercent="flex";

                                            }else{
                                                $showTakhfifPercent="none";
                                            }
                                            if($part->showOverLine==1 and $showOverLine=="flex"){
                                                $showOverLine="flex";
                                            }else{
                                                $showOverLine="none";
                                            }
                                            $percentResult=0;
                                            if($kala->Price4>0 and $kala->Price3>0){
                                            $percentResult=round((($kala->Price4-$kala->Price3)*100)/$kala->Price4);
                                            }else{
                                                $percentResult=0;
                                            }
                                            if($percentResult<0){
                                                $percentResult=0;
                                            }
                                            if($percentResult==0){
                                                $showTakhfifPercent="none";
                                            }
                                            $descUrl="/descKala/".$kala->firstGroupId."/itemCode/".$kala->GoodSn;
                                            $imageSrc="";
                                            if(file_exists("resources/assets/images/kala/". $kala->GoodSn ."_1.jpg")){
                                                $imageSrc="/resources/assets/images/kala/".$kala->GoodSn."_1.jpg";
                                            }else{
                                                $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                                            }
                                            $cart.="
                                            <div class='product-item swiper-slide'>
                                                <div class='col-sm-6 float-right ps-1 m-0 buyPlus' style='z-index:100;display:".$showBuys.";'>
                                                        <i onclick='preBuyFromHome(".$kala->GoodSn.",".$partId.")' id='preBuySign".$partId."_".$kala->GoodSn."' class='fa fa-plus-circle fa-lg d-flex flex-start pt-3' style='font-size:30px; color:".$boughtKala.";'>
                                                            <span style='opacity:0.1; padding-right:-5px; font-size:18px;'> خرید </span>
                                                        </i>
                                                        <div style='display:none;' class='preBuyFromHome overlay' id='preBuyFromHome".$partId."_".$kala->GoodSn."' >
                                                        <input id='preOrderNumber".$partId."_".$kala->GoodSn."' style='display:none;' value='".$orderBYSsn."' type='text'/>
                                                        <button class='tobuy fw-bold' id='addToPreBuy".$partId."_".$kala->GoodSn."' onclick='addToPreBuy(".$kala->GoodSn.",".$partId.")'>  + </button>
                                                        <span class='tobuy fw-bold' id='preBuyNumber".$partId."_".$kala->GoodSn."' style='padding:1px 12px 5px 12px;'> ".$amount."  </span>
                                                        <button class='tobuy fw-bold' id='subFromPreBuy".$partId."_".$kala->GoodSn."' onclick='subFromPreBuy(".$kala->GoodSn.",".$partId.")'> -  </button>
                                                    </div>
                                                </div>
                                                <a href='".$descUrl."'>
													<img src='".$logoSrc."' class='".$logoClass."' />
                                                    <img src='$imageSrc' alt='تصویر'>
                                                    <p class='title' ".$descUrl."'>".$kala->GoodName."</p>
                                                </a>
                                                <div class='row border-top priceDiv'>
                                                    <div class='d-block'style='width:50%'>
                                                        <div class='row d-flex flex-nowrap pe-3 pt-2' style='display:".$showTakhfifPercent.";'>
                                                                <span class='takhfif-round' style='background-color:red'> ".$percentResult."% </span></div>
                                                        <div class='row'> &nbsp </div>
                                                    </div>
                                                    <div class='inc-product-price d-block'style='width:50%'>
                                                        <div class='row'> <del class='ps-3 m-0 fw-bold' style='color: #ef394e;'>".number_format($kala->Price4/$currency)." ".$currencyName." </del></div>
                                                        <div class='row'><span class='ps-3 m-0  price' style='color:#39ae00';>".number_format($kala->Price3/$currency)." ".$currencyName." </span></div>
                                                    </div>
                                                </div>
                                                <div id='newpslider-nbtn' class='slider-nbtn swiper-button-next' style='display: none;'></div>
                                                <div id='newpslider-pbtn' class='slider-pbtn swiper-button-prev' style='display: none;'></div>
                                                <span class='swiper-notification' aria-live='assertive' aria-atomic='true'></span>
                                            </div>
                                        ";
                                            }
                                        $secondPartStringLocal="
                                    </section>";
                                     $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                if($part->partType == 2){
                    //لیستی از کالاها
                    $showAll="";

                    if($part->showAll==1){
                        $showAll="<a href='/getAllKala/".$part->partId."' style='display:inline;'>مشاهده همه</a>";
                    }else{
                        $showAll="";
                    }
                    $firstPartStringLocal="
                    <section class='product-wrapper container'>
                        <div class='headline two-headline'>
                            <h4>".$part->title."</h4>".$showAll."
                        </div>
                        <div id='newpslider".$counterAsId."' class='swiper-container swiper-container-horizontal swiper-container-rtl'>
                        <div class='product-box swiper-wrapper' style='transform: translate3d(1088px, 0px, 0px); transition-duration: 0ms;'>
                    ";
                    $partId=$part->partId;
                    $cart="";
                    $allKalas=DB::select("SELECT TOP ".$part->showTedad." * FROM (
                                            SELECT  star_add_homePart_stuff.priority,GoodGroups.GoodGroupSn,UName,
                                                    star_add_homePart_stuff.productId,PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price4,GoodPriceSale.Price3,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.callOnSale FROM Shop.dbo.PubGoods
                                                    JOIN NewStarfood.dbo.star_add_homePart_stuff ON PubGoods.GoodSn=star_add_homePart_stuff.productId
                                                    JOIN Shop.dbo.GoodPriceSale on star_add_homePart_stuff.productId=GoodPriceSale.SnGood
                                                    JOIN Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                                                    JOIN Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=PubGoods.defaultUnit
                                                    JOIN NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                                                    WHERE star_add_homePart_stuff.homepartId=$partId
                                                    and star_add_homePart_stuff.productId not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) )a 
                                                    
                                                    JOIN (select min(firstGroupId) as firstGroupId,product_id from NewStarfood.dbo.star_add_prod_group group by product_id)b on a.productId=b.product_id order by priority asc");
                        foreach ($allKalas as $kala) {
                        $boughtKalas=DB::select("select  FactorStar.*,orderStar.* from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and  orderStatus=0");
                        $orderBYSsn;
                        $secondUnit;
                        $amount;
                        $packAmount;
                        foreach ($boughtKalas as $boughtKala) {
                            $orderBYSsn=$boughtKala->SnOrderBYS;
                            $orderGoodSn=$boughtKala->SnGood;
                            $amount=$boughtKala->Amount;
                            $packAmount=$boughtKala->PackAmount;
                            $secondUnits=DB::select('select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods
                            join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood
                            join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn);
                        
                            if(count($secondUnits)>0){
                                $secondUnit=$secondUnits[0]->UName;
                            }else{
                                $secondUnit=$kala->UName;
                            }

                        }
                        if(count($boughtKalas)>0){
                            $kala->bought="Yes";
                            $kala->SnOrderBYS=$orderBYSsn;
                            $kala->secondUnit=$secondUnit;
                            $kala->Amount=$amount;
                            $kala->PackAmount=$packAmount;
                        }else{
                            $kala->bought="No";
                        }
                    }
                    foreach ($allKalas as $kala) {
                        $orderBYSsn=0;
                        $boughtKala="red";
                        $showOverLine="none";
                        $showTakhfifPercent="none";
                        $showCall="flex";
                        if($kala->callOnSale==1){
                            $showCall="none";
                        }else{
                            $showCall="flex";
                        }
                        if($kala->bought=="Yes"){
                            $orderBYSsn=$kala->SnOrderBYS;
                            $amount=$kala->Amount/$kala->PackAmount;
                            $boughtKala="green";
                            $fontSize = "27px";
                        }else{
                            $orderBYSsn=0;
                            $amount=0;
                        }
                        if($part->showPercentTakhfif==1){
                            $showOverLine="flex";
                            $showTakhfifPercent="flex";
                        }else{
                            $showTakhfifPercent="none";
                        }
                        if($part->showOverLine==1 and $showOverLine=="flex"){
                            $showOverLine="flex";
                        }else{
                            $showOverLine="none";
                        }
                        $percentResult=0;
                        if($kala->Price4>0 and $kala->Price3>0){
                        $percentResult=round((($kala->Price4-$kala->Price3)*100)/$kala->Price4);
                        }else{
                            $percentResult=0;
                        }
                        if($percentResult<0){
                            $percentResult=0;
                        }
                        if($percentResult==0){
                            $showTakhfifPercent="none";
                        }
                        $descUrl="/descKala/".$kala->firstGroupId."/itemCode/".$kala->GoodSn;

                        $imageSrc="";
                        if(file_exists("resources/assets/images/kala/". $kala->GoodSn ."_1.jpg")){
							$logoSrc='/resources/assets/images/starfood.png';
							
                            $imageSrc="/resources/assets/images/kala/".$kala->GoodSn."_1.jpg";
                        }else{
                            $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                        }
                        $cart.="
                        <div class='product-item swiper-slide'>
                        <div class='col-sm-6 ps-1 m-0 buyPlus' style='z-index:1000;display:".$showBuys.";'>
                        <i onclick='buyFromHome(".$kala->GoodSn.",".$partId.")' id='buySign".$partId."_".$kala->GoodSn."' class='fa fa-plus-circle fa-lg d-flex flex-start pt-3' style='font-size:30px;color:".$boughtKala.";' >
                            <span style='opacity:0.1; padding-left:5px; font-size:18px;'> خرید </span> </i>
                        <div style='display:none;' class='buyFromHome alert overlay' id='buyFromHome".$partId."_".$kala->GoodSn."' >
                        <input class='tobuy'  id='orderNumber".$partId."_".$kala->GoodSn."' style='display:none' value='".$orderBYSsn."' type='text'/>
                        <button class='tobuy' id='addToBuy".$partId."_".$kala->GoodSn."' onclick='addToBuy(".$kala->GoodSn.",".$partId.")' style='display:bock;'> + </button>
                        <span  class='tobuy fw-bold' id='BuyNumber".$partId."_".$kala->GoodSn."' style='padding:1px 12px 5px 12px;'>".$amount."</span>
                        <button class='tobuy' id='subFromBuy".$partId."_".$kala->GoodSn."' onclick='subFromBuy(".$kala->GoodSn.",".$partId.")'> - </button>
                        </div>
                        </div>
                            <a href='".$descUrl."'>
							<img src='".$logoSrc."' class='".$logoClass."' />
                                <img src='$imageSrc' alt='تصویر'>
                                <p class='title' ".$descUrl."'>".$kala->GoodName."</p>
                            </a>
                                <div class='row border-top priceDiv'>
                                    <div class='d-block'style='width:50%'>
                                        <div class='row d-flex flex-nowrap pe-3 pt-2' style='display:".$showTakhfifPercent.";'>
                                                <span class='takhfif-round' style='background-color:red'> ".$percentResult."% </span></div>
                                        <div class='row'> &nbsp </div>
                                    </div>
                                    <div class='inc-product-price d-block'style='width:50%'>
                                        <div class='row'> <del class='ps-3 m-0 fw-bold' style='color: #ef394e;'>".number_format($kala->Price4/$currency)." ".$currencyName." </del></div>
                                        <div class='row'><span class='ps-3 m-0  price' style='color:#39ae00';>".number_format($kala->Price3/$currency)." ".$currencyName." </span></div>
                                    </div>
                                </div>
                                <div id='newpslider-nbtn' class='slider-nbtn swiper-button-next' style='display: none;'></div>
                                <div id='newpslider-pbtn' class='slider-pbtn swiper-button-prev' style='display: none;'></div>
                                <span class='swiper-notification' aria-live='assertive' aria-atomic='true'></span>
                            </div>
                        ";
                    }
                    $secondPartStringLocal="</section>";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }


                if($part->partType == 11){
                    //شگفت انگیزها'
                    $showOverLine="none";
                    $showTakhfifPercent="none";
                    if($part->showAll==1){
                        $showAll="<div  class='specials__btn'>مشاهده همه</div>";
                    }else{
                        $showAll="<div style='width:100px'></div>";
                    }
                    $shegeftAngizColorFlag+=1;
                    $changeShegeftColor="";
                        $firstPartStringLocal='
                        <section class="product-wrapper container">
                          <div  id="" style="background-color:'.$part->partColor.' !important" class="c-swiper-specials--incredible mt-2 mb-0">
                            <div style="overflow: hidden" class="container">
                            <section class="icontainer">
                                <a href="/getAllKala/'.$part->partId.'" class="specials__title">
                                    <img src="/resources/assets/images/shegeftAngesPicture/'.$part->partId.'.jpg" alt="پیشنهاد شگفت&zwnj;انگیز">'.$showAll.'</a>
                                <div class="swiper--specials">
                                    <div id="inc-slider'.$shegeftAngizColorFlag.'" class="swiper-container swiper-container-horizontal swiper-container-rtl">
                                    <div class="product-box swiper-wrapper  d-flex align-items-center justify-content-center" style="transform: translate3d(1088px, 0px, 0px); transition-duration: 0ms;">';

                                        $partId=$part->partId;
                                        $cart="";
                                        $allKalas=DB::select("SELECT TOP ".$part->showTedad." * FROM (
                                            SELECT  star_add_homePart_stuff.priority,GoodGroups.GoodGroupSn,UName,
                                                    star_add_homePart_stuff.productId,PubGoods.GoodName,PubGoods.GoodSn,GoodPriceSale.Price4,GoodPriceSale.Price3,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.callOnSale FROM Shop.dbo.PubGoods
                                                    JOIN NewStarfood.dbo.star_add_homePart_stuff ON PubGoods.GoodSn=star_add_homePart_stuff.productId
                                                    JOIN Shop.dbo.GoodPriceSale on star_add_homePart_stuff.productId=GoodPriceSale.SnGood
                                                    JOIN Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn
                                                    JOIN Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=PubGoods.defaultUnit
                                                    JOIN NewStarfood.dbo.star_GoodsSaleRestriction on PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
                                                    WHERE star_add_homePart_stuff.homepartId=$partId
                                                    and star_add_homePart_stuff.productId not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 ) )a 
                                                    
                                                    JOIN (select min(firstGroupId) as firstGroupId,product_id from NewStarfood.dbo.star_add_prod_group group by product_id)b on a.productId=b.product_id order by priority asc");

                                        foreach ($allKalas as $kala) {
                                        $boughtKalas=DB::select("select  FactorStar.*,orderStar.* from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and SnGood=".$kala->GoodSn." and  orderStatus=0");
                                        $orderBYSsn;
                                        $secondUnit=$kala->UNAME;
                                        $amount;
                                        $packAmount;
                                        foreach ($boughtKalas as $boughtKala) {
                                            $orderBYSsn=$boughtKala->SnOrderBYS;
                                            $orderGoodSn=$boughtKala->SnGood;
                                            $amount=$boughtKala->Amount;
                                            $packAmount=$boughtKala->PackAmount;
                                            $secondUnits=DB::select('select GoodUnitSecond.AmountUnit,PubGoods.GoodSn,PUBGoodUnits.UName from Shop.dbo.PubGoods
                                            join Shop.dbo.GoodUnitSecond on PubGoods.GoodSn=GoodUnitSecond.SnGood
                                            join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5 and GoodUnitSecond.SnGood='.$orderGoodSn);
                                        
                                        $secondUnit=$secondUnits[0]->UName;
                                        }
                                        if(count($boughtKalas)>0){
                                            $kala->bought="Yes";
                                            $kala->SnOrderBYS=$orderBYSsn;
                                            $kala->secondUnit=$secondUnit;
                                            $kala->Amount=$amount;
                                            $kala->PackAmount=$packAmount;
                                        }else{
                                            $kala->bought="No";
                                        }
                                    }
                                        foreach ($allKalas as $kala) {
                                            $orderBYSsn=0;
                                            $boughtKala="red";
                                            if($kala->bought=="Yes"){
                                                $orderBYSsn=$kala->SnOrderBYS;
                                                $amount=$kala->Amount/$kala->PackAmount;
                                                $boughtKala="green";
                                                $fontSize = "27px";
                                            }else{
                                                $orderBYSsn=0;
                                                $amount=0;
                                            }
                                            if($part->showPercentTakhfif==1){
                                                $showOverLine="flex";
                                                $showTakhfifPercent="flex";

                                            }else{
                                                $showTakhfifPercent="none";
                                            }
                                            if($part->showOverLine==1 or $showOverLine=="flex"){
                                                $showOverLine="flex";
                                            }else{
                                                $showOverLine="none";
                                            }
                                            if($kala->Price4>0 and $kala->Price3>0){
                                                $percentResult=round((($kala->Price4-$kala->Price3)*100)/$kala->Price4);
                                                }else{
                                                    $percentResult=0;
                                                }
                                            if($percentResult==0){
                                                $showTakhfifPercent="none";
                                            }
                                            $descUrl="/descKala/".$kala->firstGroupId."/itemCode/".$kala->GoodSn;
                                            $imageSrc="";
                                            if(file_exists("resources/assets/images/kala/". $kala->GoodSn ."_1.jpg")){
												
                                                $imageSrc="/resources/assets/images/kala/".$kala->GoodSn."_1.jpg";
                                            }else{
                                                $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                                            }
                                            $cart.='

                                            <div class="shegeftAngez-item swiper-slide shegeftAngezHover">
                                                    <div class="col-sm-6 ps-1 m-0 buyPlus" style="z-index:9999999999999999999999; display:'.$showBuys.'; position:fixed;">
                                                        <i onclick="buyFromHome('.$kala->GoodSn.','.$partId.')" id="buySign'.$partId.'_'.$kala->GoodSn.'" class="fa fa-plus-circle fa-lg d-flex flex-start pt-3" style="font-size:30px;color:'.$boughtKala.';" >
                                                        <span style="opacity:0.1; padding-left:5px; font-size:18px;"> خرید </span> </i>
                                                        <div style="display:none;" class="buyFromHome alert overlay" id="buyFromHome'.$partId.'_'.$kala->GoodSn.'" >
                                                        <input class="tobuy"  id="orderNumber'.$partId.'_'.$kala->GoodSn.'" style="display:none" value="'.$orderBYSsn.'" type="text"/>
                                                        <button class="tobuy" id="addToBuy'.$partId.'_'.$kala->GoodSn.'" onclick="addToBuy('.$kala->GoodSn.','.$partId.')" style="display:bock;"> + </button>
                                                        <span  class="tobuy fw-bold" id="BuyNumber'.$partId.'_'.$kala->GoodSn.'" style="padding:1px 12px 5px 12px;">'.$amount.'</span>
                                                        <button class="tobuy" id="subFromBuy'.$partId.'_'.$kala->GoodSn.'" onclick="subFromBuy('.$kala->GoodSn.','.$partId.')"> - </button>
                                                    </div>
                                                    </div>

                                                        <a href="'.$descUrl.'">
														<img src="'.$logoSrc.'" class="'.$logoClass.'" />
                                                            <img style="z-index:2" src="'.$imageSrc.'" alt="تصویر">
                                                            <p class="title pe-1 pt-1" href="'.$descUrl.'">'.$kala->GoodName.'</p>
                                                        </a>
                                                    </dvi>

                                                        <div class="row border-top priceDiv">
                                                        <div class="d-block" style="width:50%">
                                                            <div class="row d-flex flex-nowrap pe-3 pt-2" style="display:'.$showTakhfifPercent.';">
                                                                <span class="takhfif-round" style="background-color:red">'.$percentResult.'% </span></div>
                                                            <div class="row"> &nbsp </div>
                                                        </div>
                                                        <div class="inc-product-price d-block" style="width:50%; text-align:left;">
                                                                <div class="row"> <del class="ps-3 ps-0 m-0 fw-bold d-flex justify-content-end" style="color:#ef394e;">'.number_format($kala->Price4/$currency).' '.$currencyName.' </del></div>
                                                                <div class="row"> <span class="ps-3 m-0  price d-flex justify-content-end" style="color:#39ae00;">'.number_format($kala->Price3/$currency).' '.$currencyName.' </span></div>
                                                        </div>
                                                    </div>
                                                    </div>';
                                        }
                                        $secondPartStringLocal=' </div></div><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                                                            </div> </section></div> </div></section>';
                                $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                if($part->partType == 6){
                    //تک عکسی و لیستی از کالاها
                    $firstPartStringLocal="
                    <div class='container'>
                        <div class='row'>
                            <div class='col-12 medium-100'>";
                            $partId=$part->partId;
                            $cart="";
                    $pictures=DB::select("select * from NewStarfood.dbo.star_homepart_pictures where homepartId=".$partId);
                    foreach ($pictures as $pic) {
                        $countPicKala=0;
                        $disabled="";
                        $countPicKala = DB::table("NewStarfood.dbo.star_add_homePart_stuff")->where("partPic",$pic->id)->count();
                    if($countPicKala<1){
                            $disabled="style='pointer-events: none; cursor: default;'";
                    }else{
                        $disabled="";
                    }
                        $cart.="
                            <a class='item-box mb-1' ".$disabled." href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."'></a>
                            <a href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."' ".$disabled."'>
                                <img style='width: 100%;' src='/resources/assets/images/onePic/".$part->homepartId.".jpg' alt=''>
                            </a>
                        ";
                    }
                    $secondPartStringLocal="
                            </div>
                            </div>
                        </div>";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                if($part->partType == 7){
                    //دوعکسی و لیستی از کالاها
                    $firstPartStringLocal="
                    <div class='container'>
                        <div class='row'>";
                            $partId=$part->partId;
                            $cart="";
                    $pictures=DB::select("select * from NewStarfood.dbo.star_homepart_pictures where homepartId=".$partId);
                    $i=0;
                    foreach ($pictures as $pic) {
                        $i=$i+1;
                        $countPicKala=0;
                        $disabled="";
                        $countPicKala = DB::table("NewStarfood.dbo.star_add_homePart_stuff")->where("partPic",$pic->id)->count();
                    if($countPicKala<1){
                        $disabled="style='pointer-events: none; cursor: default;'";
                    }else{
                        $disabled="";
                    }
                    $imageSrc="";

                    if(file_exists("resources/assets/images/twoPics/".$part->homepartId."_".$i.".jpg")){
                        $imageSrc="resources/assets/images/twoPics/".$part->homepartId."_".$i.".jpg";
                        }else{
                        $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                        }

                        $cart.="
                            <div class='col-6 col-md-6 medium-25 pe-1'>
                                <div class='item-box mb-1' href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."' ".$disabled.">
                                    <div class='item-media'>
                                    <a href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."' ".$disabled.">
									<img src='".$logoSrc."' class='".$logoClass."' />
                                        <img style='min-height:170px;' src='$imageSrc' ".$part->homepartId."_".$i.".jpg' alt='تصویر'>
                                    </a>
                                    </div>
                                </div>
                            </div>
                        ";
                            }
                    $secondPartStringLocal="
                        </div>
                    </div> ";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                if($part->partType == 8){
                    //سه عکسی و لیستی از کالاها
                    $firstPartStringLocal="
                    <div class='container'>
                        <div class='row'>";
                            $partId=$part->partId;
                            $cart="";
                    $pictures=DB::select("select * from NewStarfood.dbo.star_homepart_pictures where homepartId=".$partId);
                    $i=0;
                    $countCol=0;
                    foreach ($pictures as $pic) {
                        $i=$i+1;
                        $countCol++;
                        $countPicKala=0;
                        $disabled="";
                        $countPicKala = DB::table("NewStarfood.dbo.star_add_homePart_stuff")->where("partPic",$pic->id)->count();
                    if($countPicKala<1){
                        $disabled="style='pointer-events: none; cursor: default;'";
                    }else{
                        $disabled="";
                    }
                    $colSize=12;
                    if($countCol==2 or $countCol==1){
                            $colSize=6;
                        }
                        $imageSrc="";
                        if(file_exists("resources/assets/images/threePics/".$part->homepartId."_".$i.".jpg")){
                            $imageSrc="/resources/assets/images/threePics/".$part->homepartId."_".$i.".jpg";
                            }else{
                            $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                            }
                        $cart.="<div class='col-".$colSize." col-md-4 medium-25 pe-1'>
                                    <div class='item-box mb-1' href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."' ".$disabled.">
                                        <div class='item-media'>
                                            <a href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."'  ".$disabled.">
											<img src='".$logoSrc."' class='".$logoClass."' />
                                                <img style='height:170px;' src='".$imageSrc."' alt=''>
                                            </a>
                                        </div>
                                    </div>
                                </div> ";
                            }
                    $secondPartStringLocal="</div>";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                if($part->partType == 9){
                    //چهار عکسی و لیستی از کالاها
                    $firstPartStringLocal="
                    <section class='product-wrapper container'>
                        <div id='newpslider' class='swiper-container swiper-container-horizontal swiper-container-rtl'>
                            <div class='product-box swiper-wrapper align-items-center d-flex justify-content-center text-center' style='transform: translate3d(272px, 0px, 0px); transition-duration: 0ms;'>";
                            $partId=$part->partId;
                            $cart="";
                    $pictures=DB::select("select * from NewStarfood.dbo.star_homepart_pictures where homepartId=".$partId);
                    $i=0;
                    foreach ($pictures as $pic) {
                        $i=$i+1;
                        $countPicKala=0;
                        $disabled="";
                        $countPicKala = DB::table("NewStarfood.dbo.star_add_homePart_stuff")->where("partPic",$pic->id)->count();
                    if($countPicKala<1){
                        $disabled="style='pointer-events: none; cursor: default;'";
                    }else{
                        $disabled="";
                    }
                    $imageSrc="";
                    if(file_exists("resources/assets/images/fourPics/".$part->homepartId."_".$i.".jpg")){
                        $imageSrc="/resources/assets/images/fourPics/".$part->homepartId."_".$i.".jpg";
                        }else{
                        $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                        }
                        $cart.="<div class='swiper-slide swiper-slide-prev d-flex justify-content-center'>
                                <a style='display:block; width:100%;' href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."' ".$disabled.">
								<img src='".$logoSrc."' class='".$logoClass."' />
                                <img class=img-responsiv' src='$imageSrc' alt=''></a> </div>";
                            }
                    $secondPartStringLocal="
                    </div><div id='pslider-nbtn' class='slider-nbtn swiper-button-next' style='display: none;'></div>
                    <div id='pslider-pbtn' class='slider-pbtn swiper-button-prev' style='display: none;'></div>
                    <span class='swiper-notification' aria-live='assertive' aria-atomic='true'></span>
                    </div></section>";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                if($part->partType == 10){
                    //پنج عکسی و لیستی از کالاها
                    $firstPartStringLocal="
                    <div class='container'><div class='row'>";
                            $partId=$part->partId;
                            $cart="";
                    $pictures=DB::select("select * from NewStarfood.dbo.star_homepart_pictures where homepartId=".$partId);
                    $i=0;
                    foreach ($pictures as $pic) {
                        $i=$i+1;
                        $countPicKala=0;
                        $disabled="";
                        $countPicKala = DB::table("NewStarfood.dbo.star_add_homePart_stuff")->where("partPic",$pic->id)->count();
                    if($countPicKala<1){
                        $disabled="style='pointer-events: none; cursor: default;'";
                    }else{
                        $disabled="";
                    }
                    if($i<4){
                        $imageSrc="";
                        if(file_exists("resources/assets/images/fivePics/".$part->homepartId."_".$i.".jpg")){
                            $imageSrc="/resources/assets/images/fivePics/".$part->homepartId."_".$i.".jpg";
                            }else{
                            $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                            }
                        $cart.=" <div class='col-12 col-md-4 medium-20  fifthBanner'>
                                    <div class='item-box mb-2' href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."' ".$disabled.">
                                        <div class='item-media'>
                                            <a href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."'>
											<img src='".$logoSrc."' class='".$logoClass."' />
                                                <img src='$imageSrc'  ".$disabled." alt=''>
                                            </a>
                                        </div>
                                    </div>
                                </div>";
                        }else{
                            $imageSrc="";
                            if(file_exists("resources/assets/images/fivePics/".$part->homepartId."_".$i.".jpg")){
                                $imageSrc="/resources/assets/images/fivePics/".$part->homepartId."_".$i.".jpg";
                                }else{
                                $imageSrc="/resources/assets/images/defaultKalaPics/altKala.jpg";
                                }
                            $cart.="
                            <div class='col-12 col-md-6 medium-20 fifthBanner'>
                                <div class='item-box mb-2' href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."'   ".$disabled.">
                                    <div class='item-media'>
                                        <a href='/listKalaFromPart/".$part->homepartId."/partPic/".$pic->id."'   ".$disabled.">
                                            <img style='height:170px;' src='$imageSrc' alt='' >
                                        </a>
                                    </div>
                                </div>
                            </div>";
                        }
                            }
                    $secondPartStringLocal="</div></div>";
                    $partStringLocal=$firstPartStringLocal.$cart.$secondPartStringLocal;
                }
                $partStringForView.=$partStringLocal;
            }
            $socials=DB::select("select enamad from NewStarfood.dbo.star_webSpecialSetting");
            $showEnamad=0;
            foreach ($socials as $social) {
                $showEnamad=$social->enamad;
            }
        return View('home.home',['productGroups' => $productGroups,'homePageType'=>$homePageType,'slider'=>$slider,'smallSlider'=>$smallSlider,'partViews'=>$partStringForView,'showEnamad'=>$showEnamad]);
    }
    public function aboutUs(Request $request)
    {
        return View("siteInfo.aboutUs");
    }
    public function policy(Request $request)
    {
        return View("siteInfo.policy");
    }
    public function contactUs(Request $request)
    {
        return View("siteInfo.contactUs");
    }
    public function privacy(Request $request)
    {
        return View("siteInfo.privacy");
    }
}
