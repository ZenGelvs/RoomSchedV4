@extends('layouts.app')

@section('title', 'Room Schedule')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center mb-0">Room Schedule {{ $room->room_id }} - {{ $room->room_name }}</h2>
            </div>
        </div>
        <!-- Summary Table for Subjects -->
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Summary of Scheduled Subjects</h4>
                <div class="table-responsive">
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
                            @foreach ($schedules->unique('subject_id') as $schedule)
                                <tr>
                                    <td>{{ $schedule->subject->Subject_Code }}</td>
                                    <td>{{ $schedule->subject->Description }}</td>
                                    <td>
                                        @if($schedule->subject->faculty->isNotEmpty())
                                            @foreach($schedule->subject->faculty as $faculty)
                                                {{ $faculty->name }}<br>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $schedule->subject->Lec }}</td>
                                    <td>{{ $schedule->subject->Lab }}</td>
                                    <td>{{ $schedule->subject->Units }}</td>
                                    <td>
                                        @foreach ($schedules->where('subject_id', $schedule->subject_id) as $sched)
                                            {{ $sched->day }}: {{ $sched->start_time }} - {{ $sched->end_time }} (Section: {{ $schedule->section->program_name }} {{ $schedule->section->section }}) <br>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Day-wise Schedule Tables -->
        <div class="card mt-4">
            <div class="card-body">
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
                                        $colorMap = [];
                                    @endphp
                                    @foreach ($schedules->where('day', $day)->sortBy('start_time') as $schedule)
                                        @php
                                            $subjectKey = $schedule->subject->Description . $schedule->subject->Subject_Code;
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
                                                <p><strong>{{ $schedule->start_time }} - {{ $schedule->end_time }}</strong> </p>
                                                <p><strong>{{ $schedule->subject->Subject_Code }}</strong> </p>
                                                <p><strong>{{ $schedule->section->program_name}} - {{ $schedule->section->section}}</strong> </p>
                                                <p><strong>{{ $schedule->type }}</strong> </p>
                                                <a href="{{ route('department.schedule.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('department.schedule.destroy', $schedule->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @php
                                            $startTime = $schedule->end_time;
                                        @endphp
                                    @endforeach
                                    @if ($startTime < "21:00")
                                        <tr>
                                            <td style="background-color: #f2f2f2;">
                                                <p><strong>Time:</strong> {{ $startTime }} - 21:00</p>
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
