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
    
        $sections = Sections::where('college', Auth::user()->college)
                   ->where('department', Auth::user()->department)
                   ->paginate(10); 
    
        return view('department.sections', ['programs' => $programs, 'sections' => $sections]);
    }
    

    public function store(Request $request)
    {   
        $request->validate([
            'program_name' => 'required',
            'year_level' => 'required',
            'section' => 'required',
        ]);

        $yearLevel = $request->year_level;
        switch ($yearLevel) {
            case 1:
                $yearLevel = '1st';
                break;
            case 2:
                $yearLevel = '2nd';
                break;
            case 3:
                $yearLevel = '3rd';
                break;
            default:
                $yearLevel .= 'th';
                break;
        }

        $existingSection = Sections::where('program_name', $request->program_name)
            ->where('year_level', $yearLevel)
            ->where('section', $request->section)
            ->exists();

        if ($existingSection) {
            return redirect()->back()->with('error', 'Section already exists!');
        }

        Sections::create([
            'program_name' => $request->program_name,
            'year_level' => $yearLevel,
            'section' => $request->section,
            'college' => Auth::user()->college,
            'department' => Auth::user()->department,
        ]);

        return redirect()->back()->with('success', 'Section added successfully!');
    }

    public function destroy($id)
    {
        $section = Sections::findOrFail($id);
        $section->delete();

        return redirect()->back()->with('success', 'Section deleted successfully!');
    }

    public function deleteAll(Request $request)
    {
        Sections::truncate();

        return redirect()->back()->with('success', 'All sections deleted successfully!');
    }

    
}
