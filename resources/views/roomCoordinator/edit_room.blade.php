@extends('layouts.app')

@section('title', 'Edit Room')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center mb-0">Edit Room</h2>
            </div>
            <div class="card-body">
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
                    <div class="col-md-6 offset-md-3">
                        <form id="editRoomForm" method="POST" action="{{ route('roomCoordinator.updateRoom', $room->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="roomName">Room Name</label>
                                <input type="text" class="form-control" id="roomName" name="roomName" value="{{ $room->room_name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="roomID">Room ID</label>
                                <input type="text" class="form-control" id="roomID" name="roomID" value="{{ $room->room_id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="roomType">Room Type</label>
                                <select class="form-control" id="roomType" name="roomType" required>
                                    <option value="Lecture" {{ $room->room_type == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                                    <option value="Lab" {{ $room->room_type == 'Lab' ? 'selected' : '' }}>Lab</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="building">Building</label>
                                <select class="form-control" id="building" name="building" required>
                                    <option value="COECSA" {{ $room->building == 'COECSA' ? 'selected' : '' }}>COECSA</option>
                                    <option value="Jose" {{ $room->building == 'Jose' ? 'selected' : '' }}>Jose</option>
                                    <option value="Sotero" {{ $room->building == 'Sotero' ? 'selected' : '' }}>Sotero</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-success" onclick="confirmUpdate()">Update Room</button>
                            <a href="{{ route('dashboard.roomCoordIndex')}}" type="button" class="btn btn-secondary" >Cancel</a>
                        </form>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmUpdate() {
        if (confirm("Are you sure you want to update this room?")) {
            document.getElementById('editRoomForm').submit();
        }
    }
</script>
