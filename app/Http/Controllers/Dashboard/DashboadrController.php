<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboadrController extends Controller
{

    public function index()
    {
//        $user = Auth::guard('admin')->user();
        $user = Auth::user();

        return view('dashboard.index', ['user' => $user]);
    }
}
