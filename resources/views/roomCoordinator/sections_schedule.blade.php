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
                                        <p><strong>Time:</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
                                        <p><strong>Subject Code:</strong> {{ $schedule->subject->Subject_Code }}</p>
                                        <p><strong>Subject:</strong> {{ $schedule->subject->Description }}</p>
                                        <p><strong>Type:</strong> {{ $schedule->type }}</p>
                                        <p><strong>Room:</strong> {{ $schedule->room->room_id }} {{ $schedule->room->room_name }}</p>
                                        <p><strong>Faculty:</strong> 
                                            @if($schedule->subject && $schedule->subject->faculty->isNotEmpty())
                                                @foreach($schedule->subject->faculty as $faculty)
                                                    {{ $faculty->name }},
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                        <a href="{{ route('roomCoordinator.editSchedule', $schedule->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('roomCoordinator.destroySchedule', $schedule->id) }}" method="POST" class="delete-form">
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
                            @if ($startTime < "19:00")
                                <tr>
                                    <td style="">
                                        <p><strong>Time:</strong> {{ $startTime }} - 19:00</p>
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