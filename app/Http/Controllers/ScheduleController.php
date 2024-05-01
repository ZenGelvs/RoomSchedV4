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
            ->where(function ($query) use ($request) {
                $query->where('room_id', '!=', $request->roomId) 
                    ->where(function ($query) use ($request) {
                        $query->where(function ($query) use ($request) {
                            $query->where('start_time', '>=', $request->startTime)
                                ->where('start_time', '<', $request->endTime);
                        })->orWhere(function ($query) use ($request) {
                            $query->where('end_time', '>', $request->startTime)
                                ->where('end_time', '<=', $request->endTime);
                        });
                    });
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

        if ($schedule->day == $request->day &&
            $schedule->start_time == $request->startTime &&
            $schedule->end_time == $request->endTime &&
            $schedule->subject_id == $request->subjectId &&
            $schedule->type == $request->type &&
            $schedule->room_id == $request->roomId) {
            return redirect()->back()->with('success', 'Schedule updated successfully.');
        }

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
            ->where(function ($query) use ($request) {
                $query->where('room_id', '!=', $request->roomId) 
                    ->where(function ($query) use ($request) {
                        $query->where(function ($query) use ($request) {
                            $query->where('start_time', '>=', $request->startTime)
                                ->where('start_time', '<', $request->endTime);
                        })->orWhere(function ($query) use ($request) {
                            $query->where('end_time', '>', $request->startTime)
                                ->where('end_time', '<=', $request->endTime);
                        });
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

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Retrieve the section and its subjects
        $section = Sections::findOrFail($request->section_id);
        $subjects = $section->subjects;

        $rooms = Room::all();

        // Retrieve preferred day and time from the request
        $preferredDay = $request->input('preferred_day');
        $preferredStartTime = $request->input('preferred_start_time');
        $preferredEndTime = $request->input('preferred_end_time');

        $schedulingSuccess = false;

        // Iterate through subjects
        foreach ($subjects as $subject) {
            // Check if the subject has been scheduled for any day of the week
            $existingSchedules = Schedules::where('section_id', $request->section_id)
                ->where('subject_id', $subject->id)
                ->exists();

            if (!$existingSchedules) {
                // Determine the days of the week to iterate through
                $daysToCheck = ($preferredDay) ? [$preferredDay] : $daysOfWeek;

                foreach ($daysToCheck as $day) {
                    // Check if the preferred time is available or use default time
                    $startTime = ($preferredStartTime) ? $preferredStartTime : '07:00';
                    $endTime = ($preferredEndTime) ? $preferredEndTime : '08:30';
                    
                    // Find an available room for the subject
                    $availableRoom = $this->findAvailableRoom($rooms, $day, $startTime, $endTime);

                    if ($availableRoom) {
                        // Create a new schedule for the subject
                        Schedules::create([
                            'day' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'section_id' => $request->section_id,
                            'subject_id' => $subject->id,
                            'type' => 'Lecture',
                            'room_id' => $availableRoom->id,
                            'college' => Auth::user()->college,
                            'department' => Auth::user()->department,
                        ]);
                        $schedulingSuccess = true;
                        break; // Break the loop once scheduled for one day
                    } else {
                        $schedulingSuccess = false;
                    }
                }
            }
        }

        if ($schedulingSuccess) {
            return redirect()->back()->with('success', 'Automatic scheduling completed successfully.');
        } else {
            return redirect()->back()->with('error', 'Automatic scheduling failed. No available rooms found.');
        }
    }

    

    private function findAvailableRoom($rooms, $day, $startTime, $endTime)
    {
        // Iterate through rooms and find the first available room with room_type "Lecture"
        foreach ($rooms as $room) {
            if ($room->room_type === 'Lecture') {
                $existingSchedule = Schedules::where('day', $day)
                    ->where('start_time', '<=', $startTime)
                    ->where('end_time', '>=', $endTime)
                    ->where('room_id', $room->id)
                    ->exists();

                if (!$existingSchedule) {
                    return $room; // Return the available room
                }
            }
        }

        return null; // Return null if no available room with room_type "Lecture" is found
    }
    
}
