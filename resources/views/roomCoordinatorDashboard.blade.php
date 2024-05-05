@extends('layouts.app')

@section('title', 'Room Coordinator Page')

@section('content')
    <div class="container mt-4">
        <div class="login-container">
            <h2 class="text-center mb-4">Welcome to Room Coordinator Page, manage stuff!</h2>

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
            <div class="row mt-4">
                <div class="col-md-4">
                    <form action="{{ route('dashboard.roomCoordIndex') }}" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search by room ID or name" name="search">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#addSubjectForm" aria-expanded="false" aria-controls="addSubjectForm">
                                Add Room
                            </button>
                        </div>
                        <div class="collapse" id="addSubjectForm">
                            <div class="card-body">
                                <form method="POST" action="{{ route('roomCoordinator.addRoom') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="roomName">Room Name</label>
                                        <input type="text" class="form-control" id="roomName" name="roomName" placeholder="Enter Room name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="roomID">Room ID</label>
                                        <input type="text" class="form-control" id="roomID" name="roomID" placeholder="Enter Room ID" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="roomType">Room Type</label>
                                        <select class="form-control" id="roomType" name="roomType" required>
                                            <option value="">Select Room Type</option>
                                            <option value="Lecture">Lecture</option>
                                            <option value="Lab">Lab</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="building">Building</label>
                                        <select class="form-control" id="building" name="building" required>
                                            <option value="">Select Building </option>
                                            <option value="COECSA">COECSA</option>
                                            <option value="Jose">Jose</option>
                                            <option value="Sotero">Sotero</option>
                                        </select>                                    </div>
                                    <button type="submit" class="btn btn-success">Add Room</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Room Table Card -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Room List</h5>
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
                                                    <a href="{{ route('roomCoordinator.editRoom', $room->id) }}" class="btn btn-warning">Edit room</a>
                                                    <a href="#" class="btn btn-primary">View Schedule</a>
                                                    <form action="{{ route('roomCoordinator.deleteRoom', $room->id) }}" method="POST" onsubmit="return confirmDeleteRoom()">
                                                        @csrf
                                                        @method('DELETE') 
                                                        <button type="submit" class="btn btn-danger mt-2">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    {{ $rooms->links() }} <!-- Pagination Links -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
@endsection

<script>
    function confirmDeleteRoom() {
        return confirm("Are you sure you want to delete this Room?");
    }
</script>
