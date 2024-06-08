@extends('layouts.app')

@section('title', 'Assign Rooms to Faculty')

@section('content')
    <div class="container mt-4">
        <div class="login-container">
            <h2 class="text-center mb-4">Assign Rooms to Faculty Members</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Form for assigning rooms to faculty members -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#assignRoomForm" aria-expanded="false" aria-controls="assignRoomForm">
                                Assign Room to Faculty
                            </button>
                        </div>
                        <div class="collapse show" id="assignRoomForm">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-md-5">
                                        <form method="GET" action="">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Search for Rooms by ID, Type, or Building" name="search">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <form method="POST" action="{{ route('roomCoordinator.assignRoom') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="faculty">Select Faculty Member</label>
                                        <select class="form-control" id="user" name="user_id" required>
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Room</th>
                                                    <th>Room Type</th>
                                                    <th>Building</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rooms as $room)
                                                    <tr class="roomRow" data-building="{{ $room->building }}" data-room-type="{{ $room->room_type }}">
                                                        <td><input type="checkbox" name="room_ids[]" value="{{ $room->id }}"></td>
                                                        <td>{{ $room->room_id }} {{ $room->room_name }}</td>
                                                        <td>{{ $room->room_type }}</td>
                                                        <td>{{ $room->building }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            {{ $rooms->links() }} 
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-success">Assign Rooms</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table to display assigned rooms for each user -->
            @foreach ($users as $user)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <b>Assigned Rooms for {{ $user->name }} <b>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Room</th>
                                                <th>Room Type</th>
                                                <th>Building</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->rooms as $room)
                                                <tr>
                                                    <td>{{ $room->room_id }} {{ $room->room_name }}</td>
                                                    <td>{{ $room->room_type }}</td>
                                                    <td>{{ $room->building }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('roomCoordinator.unassignRoom', ['user_id' => $user->id, 'room_id' => $room->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Unassign</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
       
    </script>
    
@endsection
