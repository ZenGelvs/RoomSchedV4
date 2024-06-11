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
        $faculties = Faculty::with(['subjects.sections'])
            ->where('college', Auth::user()->college)
            ->where('department', Auth::user()->department)
            ->get();
    
        foreach ($faculties as $faculty) {
            $faculty->total_units = $faculty->subjects->sum('Units');
        }
    
        $sectionsWithoutSchedules = Sections::with(['subjects' => function ($query) {
            $query->whereDoesntHave('schedules', function ($q) {
                $q->where('type', 'Lecture')->orWhere('type', 'Lab');
            });
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
        $yearLevel = $request->input('Year_Level');
        $semester = $request->input('Semester');
        $college = $request->input('College');
        $department = $request->input('Department');
        $program = $request->input('Program');
        $academicYear = $request->input('Academic_Year'); 

        $yearLevels = Subject::distinct()->pluck('Year_Level')->toArray();
        $semesters = Subject::distinct()->pluck('Semester')->toArray();
        $colleges = Subject::distinct()->pluck('College')->toArray();
        $departments = Subject::distinct()->pluck('Department')->toArray();
        $programs = Subject::distinct()->pluck('Program')->toArray();
        $academicYears = Subject::distinct()->pluck('Academic_Year')->toArray();

        $subjects = Subject::query();

        // Apply Search
        if ($query) {
            $subjects->where('Subject_Code', 'like', '%'.$query.'%')
                ->orWhere('Description', 'like', '%'.$query.'%');
        }

        // Apply Filters
        if ($yearLevel) {
            $subjects->where('Year_Level', $yearLevel);
        }
        if ($semester) {
            $subjects->where('Semester', $semester);
        }
        if ($college) {
            $subjects->where('College', $college);
        }
        if ($department) {
            $subjects->where('Department', $department);
        }
        if ($program) {
            $subjects->where('Program', $program);
        }
        if ($academicYear) {
            $subjects->where('Academic_Year', $academicYear);
        }

        $subjects = $subjects->paginate(9)
            ->appends([
                'search' => $query,
                'Year_Level' => $yearLevel,
                'Semester' => $semester,
                'College' => $college,
                'Department' => $department,
                'Program' => $program,
                'Academic_Year' => $academicYear
            ]);

        return view('adminDashboard', compact(
            'subjects',
            'yearLevels',
            'semesters',
            'colleges',
            'departments',
            'programs',
            'academicYears'
        ));
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
