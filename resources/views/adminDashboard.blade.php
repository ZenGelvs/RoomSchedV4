@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to Subjects Record, manage subjects for the Term</h2>

        <!-- Search and Filter Form (Combined) -->
        <div class="row mb-6">
            <div class="col-md-8">
                <form action="{{ route('dashboard.adminIndex') }}" method="GET" class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search by Subject Name or Code" aria-label="Search" name="search">
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
            <div class="col-md-4">
                <form id="deleteAllForm" action="{{ route('admin.subjects.deleteAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete()" class="btn btn-danger float-right">Delete All</button>
                </form>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Filter Options</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.adminIndex') }}" method="GET">
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
                </div>
            </div>         
        </div>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
        
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Subjects Table -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                        <tr>
                            <td>{{ $subject->Subject_Code }}</td>
                            <td>{{ $subject->Description }}</td>
                            <td>
                                <a href="{{ route('admin.subjects.view', $subject->id) }}" class="btn btn-success">View Subject</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No subjects found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $subjects->links() }}
        </div>
    </div>
</div>
@endsection

<script>
    function confirmDelete() {
        if (confirm("Are you sure you want to delete ALL the Subjects in the Table?")) {
            document.getElementById('deleteAllForm').submit();
        }
    }
</script>
