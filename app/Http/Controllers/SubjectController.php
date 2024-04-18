<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Imports\SubjectsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


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

        if (!$importedData) {
            return redirect()->back()->with('error', 'Failed to import data from the Excel file.');
        }

        $validationErrors = $this->validateImportedData($importedData);

        if ($validationErrors->count() > 0) {
            return redirect()->back()->withErrors(['excelFile' => $validationErrors])->withInput();
        }

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

    private function validateImportedData($importedData)
    {
        $errors = collect();

        foreach ($importedData as $index => $data) {
            if (!isset($data['subject_code']) || empty($data['subject_code'])) {
                $errors->push("Row {$index}: Subject code is required.");
            }

            if (!isset($data['description']) || empty($data['description'])) {
                $errors->push("Row {$index}: Description is required.");
            }

            if (!isset($data['lec']) || !is_numeric($data['lec']) || $data['lec'] < 0) {
                $errors->push("Row {$index}: Lec must be a non-negative numeric value.");
            }

            if (!isset($data['lab']) || !is_numeric($data['lab']) || $data['lab'] < 0) {
                $errors->push("Row {$index}: Lab must be a non-negative numeric value.");
            }

            if (!isset($data['units']) || !is_numeric($data['units']) || $data['units'] <= 0) {
                $errors->push("Row {$index}: Units must be a positive numeric value.");
            }

            if (!isset($data['pre_req']) || !is_string($data['pre_req'])) {
                $errors->push("Row {$index}: Pre-Requisite must be a string.");
            }

            if (!isset($data['year_level']) || empty($data['year_level'])) {
                $errors->push("Row {$index}: Year Level is required.");
            }

            if (!isset($data['semester']) || empty($data['semester'])) {
                $errors->push("Row {$index}: Semester is required.");
            }

            if (!isset($data['college']) || empty($data['college'])) {
                $errors->push("Row {$index}: College is required.");
            }

            if (!isset($data['department']) || empty($data['department'])) {
                $errors->push("Row {$index}: Department is required.");
            }

            if (!isset($data['program']) || empty($data['program'])) {
                $errors->push("Row {$index}: Program is required.");
            }

            if (!isset($data['academic_year']) || empty($data['academic_year'])) {
                $errors->push("Row {$index}: Academic Year is required.");
            }
        }

        return $errors;
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

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.edit_subject', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Subject_Code' => 'required',
            'Description' => 'required',
            'Lec' => 'required|numeric|min:0',
            'Lab' => 'required|numeric|min:0',
            'Units' => 'required|numeric|min:0',
            'Pre_Req' => 'required',
            'Year_Level' => 'required|in:1st,2nd,3rd,4th', 
            'Semester' => 'required|in:1,2,Summer', 
            'College' => 'required|in:COECSA,CAMS,CAS,CBA,CFAD,CITHM,NURSING', 
            'Department' => 'required',
            'Program' => 'required',
            'Academic_Year' => 'required',
        ]);
        
        $subject = Subject::find($id);

        $existingSubject = Subject::where('Subject_Code', $request->input('Subject_Code'))
                                    ->where('Description', $request->input('Description'))
                                    ->where('Pre_Req', $request->input('Pre_Req'))
                                    ->where('Year_Level', $request->input('Year_Level'))
                                    ->where('Semester', $request->input('Semester'))
                                    ->where('College', $request->input('College'))
                                    ->where('Department', $request->input('Department'))
                                    ->where('Program', $request->input('Program'))
                                    ->where('Academic_Year', $request->input('Academic_Year'))
                                    ->where('id', '!=', $id) 
                                    ->first();

        if ($existingSubject) {
            $errorMessage = 'A subject with the same data already exists.';
            return redirect()->route('dashboard.adminIndex')->withErrors(['error' => $errorMessage])->with('subjectError', $errorMessage);
        }
        
        $subject->update($request->all());

        return redirect()->route('dashboard.adminIndex')->with('success', 'Subject updated successfully!');
    }
    
}
