@extends('layouts.app')

@section('title', 'Manage Schedule Pairing')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Manage Schedule Pairing</h2>

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

    <div class="card mb-4">
        <div class="card-header">
            <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#addSchedulePairingForm" aria-expanded="false" aria-controls="addSchedulePairingForm">
                Add Schedule Pairing
            </button>
        </div>
        <div class="collapse" id="addSchedulePairingForm">
            <div class="card-body">
                <form method="POST" action="{{ route('roomCoordinator.storeSchedulePairing') }}">
                    @csrf
                    <div class="form-group">
                        <label for="days">Select Two Days</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Monday" id="dayMonday">
                            <label class="form-check-label" for="dayMonday">Monday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Tuesday" id="dayTuesday">
                            <label class="form-check-label" for="dayTuesday">Tuesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Wednesday" id="dayWednesday">
                            <label class="form-check-label" for="dayWednesday">Wednesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Thursday" id="dayThursday">
                            <label class="form-check-label" for="dayThursday">Thursday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Friday" id="dayFriday">
                            <label class="form-check-label" for="dayFriday">Friday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Saturday" id="daySaturday">
                            <label class="form-check-label" for="daySaturday">Saturday</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Add Pairing</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Schedule Pairings</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedulePairings as $pairing)
                            <tr>
                                <td>{{ implode(', ', json_decode($pairing->days)) }}</td>
                                <td>
                                    <a href="{{ route('roomCoordinator.editSchedulePairing', $pairing->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('roomCoordinator.destroySchedulePairing', $pairing->id) }}" method="POST" onsubmit="return confirmDeletePairing()" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $schedulePairings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function confirmDeletePairing() {
        return confirm("Are you sure you want to delete this pairing?");
    }
</script>
