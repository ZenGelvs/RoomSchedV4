<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Sections;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with(['subjects.sections'])->where('college', Auth::user()->college)
            ->where('department', Auth::user()->department)
            ->get();
    
        foreach ($faculties as $faculty) {
            $faculty->total_units = $faculty->subjects->sum('Units');
        }
    
        $sectionsWithoutSchedules = Sections::with(['subjects' => function ($query) {
            $query->whereHas('schedules', function ($q) {
                $q->where('type', 'Lecture')->orWhere('type', 'Lab');
            }, '<', 2);
        }])
        ->where('college', Auth::user()->college)
        ->where('department', Auth::user()->department)
        ->get();
        
        $programs = $sectionsWithoutSchedules->groupBy('program_name');

        return view('dashboard', compact('faculties', 'sectionsWithoutSchedules', 'programs'));
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

    public function roomCoordIndex(Request $request)
    {
        $search = $request->input('search');
    
        $rooms = Room::query();
        
        if ($search) {
            $rooms->where('room_id', 'like', "%$search%")
                ->orWhere('room_name', 'like', "%$search%")
                ->orWhere('room_type', 'like', "%$search%")
                ->orWhere('building', 'like', "%$search%");
        }

        $rooms = $rooms->paginate(10)->appends(['search' => $search]);
        
        return view('roomCoordinatorDashboard', compact('rooms'));
    }
}
