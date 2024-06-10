@extends('layouts.app')

@section('title', 'Section Schedules')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center mb-0">Schedules for {{ $section->program_name }} - {{ $section->section }}</h2>
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
                        @foreach ($subjects as $subject)
                            <tr>
                                <td>{{ $subject->Subject_Code }}</td>
                                <td>{{ $subject->Description }}</td>
                                <td>
                                    @if($subject->faculty->isNotEmpty())
                                        @foreach($subject->faculty as $faculty)
                                            {{ $faculty->name }},
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $subject->Lec }}</td>
                                <td>{{ $subject->Lab }}</td>
                                <td>{{ $subject->Units }}</td>
                                <td>
                                    @foreach ($subject->schedules as $schedule)
                                        {{ $schedule->day }}: {{ $schedule->start_time }} - {{ $schedule->end_time }} ({{ $schedule->room->room_id }} {{ $schedule->room->room_name }})<br>
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
                                        <td style="">
                                            <p><strong>Time:</strong> {{ $startTime }} - {{ $schedule->start_time }}</p>
                                            <p><em>No schedule</em></p>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="background-color: {{ $color }}; color: {{ $textColor }};">
                                        <p><strong>{{ $schedule->start_time }} - {{ $schedule->end_time }}</strong></p>
                                        <p><strong>{{ $schedule->subject->Subject_Code }}</strong></p>
                                        <p><strong>{{ $schedule->type }}</strong> </p>
                                        <p><strong>{{ $schedule->room->room_id }}</strong></p>
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
                                    <td style="">
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