<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Sections;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $rooms = Room::all();

        $sections = Sections::with('subjects')
                    ->where('college', Auth::user()->college)
                    ->where('department', Auth::user()->department)
                    ->get(); 

        $subjects = $sections->flatMap->subjects->unique();

        return view('department.schedules', compact('rooms', 'sections', 'subjects'));
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

}
