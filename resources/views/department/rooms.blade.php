@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Room List</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Room ID</th>
                                    <th>Room Name</th>
                                    <th>Room Type</th>
                                    <th>Building</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_id }}</td>
                                        <td>{{ $room->room_name }}</td>
                                        <td>{{ $room->room_type }}</td>
                                        <td>{{ $room->building }}</td>
                                        <td>
                                            <a href="{{ route('department.roomSchedule', $room->id) }}" class="btn btn-primary">View Schedule</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $rooms->links() }} <!-- Pagination links -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
