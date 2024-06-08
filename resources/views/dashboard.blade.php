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
            <!-- Sections without Schedules Card -->
            <div class="col-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Sections without Schedules</h5>
                        <ul>
                            @foreach($sectionsWithoutSchedules as $section)
                            <li>{{ $section->program_name }} - {{ $section->section }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('department.subjects') }}" class="btn btn-dark">Assign Subjects</a>
                        <a href="{{ route('department.sections') }}" class="btn btn-dark">Add Sections</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
