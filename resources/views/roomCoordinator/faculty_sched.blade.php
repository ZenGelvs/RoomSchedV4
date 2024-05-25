@extends('layouts.app')

@section('title', 'Faculty Schedules')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center mb-0">Schedules for {{ $faculty->name }}</h2>
        </div>
    </div>
    <div class="card mt-4">
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

        <div class="card-body">
            <!-- Summary Table for Subjects -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Assigned Faculty</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>Total Units</th>
                            <th>Schedule</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subjectsGrouped = $faculty->subjects->groupBy(function($item) {
                                return $item->Subject_Code . '-' . $item->Description;
                            });
                        @endphp
                        @foreach ($subjectsGrouped as $subjectGroup)
                            @php
                                $subject = $subjectGroup->first();
                            @endphp
                            <tr>
                                <td>{{ $subject->Subject_Code }}</td>
                                <td>{{ $subject->Description }}</td>
                                <td>{{ $faculty->name }}</td>
                                <td>{{ $subject->Lec }}</td>
                                <td>{{ $subject->Lab }}</td>
                                <td>{{ $subject->Units }}</td>
                                <td>
                                    @foreach ($subjectGroup as $subj)
                                        @foreach ($subj->schedules as $schedule)
                                            {{ $schedule->day }}: {{ $schedule->start_time }} - {{ $schedule->end_time }} ({{ $schedule->room->room_id }} {{ $schedule->room->room_name }}, Section: {{ $schedule->section->program_name }} {{ $schedule->section->section }})<br>
                                        @endforeach
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Day-wise Schedule Tables -->
            <div class="row">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                    <div class="col-md-2">
                        <h5 class="text-center">{{ $day }}</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Schedule</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $startTime = "07:00";
                                    $endTime = "19:00";
                                    $colorMap = [];
                                @endphp
                                @foreach ($faculty->subjects as $subject)
                                    @foreach ($subject->schedules->where('day', $day)->sortBy('start_time') as $schedule)
                                        @php
                                            $subjectKey = $subject->Description . $subject->Subject_Code;
                                            $color = isset($colorMap[$subjectKey]) ? $colorMap[$subjectKey] : '#' . substr(md5($subjectKey), 0, 6);
                                            $colorMap[$subjectKey] = $color;
                                            $textColor = (hexdec(substr($color, 1, 2)) * 0.299 + hexdec(substr($color, 3, 2)) * 0.587 + hexdec(substr($color, 5, 2)) * 0.114) > 186 ? '#000000' : '#FFFFFF';
                                        @endphp
                                        @if ($schedule->start_time > $startTime)
                                            <tr>
                                                <td style="background-color: #f2f2f2;">
                                                    <p><strong>Time:</strong> {{ $startTime }} - {{ $schedule->start_time }}</p>
                                                    <p><em>No schedule</em></p>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="background-color: {{ $color }}; color: {{ $textColor }};">
                                                <p><strong>{{ $schedule->start_time }} - {{ $schedule->end_time }}</strong></p>
                                                <p><strong>{{ $subject->Subject_Code }}</strong></p>
                                                <p><strong>{{ $schedule->type }}</strong></p>
                                                <p><strong>{{ $schedule->room->room_id }}</strong></p>
                                                <p><strong>{{ $schedule->section->program_name }} {{ $schedule->section->section }}</strong></p>
                                            </td>
                                        </tr>
                                        @php
                                            $startTime = $schedule->end_time;
                                        @endphp
                                    @endforeach
                                @endforeach
                                @if ($startTime < $endTime)
                                    <tr>
                                        <td style="background-color: #f2f2f2;">
                                            <p><strong>Time:</strong> {{ $startTime }} - {{ $endTime }}</p>
                                            <p><em>No schedule</em></p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

