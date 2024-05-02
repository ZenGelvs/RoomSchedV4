@extends('layouts.app')

@section('title', 'Department Head Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to OCCUPIrate, Schedule Classes for the upcoming term!</h2>

        <div class="row">
            <!-- Faculty and Assigned Subjects Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Faculty and Assigned Subjects</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Faculty Name</th>
                                            <th>Assigned Subjects</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($faculties as $faculty)
                                        <tr>
                                            <td>{{ $faculty->name }}</td>
                                            <td>
                                                <ul>
                                                    @foreach($faculty->subjects as $subject)
                                                    <li>{{ $subject->Description }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Sections without Schedules Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sections without Schedules</h5>
                            <ul>
                                @foreach($sectionsWithoutSchedules as $section)
                                <li>{{ $section->program_name }} - {{ $section->section }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    
</div>
@endsection
