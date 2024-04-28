@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Manage Subjects</h2>
        <form action="{{ route('department.subjects') }}" method="GET" class="mb-4">
            <div class="form-row">
                <div class="col">
                    <input type="text" name="search" class="form-control" placeholder="Search by Subject Name or Code" value="{{ request('search') }}">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
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
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Description</th>
                        <th>Lecture Hours</th>
                        <th>Lab Hours</th>
                        <th>Units</th>
                        <th>Pre-Requisites</th>
                        <th>Year Level</th>
                        <th>Semester</th>
                        <th>Program</th>
                        <th>Academic Year</th>
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
                            <td>{{ $subject->Pre_Req }}</td>
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
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
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
        </div>
        
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $subjects->links() }}
        </div>
    </div>
@endsection
