<?php

namespace App\Http\Controllers;

use App\Models\Room;
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
}
