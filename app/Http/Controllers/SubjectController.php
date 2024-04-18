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

        $import = new SubjectsImport;
        $importedData = Excel::toCollection($import, $file)->first();

        $duplicates = [];
        foreach ($importedData as $data) {

            $existingSubject = Subject::where('Subject_Code', $data['subject_code'])
                ->where('Description', $data['description'])
                ->where('Lec', $data['lec'])
                ->where('Lab', $data['lab'])
                ->where('Units', $data['units'])
                ->where('Pre_Req', $data['pre_req'])
                ->where('Year_Level', $data['year_level'])
                ->where('Semester', $data['semester'])
                ->where('College', $data['college'])
                ->where('Department', $data['department'])
                ->where('Program', $data['program'])
                ->where('Academic_Year', $data['academic_year'])
                ->first();

            if ($existingSubject) {
                $duplicates[] = $data;
            }
        }

        if (!empty($duplicates)) {
            $message = 'The following subjects already exist:';
            foreach ($duplicates as $duplicate) {
                $message .= "\n" . $duplicate['subject_code'] . ' - ' . $duplicate['description'];
            }
            return redirect()->back()->with('error', $message);
        }

        foreach ($importedData as $data) {
            Subject::create([
                'Subject_Code' => $data['subject_code'],
                'Description' => $data['description'],
                'Lec' => $data['lec'],
                'Lab' => $data['lab'],
                'Units' => $data['units'],
                'Pre_Req' => $data['pre_req'],
                'Year_Level' => $data['year_level'],
                'Semester' => $data['semester'],
                'College' => $data['college'],
                'Department' => $data['department'],
                'Program' => $data['program'],
                'Academic_Year' => $data['academic_year'],
            ]);
        }

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
    
        $subject = Subject::firstOrCreate($validatedData);
    
        if (!$subject->wasRecentlyCreated) {
            return redirect()->back()->with('error', 'A subject with the same details already exists.');
        }
        
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
