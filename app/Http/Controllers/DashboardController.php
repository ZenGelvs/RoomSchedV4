<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function adminIndex()
    {
        $subjects = Subject::paginate(12); 
        return view('adminDashboard', compact('subjects'));
    }
    public function roomCoordIndex()
    {
        return view('roomCoordinatorDashboard');
    }
}
