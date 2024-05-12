@extends('layouts.app')

@section('title', 'Schedule')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Manage Schedules</h2>
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
    <div class="card mb-4">
        <div class="card-header" id="createScheduleHeading">
            <h4 class="text-center mb-4">Create Schedule Manually</h2>
            <h2 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#createScheduleCollapse" aria-expanded="true" aria-controls="createScheduleCollapse">
                    Create Schedule
                </button>
            </h2>
        </div>
        <div id="createScheduleCollapse" class="collapse" aria-labelledby="createScheduleHeading" data-parent="#accordion">
            <div class="card-body">
                <!-- Form to create a new schedule -->
                <form action="{{ route('department.schedule.store') }}" method="POST" id="createScheduleForm">
                    @csrf
                    <div class="form-group">
                        <label for="day">Day:</label>
                        <select class="form-control" id="day" name="day" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
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
                                    <option value="{{ $time }}">{{ $time }}</option>
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
                                    <option value="{{ $time }}">{{ $time }}</option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sectionId">Section ID:</label>
                        <select class="form-control" id="sectionId" name="sectionId" required>
                            <option value="">Select Section...</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->program_name }} - {{ $section->section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subjectId">Subject:</label>
                        <select class="form-control" id="subjectId" name="subjectId" required>
                            <option value="">Select Subject...</option>
                            @foreach($sections as $section)
                                @foreach($section->subjects as $subject)
                                    <option class="section-{{ $section->id }}-subject" value="{{ $subject->id }}" style="display: none;">{{ $subject->Description }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Type of Class:</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="">Select Class Type...</option>
                            <option value="Lecture">Lecture</option>
                            <option value="Laboratory">Laboratory</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="roomId">Room ID:</label>
                        <select class="form-control" id="roomId" name="roomId" required>
                            <option value="">Select Room...</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Create Schedule</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Automatic Scheduling using Greeedy Lagorithm-->
    <div class="card mb-4">
        <div class="card-header" id="autoScheduleHeading">
            <h4 class="text-center mb-4">Automatic Scheduling</h4>
            <h2 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#autoScheduleCollapse" aria-expanded="false" aria-controls="autoScheduleCollapse">
                    Automatic Scheduling
                </button>
            </h2>
        </div>
        <div id="autoScheduleCollapse" class="collapse" aria-labelledby="autoScheduleHeading" data-parent="#accordion">
            <div class="card-body">
                <form id="autoScheduleForm" action="{{ route('department.automatic_schedule') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="sectionSelect">Select Section to automatically schedule subjects for (Lecture Only)</label>
                        <select class="form-control" id="sectionSelect" name="section_id" required>
                            <option value="">Select Section...</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->program_name }} - {{ $section->section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferredDay">Preferred Day</label>
                        <select class="form-control" id="preferredDay" name="preferred_day">
                            <option value="Any">Any</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferredStartTime">Preferred Start Time:</label>
                        <select class="form-control" id="preferredStartTime" name="preferred_start_time" required>
                            <option value="">Select a Start time</option>
                            @for ($hour = 7; $hour <= 20; $hour++)
                                @for ($minute = 0; $minute < 60; $minute += 30)
                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                    @endphp
                                    <option value="{{ $time }}">{{ $time }}</option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferredEndTime">End Time:</label>
                        <select class="form-control" id="preferredEndTime" name="preferred_end_time" required>
                            <option value="">Select an End time</option>
                            @for ($hour = 7; $hour <= 20; $hour++)
                                @for ($minute = 0; $minute < 60; $minute += 30)
                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                    @endphp
                                    <option value="{{ $time }}">{{ $time }}</option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferredRoom">Select Room:</label>
                        <select class="form-control" id="preferredRoom" name="preferredRoom" required>
                            <option value="Any">Any</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->room_id }} {{ $room->room_name }}</option>
                            @endforeach
                        </select>
                    </div>                    
                    <button type="submit" class="btn btn-primary">Automate Scheduling</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h2 class="mb-4">View Schedule</h2>
            <form action="{{ route('department.section_schedule') }}" method="GET">
                @csrf
                <div class="form-group">
                    <label for="sectionSelect">Select Section:</label>
                    <select class="form-control" id="sectionSelect" name="section" required>
                        <option value="">Select Section...</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->program_name }} - {{ $section->section }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">View Section Schedule</button>
            </form>
            <form action="{{ route('department.faculty_schedule') }}" method="GET">
                @csrf
                <div class="form-group">
                    <label for="facultySelect">Select Faculty:</label>
                    <select class="form-control" id="facultySelect" name="faculty" required>
                        <option value="">Select Faculty...</option>
                        @foreach($faculties as $facultyOption)
                            <option value="{{ $facultyOption->id }}">{{ $facultyOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">View Faculty Schedule</button>
            </form>            
        </div>
    </div>
</div>

@endsection

@section('scripts')
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
    });
    
    document.getElementById('type').addEventListener('change', function() {
        var type = this.value; 

        var roomSelect = document.getElementById('roomId');

        roomSelect.innerHTML = '<option value="">Select Room...</option>';

        @json($rooms).forEach(function(room) {
            if (room.room_type === type) {
                var option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.room_id + ' - ' + room.room_name;
                roomSelect.appendChild(option);
            }
        });
    });

    document.getElementById('startTime').addEventListener('change', validateTime);
    document.getElementById('endTime').addEventListener('change', validateTime);

    function validateTime() {
        var startTime = document.getElementById('startTime').value;
        var endTime = document.getElementById('endTime').value;

        if (startTime >= endTime) {
            alert('Start time must be before end time.');
            this.value = '';
        }
    }
    
    const autoScheduleForm = document.getElementById('autoScheduleForm');
    const preferredStartTime = document.getElementById('preferredStartTime');
    const preferredEndTime = document.getElementById('preferredEndTime');

    autoScheduleForm.addEventListener('submit', (event) => {
        const startTime = preferredStartTime.value;
        const endTime = preferredEndTime.value;

        if (startTime >= endTime) {
            event.preventDefault();
            alert('End time must be after the start time.');
        } 
    });
</script>
@endsection
