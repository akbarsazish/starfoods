<?php
// use DB;
if (! function_exists('convertYmdToMdy')) {
    function hasPermission($id,$objective)
    {
        $hasAcess=DB::select("SELECT $objective from NewStarfood.dbo.star_hasAccess1 where adminId=".$id)[0]->$objective;
        return $hasAcess;
    }
}
?>