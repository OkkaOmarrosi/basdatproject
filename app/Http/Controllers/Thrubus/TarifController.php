<?php

namespace App\Http\Controllers\Thrubus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ini control

class TarifController extends Controller
{
    public function getService()
    {
        $data = DB::connection('mysql2')->table('item_layanan')->select('*')->get();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function getVehicle()
    {
        $data = DB::connection('mysql2')->table('item_layanan')->select('*')->get();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}
