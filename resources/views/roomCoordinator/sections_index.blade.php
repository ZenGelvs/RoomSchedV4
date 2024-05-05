@extends('layouts.app')

@section('title', 'Section Schedule Index')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center mb-0">Section Schedule Index</h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="{{ route('roomCoordinator.sectionScheduleIndex') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search...">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Program Name</th>
                                <th>Year Level</th>
                                <th>Section</th>
                                <th>College</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sections as $section)
                                <tr>
                                    <td>{{ $section->program_name }}</td>
                                    <td>{{ $section->year_level }}</td>
                                    <td>{{ $section->section }}</td>
                                    <td>{{ $section->college }}</td>
                                    <td>{{ $section->department }}</td>
                                    <td>
                                        <a href="{{ route('roomCoordinator.viewSectionSchedule', $section->id) }}" class="btn btn-primary">View Schedule</a>                                    
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
