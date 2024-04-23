<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    public function index()
    {
        $programs = Programs::where('college', Auth::user()->college)
                   ->where('department', Auth::user()->department)
                   ->get();

        return view('department.sections', ['programs' => $programs]);
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'program_name' => 'required',
            'year_level' => 'required',
            'section' => 'required',
        ]);

        // Save the section to the database
        Sections::create([
            'program_name' => $request->program_name,
            'year_level' => $request->year_level,
            'section' => $request->section,
            'college' => Auth::user()->college,
            'department' => Auth::user()->department,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Section added successfully!');
    }
}
