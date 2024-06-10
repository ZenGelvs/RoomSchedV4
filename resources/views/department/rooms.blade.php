@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Room List</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-primary filter-btn" data-room-type="">All</button>
                        <button class="btn btn-primary filter-btn" data-room-type="Lecture">Lecture Rooms</button>
                        <button class="btn btn-primary filter-btn" data-room-type="Laboratory">Laboratory Rooms</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="room-table">
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
                                    <tr data-room-type="{{ $room->room_type }}">
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const roomRows = document.querySelectorAll('#room-table tbody tr');

        filterButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const roomType = this.getAttribute('data-room-type');

                // Show all rows if room type is empty
                if (roomType === '') {
                    roomRows.forEach(function(row) {
                        row.style.display = '';
                    });
                } else {
                    roomRows.forEach(function(row) {
                        const rowRoomType = row.getAttribute('data-room-type');
                        if (rowRoomType === roomType) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
