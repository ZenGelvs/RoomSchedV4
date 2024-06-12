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
    <!-- Automatic Scheduling using Greeedy Lagorithm-->
    <div class="card mb-4">
        <div class="card-header" id="autoScheduleHeading">
            <h4 class="text-center mb-4">Automatic Room Finder</h4>
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
                        <label for="autoSubjectId">Subject:</label>
                        <select class="form-control" id="autoSubjectId" name="subjectId" required>
                            <option value="">Select Subject...</option>
                            @foreach($sections as $section)
                                @foreach($section->subjects as $subject)
                                    <option class="section-{{ $section->id }}-subject" value="{{ $subject->id }}" data-lec-points="{{ $subject->Lec }}" style="display: none;">{{ $subject->Description }}</option>
                                @endforeach
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
                    <button type="submit" class="btn btn-primary">Automate Scheduling</button>
                </form>
            </div>
        </div>
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
        this.building = $('#building');
        this.roomId = $('#roomId');
        this.prefStartTime = $('#preferredStartTime');
        this.prefEndTime = $('#preferredEndTime');
        this.prefEndTimeError = $('#PrefendTimeError');
        this.prefBuilding = $('#preferredBuilding');
        this.prefRoom = $('#preferredRoom');
        this.sectionScheduleForm = $('#sectionScheduleForm');
        this.hiddenSectionInput = $('#hiddenSectionInput');
        this.rooms = @json($rooms);
    }

    bindEvents() {
        this.sectionId.on('change', () => this.updateSubjectOptions());
        this.autoSectionId.on('change', () => this.updateAutoSubjectOptions());
        this.type.on('change', () => this.updateRoomOptions());
        this.startTime.on('change', () => this.populateEndTimeOptions());
        this.endTime.on('change', () => this.validateEndTime());
        this.prefStartTime.on('change', () => this.populateAutoEndTimeOptions());
        this.prefEndTime.on('change', () => this.validatePrefEndTime());
        this.prefBuilding.on('change', () => this.filterRoomsByBuilding());
        this.building.on('change', () => this.filterRoomsByBuildingManual());
        $('form').on('submit', (e) => this.validateForm(e));
        $('.view-schedule-btn').on('click', (e) => this.viewSectionSchedule(e));
    }

    initializeForm() {
        this.updateRoomOptions();
        this.populateEndTimeOptions();
        this.filterRoomsByBuilding();
        this.filterRoomsByBuildlingManual();
    }

    updateSubjectOptions() {
        const sectionId = this.sectionId.val();
        this.subjectId.find('option').hide();
        if (sectionId) {
            $(`.section-${sectionId}-subject`).show();
        } else {
            this.subjectId.find('option:first').show();
        }
    }

    updateAutoSubjectOptions() {
        const sectionId = this.autoSectionId.val();
        this.autoSubjectId.find('option').hide();
        if (sectionId) {
            $(`.section-${sectionId}-subject`).show();
        } else {
            this.autoSubjectId.find('option:first').show();
        }
    }

    updateRoomOptions() {
        const classType = this.type.val();
        const roomSelect = $('#roomId');
        roomSelect.empty();
        const filteredRooms = this.rooms.filter(room => room.room_type === classType);
        filteredRooms.forEach(room => {
            roomSelect.append(`<option value="${room.id}">${room.room_id} - ${room.room_name}</option>`);
        });
        if (classType === 'Lecture') {
            this.startTime.trigger('change');
        } else {
            this.populateAllEndTimes();
        }
    }

    populateAllEndTimes() {
        this.endTime.empty();
        this.endTime.append('<option value="">Select End Time</option>');
        for (let hour = 7; hour <= 21; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                this.endTime.append(`<option value="${time}">${time}</option>`);
            }
        }
    }

    populateEndTimeOptions() {
        const startTime = this.startTime.val();
        this.endTime.empty();
        this.endTime.append('<option value="">Select End Time</option>');
        if (startTime) {
            const startParts = startTime.split(':');
            const startHour = parseInt(startParts[0]);
            const startMinute = parseInt(startParts[1]);

            for (let hour = startHour; hour <= 21; hour++) {
                for (let minute = (hour === startHour ? startMinute + 30 : 0); minute < 60; minute += 30) {
                    const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                    this.endTime.append(`<option value="${time}">${time}</option>`);
                }
            }
        }
        this.validateEndTime();
    }

    populateAutoEndTimeOptions() {
        const startTime = this.prefStartTime.val();
        this.prefEndTime.empty();
        this.prefEndTime.append('<option value="">Select End Time</option>');
        if (startTime) {
            const startParts = startTime.split(':');
            const startHour = parseInt(startParts[0]);
            const startMinute = parseInt(startParts[1]);

            for (let hour = startHour; hour <= 21; hour++) {
                for (let minute = (hour === startHour ? startMinute + 30 : 0); minute < 60; minute += 30) {
                    const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                    this.prefEndTime.append(`<option value="${time}">${time}</option>`);
                }
            }
        }
        this.validatePrefEndTime();
    }

    validateEndTime() {
        const startTime = this.startTime.val();
        const endTime = this.endTime.val();
        if (startTime && endTime && endTime <= startTime) {
            this.endTimeError.show();
            return false;
        } else {
            this.endTimeError.hide();
            return true;
        }
    }

    validatePrefEndTime() {
        const startTime = this.prefStartTime.val();
        const endTime = this.prefEndTime.val();
        if (startTime && endTime && endTime <= startTime) {
            this.prefEndTimeError.show();
            return false;
        } else {
            this.prefEndTimeError.hide();
            return true;
        }
    }

    validateForm(e) {
        if (!this.validateEndTime() || !this.validatePrefEndTime()) {
            e.preventDefault();
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

    filterRoomsByBuildingManual() {
        const selectedBuilding = this.building.val();
        this.roomId.find('option').each(function () {
            const building = $(this).data('building');
            $(this).toggle(selectedBuilding === 'Any' || building === selectedBuilding);
        });
        this.roomId.val('');
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

