@extends('layouts.app')

@section('title', 'Department Head Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to OCCUPIrate, Schedule Classes for the upcoming term!</h2>

        <div class="row">
            <!-- Faculty and Assigned Subjects Card -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
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
            <div class="col-md-4 mb-4">
                <div class="card h-100">
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
            <!-- Random Room Schedule Table -->
            @if ($randomRoom)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Random Room Schedule</h5>
                        <p><strong>Room:</strong> {{ $randomRoom->room_id }} - {{ $randomRoom->room_name }}</p>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Subject</th>
                                        <th>Class</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $startTime = "07:00";
                                        $colorMap = [];
                                    @endphp
                                    @foreach ($schedules as $schedule)
                                    @if ($schedule->start_time > $startTime)
                                    <tr>
                                        <td><em>None</em></td>
                                        <td> {{ $startTime }} - {{ $schedule->start_time }}</td>
                                        <td><em>None</em></td>
                                        <td><em>None</em></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>{{ $schedule->day }}</td>
                                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                        <td>{{ $schedule->subject->Subject_Code }} - {{ $schedule->subject->Description }}</td>
                                        <td>{{ $schedule->section->program_name }} = {{ $schedule->section->section }}</td>
                                    </tr>
                                    @php
                                        $startTime = $schedule->end_time;
                                    @endphp
                                    @endforeach
                                    @if ($startTime < "19:00")
                                        <tr>
                                            <td><em>None</em></td>
                                            <td>{{ $startTime }} - 19:00</td>
                                            <td><em>None</em></td>
                                            <td><em>None</em></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
