<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use DB;
    use Session;
    use Carbon\Carbon;
    use \Morilog\Jalali\Jalalian;
    class ListFactors extends Controller{
        public function index(Request $request)
        {
            $rejectedFactors=DB::select("SELECT TOP 10 * FROM Shop.dbo.FactorHDS WHERE FactType=4 and CustomerSn=".Session::get("psn")." ORDER BY FactDate DESC");
            
            return view('factors.listFactors',['rejectedFactors'=>$rejectedFactors]);
        }
    }