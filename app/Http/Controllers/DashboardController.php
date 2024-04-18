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

    public function adminIndex(Request $request)
    {
        $query = $request->input('search');
        $subjects = Subject::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('Subject_Code', 'like', '%'.$query.'%')
                        ->orWhere('Description', 'like', '%'.$query.'%');
        })->paginate(9);
        
        return view('adminDashboard', compact('subjects'));
    }

    public function roomCoordIndex()
    {
        return view('roomCoordinatorDashboard');
    }
}
