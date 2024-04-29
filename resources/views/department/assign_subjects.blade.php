@extends('layouts.app')

@section('title', 'Assign Subjects')

@section('content')

<div class="container mt-4">
    <h2 class="text-center mb-4">Assign Subjects for {{ $programName }}, {{ $yearLevel }} Year and {{$sectionId}}ID</h2>
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
    <div class="card mb-4">
        <div class="card-header" id="assignSubjectsHeading">
            <h5 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#assignSubjectsCollapse" aria-expanded="true" aria-controls="assignSubjectsCollapse">
                    Assign Subjects
                </button>
            </h5>
            
        </div>
        <div id="assignSubjectsCollapse" class="collapse show" aria-labelledby="assignSubjectsHeading" data-parent="#accordion">
            <div class="card-body">
                <div class="table-responsive">
                    <form action="{{ route('department.assign.subjects') }}" method="POST">
                        @csrf
                        <input type="hidden" name="section_id" value="{{ $sectionId ?? '' }}">
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
                                    <th>Select Subjects</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableSubjects as $subject)
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
                                            @endforeach
                                        @endif
                                    </td>     
                                    <td>
                                        <input type="checkbox" name="subject[]" value="{{ $subject->id }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Assign Selected Subjects</button>
                    </form>
                </div>        
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="assignedSubjectsHeading">
            <h5 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#assignedSubjectsCollapse" aria-expanded="true" aria-controls="assignedSubjectsCollapse">
                    Assigned Subjects
                </button>
            </h5>
        </div>
        <div id="assignedSubjectsCollapse" class="collapse" aria-labelledby="assignedSubjectsHeading" data-parent="#accordion">
            <div class="card-body">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedSubjects as $subject)
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
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('department.unassign.subject', ['subject' => $subject->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to unassign this subject?')">Unassign</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
