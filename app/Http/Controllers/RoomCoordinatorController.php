<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Faculty;
use App\Models\Sections;
use App\Models\Schedules;
use Illuminate\Http\Request;

class RoomCoordinatorController extends Controller
{
    public function deleteRoom($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('dashboard.roomCoordIndex')->with('success', 'Room deleted successfully.');
    }

    public function addRoom(Request $request)
    {
        $request->validate([
            'roomName' => 'required|string|max:255',
            'roomID' => 'required|string|max:255',
            'roomType' => 'required|string|max:255',
            'building' => 'required|string|max:255',
        ]);

        $existingRoom = Room::where('room_name', $request->roomName)
            ->where('room_id', $request->roomID)
            ->where('room_type', $request->roomType)
            ->where('building', $request->building)
            ->exists();

        if ($existingRoom) {
            return redirect()->back()->with('error', 'Room with the same details already exists.');
        }

        $duplicateRoom = Room::where('room_name', $request->roomName)
            ->where('room_id', $request->roomID)
            ->exists();

        if ($duplicateRoom) {
            return redirect()->back()->with('error', 'Room with the same name and ID already exists.');
        }

        $roomWithSameID = Room::where('room_id', $request->roomID)->exists();

        if ($roomWithSameID) {
            return redirect()->back()->with('error', 'Room with the same ID already exists.');
        }

        Room::create([
            'room_name' => $request->roomName,
            'room_id' => $request->roomID,
            'room_type' => $request->roomType,
            'building' => $request->building,
        ]);

        return redirect()->back()->with('success', 'Room added successfully.');
    }

    public function editRoom($id)
    {
        $room = Room::findOrFail($id);
        return view('roomCoordinator.edit_room', compact('room'));
    }

    public function updateRoom(Request $request, $id)
    {
        $request->validate([
            'roomName' => 'required|string|max:255',
            'roomID' => 'required|string|max:255',
            'roomType' => 'required|string|max:255',
            'building' => 'required|string|max:255',
        ]);

        $room = Room::findOrFail($id);

        $existingRoom = Room::where('room_name', $request->roomName)
                            ->where('room_id', $request->roomID)
                            ->where('room_type', $request->roomType)
                            ->where('building', $request->building)
                            ->where('id', '!=', $id)
                            ->exists();

        if ($existingRoom) {
            return redirect()->back()->with('error', 'Room with the same details already exists.');
        }

        $duplicateRoom = Room::where('room_name', $request->roomName)
            ->where('room_id', $request->roomID)
            ->exists();

        if ($duplicateRoom) {
            return redirect()->back()->with('error', 'Room with the same name and ID already exists.');
        }

        $roomWithSameID = Room::where('room_id', $request->roomID)->exists();

        if ($roomWithSameID) {
            return redirect()->back()->with('error', 'Room with the same ID already exists.');
        }

        $room->update([
            'room_name' => $request->roomName,
            'room_id' => $request->roomID,
            'room_type' => $request->roomType,
            'building' => $request->building,
        ]);

        return redirect()->route('dashboard.roomCoordIndex')->with('success', 'Room updated successfully.');
    }

    public function roomSchedule($roomId)
    {
        $room = Room::findOrFail($roomId);
        $schedules = Schedules::where('room_id', $roomId)->get();
        return view('roomCoordinator.room_sched', compact('room', 'schedules'));
    }

    public function facultySchedIndex(Request $request)
    {
        $query = Faculty::with('subjects');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $facultyList = $query->paginate(5);

        return view('roomCoordinator.faculty_index', compact('facultyList'));
    }

    public function viewFacultySchedule($facultyId)
    {
        $faculty = Faculty::findOrFail($facultyId);
        $schedules = collect();
        foreach ($faculty->subjects as $subject) {
            foreach ($subject->schedules as $schedule) {
                $section = $subject->sections()->first();
                $schedule->section = $section;
                $schedules->push($schedule);
            }
        }
        return view('roomCoordinator.faculty_sched', compact('faculty', 'schedules'));
    }

    public function sectionScheduleIndex(Request $request)
    {
        $search = $request->input('search');
    
        $sections = Sections::query()
                        ->where('program_name', 'like', "%$search%")
                        ->orWhere('year_level', 'like', "%$search%")
                        ->orWhere('section', 'like', "%$search%")
                        ->orWhere('college', 'like', "%$search%")
                        ->orWhere('department', 'like', "%$search%")
                        ->get();
                        
        return view('roomCoordinator.sections_index', compact('sections'));
    }

    public function viewSectionSchedule($sectionId)
    {
        $section = Sections::findOrFail($sectionId);
        $schedules = $section->schedules()->get();

        return view('roomCoordinator.sections_schedule', compact('section', 'schedules'));

    }

    public function destroySchedule(Schedules $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Schedule deleted successfully');
    }
}   
