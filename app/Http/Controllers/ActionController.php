<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{

    public function getScooters(Request $request)
    {
        return json_encode(DB::table('scooters')
            ->select(['id', 'num'])
            ->where('point_id', '=', $request->post('point_id'))
            ->orderBy('id')
            ->get());
    }

}
