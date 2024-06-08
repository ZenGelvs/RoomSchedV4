<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index(){

        $user = Auth::user();

        $rooms = $user->rooms()->paginate(10); 

        return view('department.rooms', compact('rooms'));
    }

    public function roomSchedule($roomId)
    {
        $room = Room::findOrFail($roomId);
        $schedules = Schedules::where('room_id', $roomId)->with('subject')->with('section')->get();
        return view('department.room_sched', compact('room', 'schedules'));
    }
}
