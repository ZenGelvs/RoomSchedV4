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
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                                            <th>ID</th>
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
                                                <td>{{ $room->id }}</td>
                                                <td>{{ $room->room_id }}</td>
                                                <td>{{ $room->room_name }}</td>
                                                <td>{{ $room->room_type }}</td>
                                                <td>{{ $room->building }}</td>
                                                <td>
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