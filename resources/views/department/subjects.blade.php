@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')

<div class="container mt-4">
    <h2 class="text-center mb-4">Manage Subjects</h2>
    <div class="card mb-4">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card-header" id="subjectManagementHeading">
            <h5 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#subjectManagementCollapse" aria-expanded="true" aria-controls="subjectManagementCollapse">
                    Faculty Subject Management 
                </button>
            </h5>
        </div>
        <div id="subjectManagementCollapse" class="collapse show" aria-labelledby="subjectManagementHeading" data-parent="#accordion">
            <div class="card-body">
                <form action="{{ route('department.subjects') }}" method="GET" class="mb-4">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" name="search" class="form-control" placeholder="Search by Code, Description, Curriculum, Program, Semester, or Faculty" value="{{ request('search') }}">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <!-- Filter Form -->
            <h5 class="mb-0">Filter Options</h5>
                <div class="card-body">
                    <form action="{{ route('department.subjects') }}" method="GET">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Year_Level">Year Level:</label>
                                <select class="form-control" name="Year_Level" id="Year_Level">
                                    <option value="">Select Year Level</option>
                                    @foreach ($yearLevels as $level)
                                        <option value="{{ $level }}">{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Semester">Semester:</label>
                                <select class="form-control" name="Semester" id="Semester">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester }}">{{ $semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="College">College:</label>
                                <select class="form-control" name="College" id="College">
                                    <option value="">Select College</option>
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college }}">{{ $college }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Department">Department:</label>
                                <select class="form-control" name="Department" id="Department">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department }}">{{ $department }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Program">Program:</label>
                                <select class="form-control" name="Program" id="Program">
                                    <option value="">Select Program</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program }}">{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Academic_Year">Curriculum:</label>
                                <select class="form-control" name="Academic_Year" id="Academic_Year">
                                    <option value="">Select Curriculum</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>
                        </div>
                    </form>  
                </div>

                <div class="table-responsive">
                    @if($subjects->isEmpty())
                        <p>No subjects found.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Description</th>
                                    <th>Lecture Hours</th>
                                    <th>Lab Hours</th>
                                    <th>Units</th>
                                    <th>Year Level</th>
                                    <th>Semester</th>
                                    <th>Program</th>
                                    <th>Curriculum</th>
                                    <th>Assigned Faculty</th>
                                    <th>Assign a Faculty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->Subject_Code }}</td>
                                        <td>{{ $subject->Description }}</td>
                                        <td>{{ $subject->Lec }}</td>
                                        <td>{{ $subject->Lab }}</td>
                                        <td>{{ $subject->Units }}</td>
                                        <td>{{ $subject->Year_Level }}</td>
                                        <td>{{ $subject->Semester }}</td>
                                        <td>{{ $subject->Program }}</td>
                                        <td>{{ $subject->Academic_Year }}</td>
                                        <td>
                                            @if($subject->faculty->isEmpty())
                                                None
                                            @else
                                                @foreach($subject->faculty as $facultyMember)
                                                    {{ $facultyMember->name }}
                                                    <form action="{{ route('department.removeFaculty', ['subject' => $subject->id, 'faculty' => $facultyMember->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmUnassign()">Remove</button>
                                                    </form>                                    
                                                @endforeach
                                            @endif
                                        </td>                            
                                        <td>
                                            <form action="{{ route('department.assignFaculty', $subject->id) }}" method="POST">
                                                @csrf
                                                <select name="faculty_id" class="form-control">
                                                    <option value="">Select a Faculty</option>
                                                    @foreach($faculty as $facultyMember)
                                                        <option value="{{ $facultyMember->id }}">{{ $facultyMember->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-primary">Assign Faculty</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="d-flex justify-content-center">
                    {{ $subjects->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="assignSubjectsHeading">
            <h5 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#assignSubjectsCollapse" aria-expanded="true" aria-controls="assignSubjectsCollapse">
                    Section Subject Management
                </button>
            </h5>
        </div>
        <div id="assignSubjectsCollapse" class="collapse" aria-labelledby="assignSubjectsHeading" data-parent="#accordion">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Program Name</th>
                            <th>Section</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $programName => $programSections)
                            <tr>
                                <td>{{ $programName }}</td>
                                <td>
                                    <select name="section_id" class="form-control" id="section-select-{{ $programName }}">
                                        @foreach($programSections as $section)
                                            <option value="{{ $section->id }}">{{ $section->section }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="submitForm('{{ $programName }}')">Assign Subjects</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        function confirmUnassign() {
            return confirm("Are you sure you want to unassign this Faculty from this Subject?");
        }

        function submitForm(programName) {
            const selectElement = document.getElementById('section-select-' + programName);
            const sectionId = selectElement.value;
            const url = `{{ route('department.assignSubjects') }}?section_id=${sectionId}`;
            window.location.href = url;
        }
    </script>
@endsection
@endsection
