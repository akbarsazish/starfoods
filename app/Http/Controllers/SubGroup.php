<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\StarSubGroup;
use DB;
use Response;
use DateTime;
use Session;
class SubGroup extends Controller {
    public function index(Request $request)
    {
        return view("subGroups.listKala");
    }
    public function editSubGroup(Request $request)
    {
        $fatherGroupId=$request->post('fatherMainGroupId');
        $title=$request->post("subGroupNameEdit");
        $subGruopId=$request->post("subGroupId");
        // $priority=$request->post('priority');
        // $lastPriorities=DB::table('NewStarfood.dbo.Star_Group_DEF')->select('subGroupPriority')->where('id',$subGruopId)->get();
        // $lastPriority=0;
        // foreach ($lastPriorities as $pr) {
        //     $lastPriority=$pr->subGroupPriority;
        // }
        // DB::update("UPDATE NewStarfood.dbo.Star_Group_DEF SET subGroupPriority=".$priority." where id=".$subGruopId);
        // DB::update("UPDATE NewStarfood.dbo.Star_Group_DEF SET subGroupPriority=".$lastPriority." where subGroupPriority=".$priority." and id!=".$subGruopId." and selfGroupId=".$fatherGroupId);
        if($request->file('subGroupPictureEdit')){
        $picture=$request->file('subGroupPictureEdit');
        $filename=$request->file('subGroupPictureEdit')->getClientOriginalName();
        $filename=$subGruopId.'.'.'jpg';
        $picture->move("resources/assets/images/subgroup/",$filename);
        }
        DB::update("UPDATE NewStarfood.dbo.Star_Group_DEF
        SET title = '".$title."' 
        WHERE id=".$subGruopId);
        return redirect('/listGroup');
    }
    public function addSubGroup(Request $request)
    {
        $title=$request->post('groupTitle');
        $priority=$request->post('priority');
        $fatherGroupId=$request->post('fatherMainGroupId');
        $selfGroupId=$request->post('selfGroupId');
        DB::update("UPDATE NewStarfood.dbo.Star_Group_DEF set subGroupPriority+=1 where subGroupPriority>=".$priority." and selfGroupId=".$fatherGroupId);
        DB::insert("INSERT INTO NewStarfood.dbo.Star_Group_DEF (title,show_hide,created_date,selfGroupId,percentTakhf,subGroupPriority)
        VALUES('".$title."',1,DEFAULT,".$fatherGroupId.",0,".$priority.")") ;
        if($request->file('subGroupPicture')){
        $picture=$request->file('subGroupPicture');
        $filename=$request->file('subGroupPicture')->getClientOriginalName();
        $maxSubGroup=DB::table("NewStarfood.dbo.Star_Group_DEF")->where("selfGroupId",$fatherGroupId)->select("id")->max('id');
        $filename=$maxSubGroup.'.'.'jpg';
        $picture->move("resources/assets/images/subgroup/",$filename);
        }
        return redirect('/listGroup');
    }
    public function deleteSubGroup(Request $request)
    {
        $subGroupId=$request->post('id');
        $priorities=DB::table("NewStarfood.dbo.Star_Group_DEF")->where('id',$subGroupId)->select("subGroupPriority")->get();
        $priority=0;
        foreach ($priorities as $pr) {
            $priority=$pr->subGroupPriority;
        }
        $mainGroupIds=DB::table("NewStarfood.dbo.Star_Group_DEF")->where('id',$subGroupId)->select('selfGroupId')->get();
        $mainGroupId=0;
        foreach ($mainGroupIds as $mainGrId) {
            $mainGroupId=$mainGrId->selfGroupId;
        }
        DB::table("NewStarfood.dbo.star_add_prod_group")->where('secondGroupId',$subGroupId)->delete();
        DB::table("NewStarfood.dbo.Star_Group_DEF")->where('id',$subGroupId)->delete();
        DB::update("UPDATE NewStarfood.dbo.Star_Group_DEF SET subGroupPriority-=1 where subGroupPriority>".$priority." and selfGroupId=".$mainGroupId);
        if(file_exists("resources/assets/images/subgroup/".$subGroupId.".jpg")){
            unlink("resources/assets/images/subgroup/".$subGroupId.".jpg");
        }
    return redirect("/listGroup");
    }
    public function subGroups(Request $request)
    {
        $id=$request->get('id');
        $subGroups=DB::select('select id, title,selfGroupId, percentTakhf from NewStarfood.dbo.Star_Group_DEF where selfGroupId='.$id.' order by subGroupPriority asc');
         
        return Response::json($subGroups);
        
    }
    public function subGroupsEdit(Request $request)
    {
        $id=$request->get('id');
        $kalaId=$request->get('kalaId');
        $kala=DB::select('SELECT PubGoods.GoodName,PubGoods.GoodSn,PubGoods.Price,PubGoods.price2, GoodGroups.NameGRP,PUBGoodUnits.UName from Shop.dbo.PubGoods inner join Shop.dbo.GoodGroups on PubGoods.GoodGroupSn=GoodGroups.GoodGroupSn inner join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN where PubGoods.GoodSn='.$kalaId);
        $exactKala;
        foreach ($kala as $k) {
            $exactKala=$k;
        }
        $subGroupList=DB::select("select id,title,show_hide,selfGroupId from NewStarfood.dbo.Star_Group_Def  where selfGroupId=".$id);
        $addedKala=DB::select('select firstGroupId,product_id,secondGroupId from NewStarfood.dbo.star_add_prod_group WHERE product_id='.$kalaId);
        $exist="";
        
            foreach($subGroupList as $group){
                foreach($addedKala as $addkl){
                    if($addkl->secondGroupId==$group->id and $kalaId==$addkl->product_id){
                        $exist='ok';
                        break;
                    }else{
                        $exist='no';
                    }
                }
                $group->exist=$exist;
            }
        return $subGroupList;
    }
    public function getSecondSubGroup($groupId,$subGroupId)
    {
		$listKala= DB::select('select PubGoods.GoodSn,PubGoods.GoodName  from Shop.dbo.PubGoods inner join
		NewStarfood.dbo.star_add_prod_group on PubGoods.GoodSn=product_id inner join
		NewStarfood.dbo.Star_Group_DEF on 	Star_Group_DEF.id=star_add_prod_group.firstGroupId
		where star_add_prod_group.firstGroupId='.$subGroupId) ;
        
        $listSubGroups=DB::select('SELECT id,title,show_hide,selfGroupId,subGroupPriority FROM NewStarfood.dbo.Star_Group_DEF
		where selfGroupId='.$groupId);
        $logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view('groupPart.groupPart',['listKala'=>$listKala,'listGroups'=>$listSubGroups,'mainGrId'=>$groupId,'logoPos'=>$logoPos]);
    
    } 
    public function getFilteredSecondSubGroup($groupId,$subGroupId)
    {
        // without Stocks
        $listKala= DB::select("SELECT  GoodSn,GoodName,UName,Price3,Price4,SnGoodPriceSale,IIF(csn>0,'YES','NO') favorite,productId,IIF(ISNULL(productId,0)=0,0,1) as requested,IIF(zeroExistance=1,0,IIF(ISNULL(SnOrderBYS,0)=0,NewStarfood.dbo.getProductExistance(GoodSn),BoughtAmount)) Amount,IIF(ISNULL(SnOrderBYS,0)=0,'No','Yes') bought,callOnSale,SnOrderBYS,BoughtAmount,PackAmount,overLine,secondUnit,freeExistance,activeTakhfifPercent,activePishKharid FROM(
            SELECT  PubGoods.GoodSn,PubGoods.GoodName,PUBGoodUnits.UName,csn,D.productId,GoodPriceSale.Price3,GoodPriceSale.Price4,GoodPriceSale.SnGoodPriceSale
            ,E.zeroExistance,E.callOnSale,SnOrderBYS,BoughtAmount,PackAmount,E.overLine,secondUnit,star_GoodsSaleRestriction.freeExistance,star_GoodsSaleRestriction.activeTakhfifPercent,star_GoodsSaleRestriction.activePishKharid FROM Shop.dbo.PubGoods
            JOIN NewStarfood.dbo.star_GoodsSaleRestriction ON PubGoods.GoodSn=NewStarfood.dbo.star_GoodsSaleRestriction.productId
            JOIN NewStarfood.dbo.star_add_prod_group ON PubGoods.GoodSn=product_id
            JOIN NewStarfood.dbo.Star_Group_DEF ON Star_Group_DEF.id=star_add_prod_group.firstGroupId
            JOIN Shop.dbo.PUBGoodUnits ON PubGoods.DefaultUnit=PUBGoodUnits.USN
            left JOIN (select  SnOrderBYS,SnGood,Amount as BoughtAmount,PackAmount from NewStarfood.dbo.FactorStar join NewStarfood.dbo.orderStar on FactorStar.SnOrder=orderStar.SnHDS where CustomerSn=".Session::get('psn')." and orderStatus=0)f on f.SnGood=PubGoods.GoodSn
            left join (select goodSn as csn from NewStarfood.dbo.star_Favorite WHERE star_Favorite.customerSn=".Session::get('psn').")C on PubGoods.GoodSn=C.csn
            LEFT JOIN (select productId from NewStarfood.dbo.star_requestedProduct where customerId=".Session::get('psn').")D on PubGoods.GoodSn=D.productId
            left join (select zeroExistance,callOnSale,overLine,productId from NewStarfood.dbo.star_GoodsSaleRestriction)E on E.productId=PubGoods.GoodSn
            LEFT JOIN Shop.dbo.GoodPriceSale ON GoodPriceSale.SnGood=PubGoods.GoodSn
            left join (select GoodUnitSecond.AmountUnit,SnGood,UName as secondUnit from Shop.dbo.GoodUnitSecond
            join Shop.dbo.PUBGoodUnits on PUBGoodUnits.USN=GoodUnitSecond.SnGoodUnit WHERE GoodUnitSecond.CompanyNo=5)G on G.SnGood=PUbGoods.GoodSn
            WHERE secondGroupId=$subGroupId and firstGroupId=$groupId and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )
            GROUP BY PubGoods.GoodSn,E.zeroExistance,E.callOnSale,E.overLine,BoughtAmount,PackAmount,SnOrderBYS,secondUnit,D.productId,PubGoods.GoodName,GoodPriceSale.Price3,GoodPriceSale.Price4,GoodPriceSale.SnGoodPriceSale,PUBGoodUnits.UName,star_GoodsSaleRestriction.activeTakhfifPercent,
            star_GoodsSaleRestriction.freeExistance,star_GoodsSaleRestriction.activePishKharid,csn 
            ) A order by Amount desc");   

        $currency=1;

        $currencyName="ریال";

        $currencyExistance=DB::table("NewStarfood.dbo.star_webSpecialSetting")->get('currency');
		
            $currency=$currencyExistance[0]->currency;

        if($currency==10){
            $currencyName="تومان";
        }
        $listSubGroups=DB::select('SELECT * FROM NewStarfood.dbo.Star_Group_DEF where selfGroupId='.$groupId.'order by subGroupPriority desc');
		$logoPos=DB::select("SELECT logoPosition FROM NewStarfood.dbo.star_webSpecialSetting")[0]->logoPosition;
        return view('groupPart.groupPart',['listKala'=>$listKala,'listGroups'=>$listSubGroups,'subGroupId'=>$subGroupId,'mainGrId'=>$groupId,'currency'=>$currency,'currencyName'=>$currencyName,'logoPos'=>$logoPos]);
    }
    public function addKalaToSubGroups(Request $request)
    {
        $mainGrId=$request->get('mainGrId');
        $subGrId=$request->get('subGrId');
        $kalaId=$request->get('kalaId');
        foreach ($subGrId as $id) {
            DB::insert("INSERT INTO NewStarfood.dbo.star_add_prod_group(firstGroupId,product_id,secondGroupId,thirdGroupId,fourthGroupId) values(".$mainGrId.",".$kalaId.",".$id.",0,0)");
         }
        return Response::json('good');
    }
    public function deleteKalaFromSubGroups(Request $request)
    {
        $mainGrId=$request->get('mainGrId');
        $subGrId=$request->get('subGrId');
        $kalaId=$request->get('kalaId');
        foreach ($subGrId as $id) {
            DB::delete("DELETE FROM NewStarfood.dbo.star_add_prod_group WHERE firstGroupId=".$mainGrId." AND product_id=".$kalaId." AND secondGroupId=".$id);
         }
        return Response::json('good');
    }
    public function getKalaGroups(Request $request)
    {
        $subGroups=DB::select("SELECT id,title FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=0");
        return Response::json($subGroups);
    }
    public function getSubGroupList(Request $request)
    {
        $id=$request->get('mainGrId');
        $subGroups=DB::select("SELECT id,title FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=".$id);
        return Response::json($subGroups);
    }
    public function getSubGroup(Request $request)
    {
        $id=$request->get("id");
        $subGroup=DB::select("SELECT title FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=".$id);
        // foreach ($subGroup as $grp) {
        //     $group=$grp;
        // }
        return Response::json($id);
    }
    public function changeSubGroupPriority(Request $request)
    {
        $priorityState=$request->get("priorityState");
        $groupId=$request->get("subGrId");
        $mainGroupId=$request->get("mainGroupId");
        $countAllGroupsList=DB::table("NewStarfood.dbo.Star_Group_Def")->where('selfGroupId',$mainGroupId)->count();
        
        $priorities=DB::table("NewStarfood.dbo.Star_Group_Def")->where('id',$groupId)->select('subGroupPriority')->get();
        $priority=0;
        foreach ($priorities as $pr) {
            $priority=$pr->subGroupPriority;
        }
        if($priorityState=="top"){
            if($priority>1){
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set subGroupPriority-=1 WHERE id='.$groupId);
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set subGroupPriority='.($priority).' WHERE selfGroupId='.$mainGroupId.' and id!='.$groupId.' and subGroupPriority='.($priority-1));
            }
        }
        if($priorityState=="down"){
            if($priority<$countAllGroupsList){
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set subGroupPriority+=1 WHERE id='.$groupId);
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set subGroupPriority='.($priority).' WHERE selfGroupId='.$mainGroupId.' and id!='.$groupId.' and subGroupPriority='.($priority+1));
            }
        }
        $subGroups=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=".$mainGroupId." order by subGroupPriority asc");
        return Response::json($subGroups);
    }
    
    public function searchSubGroupKala(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        $subGroupId=$request->get('subGrId');
        // $kalas=DB::table("NewStarfood.dbo.star_add_prod_group")->join("Shop.dbo.PubGoods","PubGoods.GoodSn",'=','star_add_prod_group.product_id')->where("secondGroupId",$subGroupId)->select("PubGoods.*")->get();
        $kalas=DB::select("SELECT * FROM Shop.dbo.PubGoods 
        JOIN NewStarfood.dbo.star_add_prod_group ON PubGoods.GoodSn=star_add_prod_group.product_id 
        where PubGoods.GoodName like'%".$searchTerm."%' and secondGroupId=".$subGroupId." and PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )");
        return Response::json($kalas);
    }
    public function getSubGroups(Request $request)
    {
        $mainGrId=$request->get("mainGrId");
        if($mainGrId!=0){
            $subGroups=DB::table("NewStarfood.dbo.Star_Group_Def")->where("selfGroupId",$mainGrId)->select("id","title")->get();
        }else{
            $subGroups=DB::table("NewStarfood.dbo.Star_Group_Def")->where("selfGroupId","!=",0)->select("id","title")->get();
        }
        return Response::json($subGroups);
    }
    
}
