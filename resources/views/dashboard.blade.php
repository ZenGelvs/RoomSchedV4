@extends('layouts.app')

@section('title', 'Department Head Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to OCCUPIrate, Schedule Classes for the upcoming term!</h2>

        <div class="row">
            <!-- Faculty and Assigned Subjects Card -->
            <div class="col-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Faculty and Assigned Subjects</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Faculty Name</th>
                                        <th>Assigned Subjects with Sections</th>
                                        <th>Total Units of Assigned Subjects</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faculties as $faculty)
                                    <tr>
                                        <td>{{ $faculty->name }}</td>
                                        <td>
                                            <ul>
                                                @foreach($faculty->subjects as $subject)
                                                    @foreach($subject->sections as $section)
                                                        <li>{{ $subject->Subject_Code }} - {{ $section->program_name }} - {{ $section->section }}</li>
                                                    @endforeach
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $faculty->total_units }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('department.faculty') }}" class="btn btn-dark">Add Faculty</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sections and Subjects without Schedules Card -->
            <div class="col-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Sections and Subjects without Schedules</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Program Name</th>
                                        <th>Section</th>
                                        <th>Subject Code</th>
                                        <th>Description</th>
                                        <th>Missing Lecture</th>
                                        <th>Missing Lab</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sectionsWithoutSchedules as $section)
                                        @foreach($section->subjects as $subject)
                                            @php
                                                $hasLecture = $subject->schedules->where('type', 'Lecture')->count();
                                                $hasLab = $subject->schedules->where('type', 'Lab')->count();
                                            @endphp
                                            <tr>
                                                <td>{{ $section->program_name }}</td>
                                                <td>{{ $section->section }}</td>
                                                <td>{{ $subject->Subject_Code }}</td>
                                                <td>{{ $subject->Description }}</td>
                                                <td>
                                                    @if($subject->Lec > 0 && !$hasLecture)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($subject->Lab > 0 && !$hasLab)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('department.subjects') }}" class="btn btn-dark">Assign Subjects</a>
                        <a href="{{ route('department.sections') }}" class="btn btn-dark">Add Sections</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
