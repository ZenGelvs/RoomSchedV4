<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SubjectsImport;

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
        // Handle manual subject creation logic here

        // Redirect back with success message
        return redirect()->back()->with('success', 'Subject added successfully!');
    }
}
