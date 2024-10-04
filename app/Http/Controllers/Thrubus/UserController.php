<?php

namespace App\Http\Controllers\Thrubus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    public function getPool()
    {
        $data = DB::connection('mysql2')->table('pool')->select('*')->get();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function me(){
        $data = User::with('pool')->get();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}
