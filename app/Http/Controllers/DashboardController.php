<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function adminIndex()
    {
        return view('adminDashboard');
    }
    public function roomCoordIndex()
    {
        return view('roomCoordinatorDashboard');
    }
}
