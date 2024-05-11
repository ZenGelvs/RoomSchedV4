<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Faculty;
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
            return redirect()->back()->with('error', 'There is an overlapping schedule for the selected room and time slot.');
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

        return view('department.section_schedule', compact('section', 'schedules'));
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
                $schedule->section = $section;
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
        ->where('room_id', $request->roomId)
        ->where(function ($query) use ($request) {
            $query->whereRaw('? between start_time and end_time', [$request->startTime])
                ->orWhereRaw('? between start_time and end_time', [$request->endTime])
                ->orWhere(function ($query) use ($request) {
                    $query->where('start_time', '>=', $request->startTime)
                        ->where('end_time', '<=', $request->endTime);
                });
        })
        ->exists();

        if ($overlappingSchedule) {
            return redirect()->back()->with('error', 'There is an overlapping schedule for the selected room and time slot.');
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

        // Retrieve the section and its subjects
        $section = Sections::findOrFail($request->section_id);
        $subjects = $section->subjects;

        // Retrieve rooms and filter by preferred room
        $rooms = ($request->preferredRoom !== 'Any') ? Room::where('id', $request->preferredRoom)->get() : Room::where('room_type', 'Lecture')->get();

        // Determine preferred days to iterate through
        $daysOfWeek = ($request->preferred_day !== 'Any') ? [$request->preferred_day] : ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Track scheduled time slots per day
        $scheduledSlots = [];

        $schedulingSuccess = false;

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
                    $availableSlot = $this->findAvailableSlot($rooms, $day, $preferredStartTime, $preferredEndTime, $scheduledSlots[$day], $request->section_id);

                    if ($availableSlot) {
                        // Check if the available slot is different from the preferred time slot
                        $preferredTimeSlot = [
                            'start_time' => $preferredStartTime,
                            'end_time' => $preferredEndTime,
                        ];

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
            return redirect()->back()->with('error', 'Automatic scheduling failed. No available time slots found.');
        }
    }

    private function findAvailableSlot($rooms, $day, $preferredStartTime, $preferredEndTime, $scheduledSlots, $sectionId)
    {
        // Sort scheduled slots by start time
        usort($scheduledSlots, function ($a, $b) {
            return strtotime($a['start_time']) - strtotime($b['start_time']);
        });

        // Iterate through rooms
        foreach ($rooms as $room) {
            if ($room->room_type === 'Lecture') {
                // Initialize start time based on the end time of the last scheduled slot
                $startTime = empty($scheduledSlots) ? $preferredStartTime : end($scheduledSlots)['end_time'];
                $endTime = $preferredEndTime; // Use the user-selected end time

                // Format the start and end times in a valid date/time format
                $formattedStartTime = date('Y-m-d H:i:s', strtotime($startTime));
                $formattedEndTime = date('Y-m-d H:i:s', strtotime($endTime));

                // Check if the calculated time slot overlaps with any existing schedules for the same section, day, and time
                $overlappingSchedule = Schedules::where('day', $day)
                    ->where('start_time', '<', $formattedEndTime)
                    ->where('end_time', '>', $formattedStartTime)
                    ->where('section_id', $sectionId)
                    ->exists();

                // Check if the calculated time slot overlaps with any existing schedules for the same room and day
                $overlappingRoomSchedule = Schedules::where('day', $day)
                    ->where('start_time', '<', $formattedEndTime)
                    ->where('end_time', '>', $formattedStartTime)
                    ->where('room_id', $room->id)
                    ->exists();

                if (!$overlappingSchedule && !$overlappingRoomSchedule) {
                    return [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'room_id' => $room->id,
                    ];
                }
            }
        }

        return null;
    }

}