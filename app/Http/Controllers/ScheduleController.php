<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Sections;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ScheduleController extends Controller
{
    public function index()
    {
        $rooms = Room::where('room_type', 'Lecture')->get();
    
        $userRooms = Auth::user()->rooms()->get();
    
        $sections = Sections::with('subjects')
                            ->where('college', Auth::user()->college)
                            ->where('department', Auth::user()->department)
                            ->orderBy('program_name')
                            ->orderBy('year_level')
                            ->get(); 
    
        $faculties = Faculty::where('college', Auth::user()->college)
                            ->where('department', Auth::user()->department)
                            ->get(); 
    
        return view('department.schedules', compact('rooms', 'userRooms', 'sections', 'faculties'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'sectionId' => 'required',
            'subjectId' => 'required',
            'type' => 'required',
            'day' => 'required|string',
            'startTime' => 'required',
            'endTime' => 'required',
            'roomId' => 'required',
        ]);

        $existingSchedule = Schedules::where('day', $request->day)
            ->where('start_time', $request->startTime)
            ->where('end_time', $request->endTime)
            ->where('section_id', $request->sectionId)
            ->where('subject_id', $request->subjectId)
            ->where('type', $request->type)
            ->where('room_id', $request->roomId)
            ->exists();

        if ($existingSchedule) {
            return redirect()->back()->with('error', 'A schedule with the same details already exists.');
        }

        $overlappingSchedule = Schedules::where('day', $request->day)
            ->where('section_id', $request->sectionId)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->endTime)
                    ->where('end_time', '>', $request->startTime);
            })
            ->exists();

        if ($overlappingSchedule) {
            return redirect()->back()->with('error', 'There is an overlapping schedule for this section.');
        }

        $overlappingRoomSchedule = Schedules::where('day', $request->day)
            ->where('room_id', $request->roomId)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->endTime)
                    ->where('end_time', '>', $request->startTime);
            })
            ->exists();

        if ($overlappingRoomSchedule) {
            return redirect()->back()->with('error', 'There is an overlapping schedule for the selected room and time slot.');
        }
        
        $subject = Subject::find($request->subjectId);
        $facultyIds = $subject->faculty->pluck('id');

        $overlappingFacultySchedule = Schedules::where('day', $request->day)
            ->whereIn('subject_id', function ($query) use ($facultyIds) {
                $query->select('subject_id')
                    ->from('subject_faculty')
                    ->whereIn('faculty_id', $facultyIds);
            })
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->endTime)
                    ->where('end_time', '>', $request->startTime);
            })
            ->exists();

        if ($overlappingFacultySchedule) {
            return redirect()->back()->with('error', 'There is an overlapping schedule for the faculty assigned to this subject.');
        }
        
        Schedules::create([
            'day' => $request->day,
            'start_time' => $request->startTime,
            'end_time' => $request->endTime,
            'section_id' => $request->sectionId,
            'subject_id' => $request->subjectId,
            'type' => $request->type,
            'room_id' => $request->roomId,
            'college' => Auth::user()->college,
            'department' => Auth::user()->department,
        ]);

        return redirect()->back()->with('success', 'Schedule created successfully.');
    }
    
    public function ScheduleIndex(Request $request)
    {
        $sectionId = $request->input('section');
        $section = Sections::findOrFail($sectionId);
        $schedules = $section->schedules()->with('subject', 'room')->get();
        $subjects = $section->subjects()->with(['schedules' => function($query) use ($sectionId) {
            $query->where('section_id', $sectionId);
        }, 'faculty'])->get();
    
        return view('department.section_schedule', compact('section', 'schedules', 'subjects'));
    }

    public function destroy(Schedules $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Schedule deleted successfully');
    }

    public function FacultySchedule(Request $request)
    {
        $facultyId = $request->input('faculty');
        $faculty = Faculty::findOrFail($facultyId); 

        $schedules = collect();
        foreach ($faculty->subjects as $subject) {
            foreach ($subject->schedules as $schedule) {
                $section = $subject->sections()->first();
                if ($section) {
                    $schedule->section = $section;
                }
                $schedules->push($schedule);
            }
        }

        return view('department.faculty_schedule', compact('faculty', 'schedules'));
    }

    public function EditSchedule(Schedules $schedule)
    {
        $schedule->load('section');

        $sections = Sections::with('subjects')->get();
        $rooms = Auth::user()->rooms()->get();

        return view('department.edit_schedule', compact('schedule', 'rooms', 'sections'));
    }

    public function UpdateSchedule(Request $request, Schedules $schedule)
    {
        $request->validate([
            'day' => 'required|string',
            'startTime' => 'required',
            'endTime' => 'required',
            'subjectId' => 'required',
            'type' => 'required',
            'roomId' => 'required',
        ]);

        $existingSchedule = Schedules::where('day', $request->day)
            ->where('start_time', $request->startTime)
            ->where('end_time', $request->endTime)
            ->where('section_id', $schedule->section_id) 
            ->where('subject_id', $request->subjectId)
            ->where('type', $request->type)
            ->where('room_id', $request->roomId)
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($existingSchedule) {
            return redirect()->back()->with('error', 'A schedule with the same details already exists.');
        }
        
        $overlappingSchedule = Schedules::where('day', $request->day)
            ->where('section_id', $schedule->section_id) 
            ->where('id', '!=', $schedule->id) 
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->endTime)
                    ->where('end_time', '>', $request->startTime);
            })
            ->exists();

        if ($overlappingSchedule ) {
            return redirect()->back()->with('error', 'There is an overlapping schedule for the section.');
        }

        $overlappingRoomSchedule = Schedules::where('day',  $request->day)
                    ->where('start_time', '<',  $request->endTime)
                    ->where('end_time', '>', $request->startTime)
                    ->where('room_id', $request->roomId)
                    ->exists();

        if ($overlappingRoomSchedule) {
            return redirect()->back()->with('error', 'There is an overlapping schedule for the selected room and time slot.');
        }

        $subject = Subject::find($request->subjectId);
        $facultyIds = $subject->faculty->pluck('id');

        $overlappingFacultySchedule = Schedules::where('day', $request->day)
            ->whereIn('subject_id', function ($query) use ($facultyIds) {
                $query->select('subject_id')
                    ->from('subject_faculty')
                    ->whereIn('faculty_id', $facultyIds);
            })
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->endTime)
                    ->where('end_time', '>', $request->startTime);
            })
            ->exists();

        if ($overlappingFacultySchedule) {
            return redirect()->back()->with('error', 'There is an overlapping schedule for the faculty assigned to this subject.');
        }

        $schedule->update([
            'day' => $request->day,
            'start_time' => $request->startTime,
            'end_time' => $request->endTime,
            'subject_id' => $request->subjectId,
            'type' => $request->type,
            'room_id' => $request->roomId,
            'college' => Auth::user()->college,
            'department' => Auth::user()->department,
        ]);

        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }

    public function automaticSchedule(Request $request)
    {
        $request->validate([
            'preferred_start_time' => 'required',
            'preferred_end_time' => 'required',
            'preferred_building' => 'required',
            'preferred_day' => 'required',
        ]);

        $preferredStartTime = $request->preferred_start_time;
        $preferredEndTime = $request->preferred_end_time;
        $preferredBuilding = $request->preferred_building;

        // Retrieve rooms and filter by preferred room and building
        $roomsQuery = Room::where('room_type', 'Lecture');

        if ($request->preferredRoom !== 'Any') {
            $roomsQuery->where('id', $request->preferredRoom);
        }

        if ($preferredBuilding !== 'Any') {
            $roomsQuery->where('building', $preferredBuilding);
        }

        $rooms = $roomsQuery->get();

        // Determine preferred days to iterate through
        $daysOfWeek = ($request->preferred_day !== 'Any') ? [$request->preferred_day] : ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $availableRooms = collect();

        foreach ($daysOfWeek as $day) {
            foreach ($rooms as $room) {
                $scheduledSlots = Schedules::where('room_id', $room->id)
                                            ->where('day', $day)
                                            ->orderBy('start_time')
                                            ->get();
                $result = $this->findAvailableSlot($room, $day, $preferredStartTime, $preferredEndTime, $scheduledSlots);

                if ($result['slot']) {
                    $availableRooms->push([
                        'day' => $day,
                        'start_time' => $result['slot']['start_time'],
                        'end_time' => $result['slot']['end_time'],
                        'room_id' => $room->id,
                        'room' => $room->room_id,
                        'building' => $room->building,
                    ]);
                }
            }
        }

        // Store paginated results in session
        $request->session()->put('availableRooms', $availableRooms);

        // Redirect to the results page
        return redirect()->route('department.show_automatic_schedule');
    }

    private function findAvailableSlot($room, $day, $preferredStartTime, $preferredEndTime, $scheduledSlots)
    {
        foreach ($scheduledSlots as $slot) {
            if (strtotime($preferredStartTime) >= strtotime($slot->end_time) || strtotime($preferredEndTime) <= strtotime($slot->start_time)) {
                return [
                    'slot' => [
                        'start_time' => $preferredStartTime,
                        'end_time' => $preferredEndTime,
                        'room_id' => $room->id,
                    ],
                    'reason' => null,
                ];
            }
        }

        return [
            'slot' => [
                'start_time' => $preferredStartTime,
                'end_time' => $preferredEndTime,
                'room_id' => $room->id,
            ],
            'reason' => null,
        ];
    }

    private function paginateAvailableRooms($availableRooms, $page)
    {
        $perPage = 5;

        return new LengthAwarePaginator(
            $availableRooms->forPage($page, $perPage),
            $availableRooms->count(),
            $perPage,
            $page,
            ['path' => route('department.show_automatic_schedule'), 'query' => request()->query()]
        );
    }

    public function showAutomaticSchedule(Request $request)
    {
        $availableRooms = $request->session()->get('availableRooms');
        $page = $request->input('page', 1);
    
        // Debug: Check page number and available rooms
        \Log::info('Page Number:', ['page' => $page]);
        \Log::info('Available Rooms:', ['count' => $availableRooms ? $availableRooms->count() : 0]);
    
        if ($availableRooms) {
            // Paginate the results
            $paginatedRooms = $this->paginateAvailableRooms($availableRooms, $page);
        } else {
            $paginatedRooms = null;
        }
    
        // Do not clear session data here
    
        // Retrieve other data required for the view
        $rooms = Room::where('room_type', 'Lecture')->get();
        $userRooms = Auth::user()->rooms()->get();
        $sections = Sections::with('subjects')
                            ->where('college', Auth::user()->college)
                            ->where('department', Auth::user()->department)
                            ->orderBy('program_name')
                            ->orderBy('year_level')
                            ->get();
        $faculties = Faculty::where('college', Auth::user()->college)
                            ->where('department', Auth::user()->department)
                            ->get();
    
        return view('department.schedules', compact('paginatedRooms', 'rooms', 'userRooms', 'sections', 'faculties'));
    }

    public function storePairSchedule(Request $request)
    {
        $request->validate([
            'section_id' => 'required',
            'subject_id' => 'required',
            'lecture_day1' => 'required|string',
            'lecture_start_time1' => 'required',
            'lecture_end_time1' => 'required',
            'lecture_room_id1' => 'required',
            'lecture_day2' => 'required|string',
            'lecture_start_time2' => 'required',
            'lecture_end_time2' => 'required',
            'lecture_room_id2' => 'required',
        ]);

        // Check for overlapping within the two schedules
        if ($request->lecture_day1 == $request->lecture_day2 &&
            (($request->lecture_start_time1 < $request->lecture_end_time2 && $request->lecture_end_time1 > $request->lecture_start_time2) ||
            ($request->lecture_start_time2 < $request->lecture_end_time1 && $request->lecture_end_time2 > $request->lecture_start_time1))) {
            return redirect()->back()->with('error', 'The two schedules are scheduled at the same day.');
        }

          // Fetch the selected subject to determine lab points
        $subject = Subject::find($request->subject_id);
        $hasLabPoints = $subject->Lab > 0;

        // Set the type for the second schedule based on lab points
        $type2 = $hasLabPoints ? 'Laboratory' : 'Lecture';

        // First schedule checks
        if ($this->hasScheduleConflict($request->lecture_day1, $request->lecture_start_time1, $request->lecture_end_time1, $request->section_id, $request->subject_id, $request->lecture_room_id1)) {
            return redirect()->back()->with('error', 'Conflict with the first schedule. Scheduling Stopped.');
        }

        // Second schedule checks
        if ($this->hasScheduleConflict($request->lecture_day2, $request->lecture_start_time2, $request->lecture_end_time2, $request->section_id, $request->subject_id, $request->lecture_room_id2)) {
            return redirect()->back()->with('error', 'Conflict with the second schedule. Scheduling Stopped.');
        }

        // Create first schedule
        Schedules::create([
            'day' => $request->lecture_day1,
            'start_time' => $request->lecture_start_time1,
            'end_time' => $request->lecture_end_time1,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'type' => 'Lecture',  
            'room_id' => $request->lecture_room_id1,
            'college' => Auth::user()->college,
            'department' => Auth::user()->department,
        ]);

        // Create second schedule
        Schedules::create([
            'day' => $request->lecture_day2,
            'start_time' => $request->lecture_start_time2,
            'end_time' => $request->lecture_end_time2,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'type' => $type2,  
            'room_id' => $request->lecture_room_id2,
            'college' => Auth::user()->college,
            'department' => Auth::user()->department,
        ]);

        return redirect()->back()->with('success', 'Pair schedule created successfully.');
    }

    protected function hasScheduleConflict($day, $startTime, $endTime, $sectionId, $subjectId, $roomId)
    {
        // Check for existing identical schedule
        $existingSchedule = Schedules::where('day', $day)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('room_id', $roomId)
            ->exists();

        if ($existingSchedule) {
            return true;
        }

        // Check for overlapping schedule in the same section
        $overlappingSchedule = Schedules::where('day', $day)
            ->where('section_id', $sectionId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($overlappingSchedule) {
            return true;
        }

        // Check for overlapping schedule in the same room
        $overlappingRoomSchedule = Schedules::where('day', $day)
            ->where('room_id', $roomId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($overlappingRoomSchedule) {
            return true;
        }

        // Check for overlapping faculty schedule
        $subject = Subject::find($subjectId);
        $facultyIds = $subject->faculty->pluck('id');

        $overlappingFacultySchedule = Schedules::where('day', $day)
            ->whereIn('subject_id', function ($query) use ($facultyIds) {
                $query->select('subject_id')
                    ->from('subject_faculty')
                    ->whereIn('faculty_id', $facultyIds);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($overlappingFacultySchedule) {
            return true;
        }

        return false;
    }

}