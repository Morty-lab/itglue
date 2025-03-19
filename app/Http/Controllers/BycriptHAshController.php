<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BycriptHAshController extends Controller
{

    public function hashPassword(Request $request)
    {
        $password = $request->query('password');

        $hashedPassword = bcrypt($password);


        return response()->json(['password' => $hashedPassword]);
    }
}
