<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
class HomePartType extends Controller
{
    //
    public function getPartType(Request $request )
    {
        $partTypes=DB::select('SELECT * FROM NewStarfood.dbo.star_HomePart_Type');
        return Response::json($partTypes);
    }
}
