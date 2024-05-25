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


class ScheduleController extends Controller
{
    public function index()
    {
        $rooms = Room::all();

        $sections = Sections::with('subjects')->where('college', Auth::user()->college)
                        ->where('department', Auth::user()->department)
                        ->get(); 
        
        $faculties = Faculty::where('college', Auth::user()->college)
                        ->where('department', Auth::user()->department)
                        ->get(); 

        return view('department.schedules', compact('rooms', 'sections', 'faculties'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|string',
            'startTime' => 'required',
            'endTime' => 'required',
            'sectionId' => 'required',
            'subjectId' => 'required',
            'type' => 'required',
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
        $schedules = $section->schedules()->get();
        
        $subjects = $section->subjects()->with('schedules', 'faculty')->get();

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
        $rooms = Room::all(); 

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
            'section_id' => 'required|exists:sections,id',
        ]);

        $preferredStartTime = $request->preferred_start_time;
        $preferredEndTime = $request->preferred_end_time;
        $preferredBuilding = $request->preferred_building;

        // Retrieve the section and its subjects
        $section = Sections::findOrFail($request->section_id);
        $subjects = $section->subjects;

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

        // Track scheduled time slots per day
        $scheduledSlots = [];

        $schedulingSuccess = false;
        $errorReasons = [];

        // Iterate through subjects
        foreach ($subjects as $subject) {
            // Check if the subject has been scheduled for any day of the week
            $existingSchedules = Schedules::where('section_id', $request->section_id)
                ->where('subject_id', $subject->id)
                ->exists();

            if (!$existingSchedules) {
                // Flag to indicate if the subject is scheduled for any day
                $scheduledForAnyDay = false;

                foreach ($daysOfWeek as $day) {
                    // Check if the current day has available time slots
                    if (!isset($scheduledSlots[$day])) {
                        $scheduledSlots[$day] = [];
                    }

                    // Find an available time slot for the subject on the current day
                    $result = $this->findAvailableSlot($rooms, $day, $preferredStartTime, $preferredEndTime, $scheduledSlots[$day], $request->section_id, $subject->id);
                    $availableSlot = $result['slot'];
                    $reason = $result['reason'];

                    if ($availableSlot) {
                        // Create a new schedule for the subject
                        Schedules::create([
                            'day' => $day,
                            'start_time' => $availableSlot['start_time'],
                            'end_time' => $availableSlot['end_time'],
                            'section_id' => $request->section_id,
                            'subject_id' => $subject->id,
                            'type' => 'Lecture',
                            'room_id' => $availableSlot['room_id'],
                            'college' => Auth::user()->college,
                            'department' => Auth::user()->department,
                        ]);

                        // Mark the time slot as scheduled
                        $scheduledSlots[$day][] = [
                            'start_time' => $availableSlot['start_time'],
                            'end_time' => $availableSlot['end_time'],
                        ];

                        $schedulingSuccess = true;
                        $scheduledForAnyDay = true;
                        break; // Break the loop once scheduled for one day
                    } else {
                        // Collect the reason for failure
                        $errorReasons[$day][] = $reason;
                    }
                }
                // If scheduled for any day, break out of the subjects loop
                if ($scheduledForAnyDay) {
                    break;
                }
            }
        }

        if ($schedulingSuccess) {
            $message = 'Automatic scheduling completed successfully.';
            return redirect()->back()->with('success', $message);
        } else {
            // Prepare detailed error messages
            $detailedErrors = [];
            foreach ($errorReasons as $day => $reasons) {
                $reasonsCount = array_count_values($reasons);
                foreach ($reasonsCount as $reason => $count) {
                    if ($reason !== null) {
                        $detailedErrors[] = " Day $day: $reason ";
                    }
                }
            }
            $message = 'Automatic scheduling failed. No available time slots found. ' . implode(', ', $detailedErrors);
            return redirect()->back()->with('error', $message);
        }
    }

    private function findAvailableSlot($rooms, $day, $preferredStartTime, $preferredEndTime, $scheduledSlots, $sectionId, $subjectId)
    {
        // Sort scheduled slots by start time
        usort($scheduledSlots, function ($a, $b) {
            return strtotime($a['start_time']) - strtotime($b['start_time']);
        });
    
        // Retrieve the faculty members for the subject
        $subject = Subject::find($subjectId);
        $facultyIds = $subject->faculty->pluck('id');
    
        // Iterate through rooms
        foreach ($rooms as $room) {
            if ($room->room_type === 'Lecture') {
                // Initialize start time based on the end time of the last scheduled slot
                $startTime = empty($scheduledSlots) ? $preferredStartTime : end($scheduledSlots)['end_time'];
                $endTime = $preferredEndTime;
    
                // Check if the calculated time slot overlaps with any existing schedules for the same section, day, and time
                $overlappingSchedule = Schedules::where('day', $day)
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->where('section_id', $sectionId)
                    ->exists();
    
                // Check if the calculated time slot overlaps with any existing schedules for the same room and day
                $overlappingRoomSchedule = Schedules::where('day', $day)
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->where('room_id', $room->id)
                    ->exists();
    
                // Check if the calculated time slot overlaps with any existing schedules for the faculty and day
                $overlappingFacultySchedule = Schedules::where('day', $day)
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->whereIn('subject_id', function ($query) use ($facultyIds) {
                        $query->select('subject_id')
                            ->from('subject_faculty')
                            ->whereIn('faculty_id', $facultyIds);
                    })
                    ->exists();
    
                if (!$overlappingSchedule && !$overlappingRoomSchedule && !$overlappingFacultySchedule) {
                    return [
                        'slot' => [
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'room_id' => $room->id,
                        ],
                        'reason' => null,
                    ];
                }
    
                if ($overlappingSchedule) {
                    return [
                        'slot' => null,
                        'reason' => 'Overlapping section schedule',
                    ];
                }
    
                if ($overlappingRoomSchedule) {
                    return [
                        'slot' => null,
                        'reason' => 'Overlapping room schedule',
                    ];
                }
    
                if ($overlappingFacultySchedule) {
                    return [
                        'slot' => null,
                        'reason' => 'Overlapping faculty schedule',
                    ];
                }
            }
        }
    
        return [
            'slot' => null,
            'reason' => 'No available slots',
        ];
    }
    
}