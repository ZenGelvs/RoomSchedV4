<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Sections;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $schedules = Schedules::where('room_id', $roomId)->with('subject')->with('section')->get();
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

    public function viewFacultySchedule(Request $request, $facultyId)
    {
        $facultyId = $request->input('faculty', $facultyId);
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
                        ->paginate(5)
                        ->appends(['search' => $search]);

        return view('roomCoordinator.sections_index', compact('sections'));
    }

    public function viewSectionSchedule($sectionId)
    {
        $section = Sections::findOrFail($sectionId);
        $section = Sections::findOrFail($sectionId);
        $schedules = $section->schedules()->with('subject', 'room')->get();
        $subjects = $section->subjects()->with(['schedules' => function($query) use ($sectionId) {
            $query->where('section_id', $sectionId);
        }, 'faculty'])->get();

        return view('roomCoordinator.sections_schedule', compact('section', 'schedules', 'subjects'));

    }

    public function destroySchedule(Schedules $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Schedule deleted successfully');
    }

    public function editSchedule(Schedules $schedule)
    {   
        $schedule->load('section');
        $sections = Sections::with('subjects')->get();
        $rooms = Room::all(); 
        return view('roomCoordinator.edit_schedule', compact('schedule', 'rooms', 'sections'));
    }

    public function updateSchedule(Request $request, Schedule $schedule)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|string',
            'room_id' => 'required|string',
            'department' => 'required|string',
            'college' => 'required|string',
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

        $schedule->update($request->all());

        return redirect()->back()->with('success', 'Schedule updated successfully');
    }

    public function addSchedule()
    {   
        $rooms = Room::all();

        $sections = Sections::all();
        
        $faculties = Faculty::all(); 

        return view('roomCoordinator.add_sched',  compact('rooms', 'sections', 'faculties'));
    }

    public function storeSchedule(Request $request)
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

        $section = Sections::findOrFail($request->sectionId);

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
            'college' =>  $section->college,
            'department' => $section->department,
        ]);

        return redirect()->back()->with('success', 'Schedule created successfully.');
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
        return redirect()->route('roomCoordinator.automatic_schedule');
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
            ['path' => route('roomCoordinator.automatic_schedule'), 'query' => request()->query()]
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
    
        // Retrieve other data required for the view
        $rooms = Room::where('room_type', 'Lecture')->get();
        $sections = Sections::all();
        $faculties = Faculty::all(); 

        return view('roomCoordinator.add_sched', compact('paginatedRooms', 'rooms', 'sections', 'faculties'));
    }

    public function assignRoom(Request $request)
    {

        $room_ids = $request->input('room_ids');
        $user_id = $request->input('user_id');

        if (empty($request->room_ids)) {
            return redirect()->back()->with('error', 'Please select a room to assign.');
        }

        $user = User::findOrFail($request->user_id);
    
        $assignedRoomIds = $user->rooms()->pluck('room.id')->toArray();

        $newRoomIds = array_diff($request->room_ids, $assignedRoomIds);

        if (!empty($newRoomIds)) {
            $user->rooms()->syncWithoutDetaching($newRoomIds);

            return redirect()->back()->with('success', 'Room is assigned to user successfully.');
        } else {
            return redirect()->back()->with('error', 'Room/s are already assigned to the user.');
        }
    }

    public function showAssignRoomsToFaculty(Request $request)
    {   
        $search = $request->input('search');

        $users = User::whereNotIn('college', ['ADMIN'])
                    ->whereNotIn('department', ['ROOM COORDINATOR'])
                    ->get();

        $assignedRoomIds = []; 

        foreach ($users as $user) {
            foreach ($user->rooms as $room) {
                $assignedRoomIds[] = $room->id;
            }
        }

        $rooms = Room::query();

        if ($search) {
            $rooms->where(function ($query) use ($search) {
                $query->where('room_id', 'like', '%' . $search . '%')
                    ->orWhere('room_name', 'like', '%' . $search . '%')
                    ->orWhere('building', 'like', '%' . $search . '%')
                    ->orWhere('room_type', 'like', '%' . $search . '%');
            });
        }

        $rooms = $rooms->paginate(10)->appends(['search' => $search]); 

        return view('roomCoordinator.assign_rooms', compact('users', 'rooms'));
    }    

    public function unassignRoom(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:room,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->rooms()->detach($request->room_id);

        return redirect()->back()->with('success', 'Room unassigned successfully.');
    }

}   
