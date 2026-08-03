<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DispenseController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Ready to dispense!'
        ]);
    }

    
}
