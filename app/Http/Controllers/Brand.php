<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GoodGroups;
use Illuminate\Support\Facades\Validator;
use Response;
use Session;
class Brand extends Controller
{
    public function index(Request $request)
    {
        $brands=DB::table("NewStarfood.dbo.star_brands")->select("*")->get();
        return view("admin.listBrands",['brands'=>$brands]);
    }


    
    public function addBrand(Request $request)
    {
        $brandName=$request->post("brandName");
        $picture=$request->file("brandPic");
        $fileName="";
        if($picture){
            DB::table("NewStarfood.dbo.star_brands")->insert(['name'=>"".$brandName.""]);
            $fileName=$picture->getClientOriginalName();
            $lastBrandId=DB::table("NewStarfood.dbo.star_brands")->max('id');
            $fileName=$lastBrandId.'.jpg';
            $picture->move("resources/assets/images/brands/",$fileName);
        }
        return redirect("/brands");

    }

    public function editBrand(Request $request)
    {
        $title=$request->post("brandName");
        $brandId=$request->post("brandId");
        $picture=$request->file("brandPicture");
        if($picture){
            $fileName=$picture->getClientOriginalName();
            $fileName=$brandId.'.jpg';
            $picture->move("resources/assets/images/brands/",$fileName);
        }
        if($brandId){
            DB::update("UPDATE NewStarfood.dbo.star_brands SET name='".$title."' where id=".$brandId);
        }

        return redirect("/brands");
    }
    public function deleteBrand(Request $request)
    {
        $id=$request->post("brandId");
        DB::delete("DELETE FROM NewStarfood.dbo.star_brands WHERE id=".$id);
        if(file_exists("resources/assets/images/brands/".$id.".jpg")){
            unlink("resources/assets/images/brands/".$id.".jpg");
        }
        return redirect("/brands");
    }
    public function getBrandKala(Request $request)
    {
        $brandId=$request->get("brandId");
        $kalas=DB::select("SELECT * FROM Shop.dbo.PubGoods 
        JOIN NewStarfood.dbo.star_add_prod_brands ON PubGoods.GoodSn=NewStarfood.dbo.star_add_prod_brands.productId
        WHERE NewStarfood.dbo.star_add_prod_brands.brandId=".$brandId."
        AND PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )");

        return Response::json($kalas);
    }
    public function getKala(Request $request)
    {
        $brandId=$request->get("brandId");
        $kalas=DB::select("SELECT * FROM Shop.dbo.PubGoods WHERE GoodGroupSn>49 AND PubGoods.GoodSn not in(select productId from NewStarfood.dbo.star_GoodsSaleRestriction where hideKala=1 )");

        return Response::json($kalas);
    }
    public function addKalaToBrand(Request $request)
    {
        $addedKalas=$request->post("addedKalaToBrand");
        $removeableKalas=$request->post("removeKalaFromBrand");
        $brandId=$request->post("brandId");
        if($addedKalas){
            foreach ($addedKalas as $kala) {
                list($kalaId,$title)=explode('_',$kala);
            $countExistKala=DB::table("NewStarfood.dbo.star_add_prod_brands")->where("brandId",$brandId)->count('id');
            if($countExistKala>0){
                $maxPriority=DB::table("NewStarfood.dbo.star_add_prod_brands")->where("brandId",$brandId)->max('priority');
                $maxPriority+=1;
                DB::table("NewStarfood.dbo.star_add_prod_brands")->insert(['brandId'=>$brandId,'productId'=>$kalaId,'priority'=>$maxPriority]);
            }else{
                DB::table("NewStarfood.dbo.star_add_prod_brands")->insert(['brandId'=>$brandId,'productId'=>$kalaId,'priority'=>1]);
                }
            }
        }
        if($removeableKalas){
            foreach ($removeableKalas as $kala) {
                list($kala,$title)=explode('_',$kala);
                $proirities=DB::table("NewStarfood.dbo.star_add_prod_brands")->where("brandId",$brandId)->select("priority")->get();
                DB::table("NewStarfood.dbo.star_add_prod_brands")->where("brandId",$brandId)->where("productId",$kala)->delete();
                $priority=0;
                foreach ($proirities as $pr) {
                    $priority=$pr->priority;
                }
                DB::update("UPDATE NewStarfood.dbo.star_add_prod_brands SET priority-=1 where priority>=".$priority);
            }
        }
        return redirect("/brands");
    }
    public function getListBrand(Request $request)
    {
        $brands=DB::table("NewStarfood.dbo.star_brands")->select("*")->get();
        return Response::json($brands);
    }
    public function changeBrandPartPriority(Request $request)
    {
        $brandId=$request->get("brandId");
        $partId=$request->get("partId");
        $priorityState=$request->get('priority');

        $brands = DB::select("SELECT priority FROM NewStarfood.dbo.star_add_homePart_stuff WHERE brandId=".$brandId." and homepartId=".$partId);
        $countKala = DB::select("SELECT COUNT(id) as countKala FROM NewStarfood.dbo.star_add_homePart_stuff WHERE homepartId=".$partId);
        $countAllKala=0;
        foreach ($countKala as $countKl) {
            $countAllKala=$countKl->countKala;
        }
        $priority=0;
        foreach ($brands as $b) {
            $priority=$b->priority;
        }
        if ($priorityState=='up') {
            if($priority>1){
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority-1).' WHERE homepartId='.$partId.'and brandId='.$brandId);
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority).' WHERE homepartId='.$partId.'and brandId!='.$brandId.' and priority='.($priority-1));
            $brands=DB::select("select * from NewStarfood.dbo.star_add_homePart_stuff join NewStarfood.dbo.star_brands on star_add_homePart_stuff.brandId=star_brands.id where  homePartId=".$partId." order by priority asc");
            return Response::json($brands);
        }else{
            $brands=DB::select("select * from NewStarfood.dbo.star_add_homePart_stuff join NewStarfood.dbo.star_brands on star_add_homePart_stuff.brandId=star_brands.id where  homePartId=".$partId." order by priority asc");
            return Response::json($brands);
        }

        } else {
            if($priority<$countAllKala and $priority>0){
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority+1).' WHERE homepartId='.$partId.'and brandId='.$brandId);
            DB::update('UPDATE NewStarfood.dbo.star_add_homePart_stuff set priority='.($priority).' WHERE homepartId='.$partId.'and brandId!='.$brandId.' and priority='.($priority+1));
            $brands=DB::select("select * from NewStarfood.dbo.star_add_homePart_stuff join NewStarfood.dbo.star_brands on star_add_homePart_stuff.brandId=star_brands.id where  homePartId=".$partId." order by priority asc");
            return Response::json($brands);
            }else{
                $brands=DB::select("select * from NewStarfood.dbo.star_add_homePart_stuff join NewStarfood.dbo.star_brands on star_add_homePart_stuff.brandId=star_brands.id where  homePartId=".$partId." order by priority asc");
                return Response::json($brands);
            }
        }
    }
}
