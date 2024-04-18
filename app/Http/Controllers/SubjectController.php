<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Imports\SubjectsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;


class SubjectController extends Controller
{
    public function index()
    {
        return view('admin.manage_subjects');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excelFile');

        Excel::import(new SubjectsImport, $file);

        return redirect()->back()->with('success', 'Subjects imported successfully!');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Subject_Code' => 'required|string',
            'Description' => 'required|string',
            'Lec' => 'required|integer',
            'Lab' => 'required|integer',
            'Units' => 'required|integer',
            'Pre_Req' => 'nullable|string',
            'Year_Level' => 'required|string',
            'Semester' => 'required|string',
            'College' => 'required|string',
            'Department' => 'required|string',
            'Program' => 'required|string',
            'Academic_Year' => 'required|string',
        ]);

        $subject = new Subject($validatedData);

        $subject->save();

        return redirect()->back()->with('success', 'Subject added successfully!');
    }

    public function deleteAll()
    {
        Subject::truncate();

        return redirect()->back()->with('success', 'All subjects have been deleted successfully.');
    }

    public function delete($id)
    {
        Subject::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Subject has been deleted successfully.');
    }
}
