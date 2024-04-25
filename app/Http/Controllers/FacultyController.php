<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    public function index()
    {
        $userDepartment = Auth::user()->department;
        $faculty = Faculty::where('department', $userDepartment)->get(); 
        return view('department.faculty', compact('faculty'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty_name' => 'required|string',
            'faculty_id' => 'required',
        ]);

        $existingFaculty = Faculty::where('faculty_id', $request->faculty_id)
                                ->where('department', Auth::user()->department)
                                ->orWhere('name', $request->faculty_name)
                                ->exists();

        if ($existingFaculty) {
            return redirect()->back()->with('error', 'Faculty member with the same ID or name already exists in your department.');
        }

        Faculty::create([
            'name' => $request->faculty_name,
            'faculty_id' => $request->faculty_id,
            'college' => Auth::user()->college,
            'department' => Auth::user()->department,
        ]);

        return redirect()->back()->with('success', 'Faculty member added successfully.');
    }

    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);

        if ($faculty->department !== Auth::user()->department) {
            return redirect()->back()->with('error', 'You are not authorized to delete this faculty member.');
        }

        $faculty->delete();

        return redirect()->back()->with('success', 'Faculty member deleted successfully.');
    }

}
