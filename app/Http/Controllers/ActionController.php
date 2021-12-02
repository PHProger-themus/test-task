<?php

namespace App\Http\Controllers;

use App\Models\Scooter;
use Illuminate\Http\Request;

class ActionController extends Controller
{

    public function getScooters(Request $request)
    {
        return json_encode(Scooter::select('id', 'num')
            ->where('point_id', '=', $request->post('point_id'))
            ->orderBy('id')
            ->get());
    }

}
