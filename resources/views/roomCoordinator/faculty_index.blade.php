@extends('layouts.app')

@section('title', 'Faculty Index')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center mb-0">Faculty Index</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <form action="{{ route('roomCoordinator.facultySchedIndex') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search by name" name="search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Faculty ID</th>
                                <th>Name</th>
                                <th>College</th>
                                <th>Department</th>
                                <th>Assigned Subjects</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($facultyList as $faculty)
                                <tr>
                                    <td>{{ $faculty->faculty_id }}</td>
                                    <td>{{ $faculty->name }}</td>
                                    <td>{{ $faculty->college }}</td>
                                    <td>{{ $faculty->department }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($faculty->subjects as $subject)
                                                <li>{{ $subject->Description }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="{{ route('roomCoordinator.viewFacultySchedule', $faculty->id) }}" class="btn btn-primary">View Schedule</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $facultyList->links() }}
            </div>
        </div>
    </div>
@endsection
