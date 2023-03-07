<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GoodGroups;
use Illuminate\Support\Facades\Validator;
use Response;
class Group extends Controller {
    public function index(Request $request){
        $mainGroups=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=0 order by mainGroupPriority asc");
        return  view ('admin.listGroup',['mainGroups'=>$mainGroups]);
        // return  view ('admin.listGroup');
    }

    public function mainGroups(Request $request){
        $allGroups=DB::select("SELECT id,title,mainGroupPriority FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=0  order by mainGroupPriority asc");
        return view('groupPart.groups',['groups'=>$allGroups]);
    }
    
    public function editGroup(Request $request){
        return view ('admin.editGroup');
    }

    public function addGroup(Request $request){
        $groupName=$request->post('mainGroupName');
        $priority=$request->post("priority");
        DB::update("UPDATE NewStarfood.dbo.Star_Group_DEF SET mainGroupPriority+=1 where mainGroupPriority>=".$priority." and selfGroupId=0");
        DB::insert("INSERT INTO NewStarfood.dbo.Star_Group_DEF(title,show_hide,created_date,selfGroupId,percentTakhf,secondBranchId,thirdBranchId,mainGroupPriority) VALUES('".$groupName."',0,DEFAULT,0,0,0,0,".$priority.")");
        if($request->file('mainGroupPicture')){
        $picture=$request->file('mainGroupPicture');
        $filename=$picture->getClientOriginalName();
        $maxGroupId=DB::table("NewStarfood.dbo.Star_Group_DEF")->where("selfGroupId",0)->select("id")->max('id');
        $filename=($maxGroupId).'.'.'jpg';
        $picture->move("resources/assets/images/mainGroups/",$filename);
        }
        return redirect('/listKala');
   }
   public function deleteMainGroup(Request $request)
   {
       $mainGroupsId=$request->post('mainGroupId');
       $mainGroupId=0;
       foreach ($mainGroupsId as $groupId) {
        list($mainGroupId, $b) = explode('_',$groupId);
       }
       $priorities=DB::table("NewStarfood.dbo.Star_Group_DEF")->select("mainGroupPriority")->where("selfGroupId",0)->get();
       $priority=0;
       foreach ($priorities as $pr) {
           $priority=$pr->mainGroupPriority;
       }
        $subGroupList=DB::table("NewStarfood.dbo.Star_Group_DEF")->where('selfGroupId',$mainGroupId)->select("id")->get();
        foreach($subGroupList as $subGroup){
            DB::table("NewStarfood.dbo.star_add_prod_group")->where("secondGroupId",$subGroup->id)->delete();
            if(file_exists("resources/assets/images/subgroup/".$subGroup->id.".jpg")){
                unlink("resources/assets/images/subgroup/".$subGroup->id.".jpg");
            }
        }
        DB::table("NewStarfood.dbo.Star_Group_DEF")->where('selfGroupId',$mainGroupId)->delete();
        DB::table("NewStarfood.dbo.Star_Group_DEF")->where('id',$mainGroupId)->delete();
        DB::update("UPDATE NewStarfood.dbo.Star_Group_DEF set mainGroupPriority-=1 where selfGroupId=0 and mainGroupPriority>".$priority);
        if(file_exists("resources/assets/images/mainGroups/".$mainGroupId.".jpg")){
            unlink("resources/assets/images/mainGroups/".$mainGroupId.".jpg");
        }
       return redirect('/listKala');
   }

   public function editMainGroup(Request $request)
   {
       $groupId=$request->post('groupId');
       $groupName=$request->post('groupName');
       if($request->file('groupPicture')){
       $picture=$request->file('groupPicture');
       $filename=$request->file('groupPicture')->getClientOriginalName();
       $filename=$groupId.'.'.'jpg';
       $picture->move("resources/assets/images/mainGroups/",$filename);
       }
       DB::update("UPDATE NewStarfood.dbo.Star_Group_Def set title='".$groupName."' where id=".$groupId);
    return redirect('/listGroup');
 }
 public function getListGroup(Request $request)
 {
     $groups=DB::select('select id,title from NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=0');
     return Response::json($groups);
 }
 public function getSearchGroups(Request $request)
 {
     $groups=DB::select("SELECT id,title FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=0");
     return Response::json($groups);
 }
 public function getGroupSearch(Request $request)
 {
     $groupId=$request->get('groupId');
     $group=DB::select('SELECT id,title FROM NewStarfood.dbo.Star_Group_Def WHERE selfGroupId=0 AND id='.$groupId);
     return Response::json($group);
 }
 public function searchGroups(Request $request)
 {
     $searchTerm=$request->get('searchTerm');
     $groups=DB::select("SELECT id,title FROM NewStarfood.dbo.Star_Group_Def WHERE title like '%".$searchTerm."%' and selfGroupId=0");
     return Response::json($groups);
 }
 public function changeGroupsPartPriority(Request $request)
    {
        $partId=$request->get('partId');
        list($groupId,$title)=explode('_',$request->get('groupId'));
        $priorityState=$request->get('priority');
        $group = DB::select("SELECT priority FROM NewStarfood.dbo.star_add_homePart_stuff WHERE firstGroupId=".$groupId." AND homepartId=".$partId);
        $groupCount = DB::select("SELECT COUNT(id) countGroup FROM NewStarfood.dbo.star_add_homePart_stuff WHERE  homepartId=".$partId);
        $countAllGroups=0;
        foreach ($groupCount as $countGroup) {
            $countAllGroups=$countGroup->countGroup;
        }
        $priority=0;
        foreach ($group  as $g) {
            $priority=$g->priority;
        }
        if ($priorityState=='up') {
            if($priority>1){
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority-1).' WHERE homepartId='.$partId.'and firstGroupId='.$groupId);
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority).' WHERE homepartId='.$partId.'and firstGroupId!='.$groupId.' and priority='.($priority-1));
            
            return redirect('/controlMainPage');
            }
        } else {
            if($priority<$countAllGroups and $priority>0){
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority+1).' WHERE homepartId='.$partId.'and firstGroupId='.$groupId);
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority).' WHERE homepartId='.$partId.'and firstGroupId!='.$groupId.' and priority='.($priority+1));
            return redirect('/controlMainPage');
            }

        }
    }
    public function getMainGroupList(Request $request)
    {
        $searchTerm=$request->get('searchTerm');
        $mainGroups=DB::select("SELECT * FROM NewStarfood.dbo.Star_Group_Def WHERE title like '%".$searchTerm."%' and selfGroupId=0");
        return Response::json($mainGroups);
    }
    public function getListOfSubGroup(Request $request)
    {
        $subGroupId=$request->get('id');
        $kalas=DB::select("SELECT Shop.dbo.PubGoods.GoodName,Shop.dbo.PubGoods.GoodSn from Shop.dbo.PubGoods 
        where companyNo=5 and not exists (select * from NewStarfood.dbo.star_add_prod_group where star_add_prod_group.product_id=GoodSn and
         star_add_prod_group.secondGroupId=$subGroupId) and CompanyNo=5 and GoodName !=''"
        );
        return Response::json($kalas);

    }
    public function changeMainGroupPriority(Request $request)
    {
        $priorityState=$request->get("priorityState");
        $groupId=$request->get("mainGrId");
        $countAllGroupsList=DB::table("NewStarfood.dbo.Star_Group_Def")->where('selfGroupId',0)->count();
        $priorities=DB::table("NewStarfood.dbo.Star_Group_Def")->where('id',$groupId)->select('mainGroupPriority')->get();
        $priority=0;
        foreach ($priorities as $pr) {
            $priority=$pr->mainGroupPriority;
        }
        if($priorityState=="top"){
            if($priority>1){
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set mainGroupPriority-=1 WHERE id='.$groupId);
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set mainGroupPriority='.($priority).' WHERE id!='.$groupId.' and mainGroupPriority='.($priority-1));
            }
        }
        if($priorityState=="down"){
            if($priority<$countAllGroupsList){
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set mainGroupPriority+=1 WHERE id='.$groupId);
                DB::update('UPDATE NewStarfood.dbo.Star_Group_Def set mainGroupPriority='.($priority).' WHERE id!='.$groupId.' and mainGroupPriority='.($priority+1));
            }
        }
        $mainGroups=DB::select("select id,title,show_hide from NewStarfood.dbo.Star_Group_Def where selfGroupId=0 order by mainGroupPriority asc");
        return Response::json($mainGroups);

    }
    public function getMainGroups(Request $request)
    {
        $groups=DB::table("NewStarfood.dbo.Star_Group_Def")->where("selfGroupId",0)->select("id",'title')->get();
        return Response::json($groups);
    }

}
