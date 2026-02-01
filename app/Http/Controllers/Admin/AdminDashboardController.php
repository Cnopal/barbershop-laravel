<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
     public function index()
    {
        // Boleh return view dashboard admin
        return view('admin.dashboard');
    }
}
