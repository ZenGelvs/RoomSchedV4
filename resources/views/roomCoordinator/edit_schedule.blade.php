@extends('layouts.app')

@section('title', 'Edit Schedule')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center mb-4">Edit Schedule</h2>
        </div>
        <div class="card-body">
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
            <form id="editSchedForm" action="{{ route('department.schedule.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="day">Day:</label>
                    <select class="form-control" id="day" name="day" required>
                        <option value="">Select Day</option>
                        <option value="Monday" {{ old('day', $schedule->day) == 'Monday' ? 'selected' : '' }}>Monday</option>
                        <option value="Tuesday" {{ old('day', $schedule->day) == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                        <option value="Wednesday" {{ old('day', $schedule->day) == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                        <option value="Thursday" {{ old('day', $schedule->day) == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                        <option value="Friday" {{ old('day', $schedule->day) == 'Friday' ? 'selected' : '' }}>Friday</option>
                        <option value="Saturday" {{ old('day', $schedule->day) == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="startTime">Start Time:</label>
                    <select class="form-control" id="startTime" name="startTime" required>
                        <option value="">Select Start Time</option>
                        @for ($hour = 7; $hour <= 20; $hour++)
                            @for ($minute = 0; $minute < 60; $minute += 30)
                                @php
                                    $time = sprintf('%02d:%02d', $hour, $minute);
                                @endphp
                                <option value="{{ $time }}" {{ old('startTime', $schedule->start_time) == $time ? 'selected' : '' }}>{{ $time }}</option>
                            @endfor
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label for="endTime">End Time:</label>
                    <select class="form-control" id="endTime" name="endTime" required>
                        <option value="">Select End Time</option>
                        @for ($hour = 7; $hour <= 20; $hour++)
                            @for ($minute = 0; $minute < 60; $minute += 30)
                                @php
                                    $time = sprintf('%02d:%02d', $hour, $minute);
                                @endphp
                                <option value="{{ $time }}" {{ old('endTime', $schedule->end_time) == $time ? 'selected' : '' }}>{{ $time }}</option>
                            @endfor
                        @endfor
                    </select>
                    <small class="text-danger" id="endTimeError" style="display:none;">End time must be after start time.</small>
                </div>                
                <div class="form-group">
                    <label for="sectionId">Section:</label>
                    <input type="text" class="form-control" id="sectionId" name="sectionId" value="{{ $schedule->section->program_name }} - {{ $schedule->section->section }}" disabled>
                </div>                
                <div class="form-group">
                    <label for="subjectId">Subject:</label>
                    <select class="form-control" id="subjectId" name="subjectId" required>
                        <option value="">Select Subject...</option>
                        @foreach($sections as $section)
                            @if($section->id == $schedule->section_id)
                                @foreach($section->subjects as $subject)
                                    <option class="section-{{ $section->id }}-subject" value="{{ $subject->id }}" {{ old('subjectId', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->Description }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>                               
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select class="form-control" id="type" name="type" >
                        <option value="Lecture" {{ old('type', $schedule->type) == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                        <option value="Laboratory" {{ old('type', $schedule->type) == 'Laboratory' ? 'selected' : '' }}>Laboratory</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="roomId">Room:</label>
                    <select class="form-control" id="roomId" name="roomId" required>
                        <option value="">Select Room...</option>
                        @foreach($rooms as $room)
                            @if($room->room_type === $schedule->type || $room->id == $schedule->room_id)
                                <option value="{{ $room->id }}" {{ $room->id == $schedule->room_id ? 'selected' : '' }}>{{ $room->room_id }} - {{ $room->room_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>           
                <button type="submit" class="btn btn-primary">Update Schedule</button>
                <a href="{{ route('roomCoordinator.sectionScheduleIndex') }}" type="button" class="btn btn-secondary" >Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#sectionId').change(function() {
            var sectionId = $(this).val();
            if (sectionId) {
                $('#subjectId option').hide();
                $('.section-' + sectionId + '-subject').show();
            } else {
                $('#subjectId option').hide();
                $('#subjectId').find('option:first').show();
            }
        });

        document.getElementById('type').addEventListener('change', function() {
            var type = this.value;
            var roomSelect = document.getElementById('roomId');
            roomSelect.innerHTML = '<option value="">Select Room...</option>';

            @json($rooms).forEach(function(room) {
                if (room.room_type === type || room.id == {{ $schedule->room_id }}) {
                    var option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.room_id + ' - ' + room.room_name;
                    if (room.id == {{ $schedule->room_id }}) {
                        option.selected = true;
                    }
                    roomSelect.appendChild(option);
                }
            });
        });

        function checkEndTime() {
            console.log('Checking end time');
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();

            if (startTime && endTime) {
                console.log('Start Time:', startTime);
                console.log('End Time:', endTime);
                if (endTime <= startTime) {
                    $('#endTimeError').show();
                    console.log('End time is before start time');
                    return false;
                } else {
                    $('#endTimeError').hide();
                    console.log('End time is after start time');
                    return true;
                }
            }
            console.log('Start time or End time is not selected');
            return true;
        }

            $('#startTime').change(function() {
                checkEndTime();
            });

            $('#endTime').change(function() {
                checkEndTime();
            });

            $('form').submit(function() {
                return checkEndTime();
            });
        });
        function confirmUpdate() {
        if (confirm("Are you sure you want to update this room?")) {
            document.getElementById('editSchedForm').submit();
        }
    }
</script>

@endsection
