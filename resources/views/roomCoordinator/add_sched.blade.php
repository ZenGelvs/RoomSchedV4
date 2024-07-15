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
            <h4 class="text-center mb-4">Manual Individual Scheduling</h2>
            <h2 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#createScheduleCollapse" aria-expanded="true" aria-controls="createScheduleCollapse">
                    Create Schedule
                </button>
            </h2>
        </div>
        <div id="createScheduleCollapse" class="collapse" aria-labelledby="createScheduleHeading" data-parent="#accordion">
            <div class="card-body">
                <!-- Manual Form to create a new schedule -->
                <form action="{{ route('department.schedule.store') }}" method="POST" id="createScheduleForm">
                    @csrf
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
                                    <option class="section-{{ $section->id }}-subject" value="{{ $subject->id }}" data-lec-points="{{ $subject->Lec }} style="display: none;">{{ $subject->Description }}</option>
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
                            @for ($hour = 7; $hour <= 21; $hour++)
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
                        <small class="text-danger" id="endTimeError" style="display:none;">End time must be after start time.</small>
                    </div>
                    <div class="form-group">
                        <label for="building">Select a Building:</label>
                        <select class="form-control" id="building" name="building" required>
                            <option value="Any">Any</option>
                            <option value="COECSA">COECSA Building</option>
                            <option value="SOTERO">SPL Building</option>
                            <option value="JOSE">JPL Building</option>
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
    <!-- Room Finder using Greedy Algorithm -->
    <div class="card mb-4">
        <div class="card-header" id="autoScheduleHeading">
            <h4 class="text-center mb-4">Automatic Room Finder</h4>
            <h2 class="mb-0">
                <button class="btn btn-danger" data-toggle="collapse" data-target="#autoScheduleCollapse" aria-expanded="false" aria-controls="autoScheduleCollapse">
                    Automatic Room Finder
                </button>
            </h2>
        </div>
        <div id="autoScheduleCollapse" class="collapse" aria-labelledby="autoScheduleHeading" data-parent="#accordion">
            <div class="card-body">
                <form id="autoScheduleForm" action="{{ route('roomCoordinator.automaticSchedule') }}" method="POST">
                    @csrf
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
                        <small class="text-danger" id="PrefendTimeError" style="display:none;">End time must be after start time.</small>
                    </div>
                    <div class="form-group">
                        <label for="preferredBuilding">Select a Building:</label>
                        <select class="form-control" id="preferredBuilding" name="preferred_building" required>
                            <option value="Any">Any</option>
                            <option value="COECSA">COECSA Building</option>
                            <option value="SOTERO">SPL Building</option>
                            <option value="JOSE">JPL Building</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferredRoom">Select Room:</label>
                        <select class="form-control" id="preferredRoom" name="preferredRoom" required>
                            <option value="Any">Any</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" data-building="{{ $room->building }}">{{ $room->room_id }} {{ $room->room_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <!-- Room Found Prompt -->
        @if (isset($paginatedRooms) && $paginatedRooms->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="text-center mb-4">Available Rooms</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time Span</th>
                            <th>Room</th>
                            <th>Building</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paginatedRooms as $room)
                            <tr>
                                <td>{{ $room['day'] }}</td>
                                <td>{{ $room['start_time'] }} - {{ $room['end_time'] }}</td>
                                <td>{{ $room['room'] }}</td>
                                <td>{{ $room['building'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $paginatedRooms->appends(request()->except('page'))->links() }}
            </div>
        </div>
        @else
            <div class="alert alert-info">
                <p> No available rooms found.</p>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    class ScheduleManager {
        constructor() {
            this.init();
        }
    
        init() {
            this.cacheElements();
            this.bindEvents();
            this.initializeForm();
        }
    
        cacheElements() {
            this.sectionId = $('#sectionId');
            this.subjectId = $('#subjectId');
            this.autoSectionId = $('#sectionSelect'); 
            this.autoSubjectId = $('#autoSubjectId'); 
            this.type = $('#type');
            this.startTime = $('#startTime');
            this.endTime = $('#endTime');
            this.endTimeError = $('#endTimeError');
            this.prefStartTime = $('#preferredStartTime');
            this.prefEndTime = $('#preferredEndTime');
            this.prefEndTimeError = $('#PrefendTimeError');
            this.prefBuilding = $('#preferredBuilding');
            this.prefRoom = $('#preferredRoom');
            this.hiddenSectionInput = $('#hiddenSectionInput');
        }
    
        bindEvents() {
            this.sectionId.on('change', () => this.updateSubjectOptions(this.sectionId, this.subjectId));
            this.autoSectionId.on('change', () => this.updateSubjectOptions(this.autoSectionId, this.autoSubjectId));
            this.type.on('change', () => this.updateRoomOptions());
			this.startTime.on('change', () => this.populateEndTimeOptions(this.startTime, this.endTime));
            this.endTime.on('change', () => this.validateTime(this.startTime, this.endTime, this.endTimeError));
			this.prefStartTime.on('change', () => this.populateEndTimeOptions(this.prefStartTime, this.prefEndTime));
            this.prefEndTime.on('change', () => this.validateTime(this.prefStartTime, this.prefEndTime, this.prefEndTimeError));
            this.prefBuilding.on('change', () => this.filterRoomsByBuilding());
            $('.view-schedule-btn').on('click', (e) => this.viewSectionSchedule(e));
        }
    
        initializeForm() {
            this.updateRoomOptions();
            this.populateEndTimeOptions(this.startTime, this.endTime);
            this.filterRoomsByBuilding();
        }
        
        //Manual and Auto Sched
        updateSubjectOptions(sectionElement, subjectElement) {
            const sectionId = sectionElement.val();
            subjectElement.find('option').hide();
            subjectElement.val('');
            subjectElement.find(`.section-${sectionId}-subject`).show();
        }
    
        updateRoomOptions() {
            const classType = this.type.val();
            const roomSelect = $('#roomId');
            roomSelect.empty();
            const filteredRooms = this.userRooms.filter(room => room.room_type === classType);
            filteredRooms.forEach(room => {
                roomSelect.append(`<option value="${room.id}">${room.room_id} - ${room.room_name}</option>`);
            });
            if (classType === 'Lecture') {
                this.startTime.trigger('change');
            } else {
                this.populateAllEndTimes();
            }
        }

        populateEndTimeOptions(startTimeElement, endTimeElement) {
            const startTime = startTimeElement.val();
            endTimeElement.empty().append('<option value="">Select End Time</option>');
            if (startTime) {
                const [startHour, startMinute] = startTime.split(':').map(Number);
                let newStartHour = startHour + 2;
                let newStartMinute = startMinute;
                
                if (newStartMinute >= 60) {
                    newStartMinute -= 60;
                    newStartHour += 1;
                }
                
                for (let hour = newStartHour; hour <= 21; hour++) {
                    for (let minute = (hour === newStartHour ? newStartMinute : 0); minute < 60; minute += 30) {
                        const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                        endTimeElement.append(`<option value="${time}">${time}</option>`);
                    }
                }
            }
            this.validateTime(startTimeElement, endTimeElement, endTimeElement.next('.text-danger'));
        }

        validateTime(startTimeElement, endTimeElement, errorElement) {
            const startTime = startTimeElement.val();
            const endTime = endTimeElement.val();
            if (endTime <= startTime) {
                errorElement.show();
                endTimeElement.addClass('is-invalid');
            } else {
                errorElement.hide();
                endTimeElement.removeClass('is-invalid');
            }
        }

        updateRoomOptions() {
            const classType = this.type.val();
            const roomSelect = $('#roomId');
            roomSelect.empty();
            const filteredRooms = this.userRooms.filter(room => room.room_type === classType);
            filteredRooms.forEach(room => {
                roomSelect.append(`<option value="${room.id}">${room.room_id} - ${room.room_name}</option>`);
            });
            if (classType === 'Lecture') {
                this.startTime.trigger('change');
            } else {
                this.populateAllEndTimes();
            }
        }

        getTimeIndex(time) {
            if (!time) return -1;
            const [hours, minutes] = time.split(':').map(Number);
            return hours * 60 + minutes;
        }

        populateAllEndTimes() {
            this.endTime.empty().append('<option value="">Select End Time</option>');
            for (let hour = 7; hour <= 21; hour++) {
                for (let minute = 0; minute < 60; minute += 30) {
                    const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                    this.endTime.append(`<option value="${time}">${time}</option>`);
                }
            }
        }

        filterRoomsByBuilding() {
            const selectedBuilding = this.prefBuilding.val();
            this.prefRoom.find('option').each(function () {
                const building = $(this).data('building');
                $(this).toggle(selectedBuilding === 'Any' || building === selectedBuilding);
            });
            this.prefRoom.val('Any');
        }

        viewSectionSchedule(e) {
            const program = $(e.target).data('program');
            const sectionSelect = $(`.section-select[data-program="${program}"]`);
            const selectedSectionId = sectionSelect.val();
            if (selectedSectionId) {
                this.hiddenSectionInput.val(selectedSectionId);
                this.sectionScheduleForm.submit();
            } else {
                alert('Please select a section.');
            }
        }
    }
    
    $(document).ready(() => {
        new ScheduleManager();
    });
    </script>
@endsection

