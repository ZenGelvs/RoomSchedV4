<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    public function index(Request $request)
    {
        $programs = Programs::where('college', Auth::user()->college)
                    ->where('department', Auth::user()->department)
                    ->get();

        $query = Sections::where('college', Auth::user()->college)
                    ->where('department', Auth::user()->department);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('program_name', 'like', '%' . $search . '%')
                ->orWhere('year_level', 'like', '%' . $search . '%')
                ->orWhere('section', 'like', '%' . $search . '%');
            });
        }

        $sections = $query->paginate(10);

        return view('department.sections', compact('programs', 'sections'));
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

    public function editSection($id)
    {
        $section = Sections::findOrFail($id);
        $programs = Programs::where('college', Auth::user()->college)
                    ->where('department', Auth::user()->department)
                    ->get(); 
        return view('department.editSection', compact('section', 'programs'));
    }
    
    public function updateSection(Request $request, $id)
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
    
        $sectionNumber = $request->year_level . '0' . $request->section;
    
        $existingSection = Sections::where('program_name', $request->program_name)
            ->where('year_level', $yearLevel)
            ->where('section', $sectionNumber)
            ->where('id', '!=', $id)
            ->first();
    
        if ($existingSection) {
            return redirect()->back()->with('error', 'Section already exists.');
        }
    
        $section = Sections::findOrFail($id);
        $section->update([
            'program_name' => $request->program_name,
            'year_level' => $yearLevel,
            'section' => $sectionNumber, 
        ]);
    
        return redirect()->route('department.sections')->with('success', 'Section updated successfully.');
    }
}
