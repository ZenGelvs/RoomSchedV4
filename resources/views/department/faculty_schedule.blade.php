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
                        @foreach ($faculty->subjects->groupBy('Subject_Code') as $subjectCode => $subjectGroup)
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
            <!-- Weekly Schedule Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered schedule-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $timeSlots = [];
                            $startTime = strtotime('07:00');
                            $endTime = strtotime('21:00');
                            while ($startTime <= $endTime) {
                                $timeSlots[] = date('H:i', $startTime);
                                $startTime = strtotime('+30 minutes', $startTime);
                            }
                            $schedulesByDay = $schedules->groupBy('day');
                        @endphp
                        @foreach ($timeSlots as $timeSlot)
                            <tr>
                                <td>{{ $timeSlot }}</td>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    @php
                                        $scheduleForDay = $schedulesByDay->get($day)?->firstWhere('start_time', $timeSlot);
                                    @endphp
                                    @if ($scheduleForDay && $scheduleForDay->start_time == $timeSlot)
                                        @php
                                            $start = strtotime($scheduleForDay->start_time);
                                            $end = strtotime($scheduleForDay->end_time);
                                            $duration = ($end - $start) / 1800;
                                            $subjectKey = $scheduleForDay->subject->Description . $scheduleForDay->subject->Subject_Code;
                                            $color = $colorMap[$subjectKey] ?? '#' . substr(md5($subjectKey), 0, 6);
                                            $colorMap[$subjectKey] = $color;
                                            $textColor = (hexdec(substr($color, 1, 2)) * 0.299 + hexdec(substr($color, 3, 2)) * 0.587 + hexdec(substr($color, 5, 2)) * 0.114) > 186 ? '#000000' : '#FFFFFF';
                                        @endphp
                                        <td rowspan="{{ $duration }}" style="background-color: {{ $color }}; color: {{ $textColor }};">
                                            <div class="cell-content">
                                                <p><strong>{{ $scheduleForDay->subject->Subject_Code }}</strong></p>
                                                <p><strong>{{ $scheduleForDay->type }}</strong> </p>
                                                <p><strong>{{ $scheduleForDay->room->room_id }}</strong></p>
                                                <a href="{{ route('department.schedule.edit', $scheduleForDay->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('department.schedule.destroy', $scheduleForDay->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @elseif (!$schedulesByDay->get($day)?->where('start_time', '<=', $timeSlot)->where('end_time', '>', $timeSlot)->count())
                                        <td><div class="cell-content"></div></td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(item => {
        item.addEventListener('click', event => {
            if (!confirm('Are you sure you want to delete this schedule?')) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection

<style>
    .schedule-table th, .schedule-table td {
        text-align: center;
        vertical-align: middle;
        word-wrap: break-word;
        white-space: normal;
        overflow: hidden;
        height: 80px; /* fixed height for cells */
        width: 150px; /* fixed width for cells */
    }
    .schedule-table th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
    }
    .cell-content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
        text-align: center;
        flex-direction: column;
    }
</style>