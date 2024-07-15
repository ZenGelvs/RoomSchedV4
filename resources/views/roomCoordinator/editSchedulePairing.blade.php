@extends('layouts.app')

@section('title', 'Edit Schedule Pairing')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Schedule Pairing</h2>

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
        <div class="card-body">
            <form method="POST" action="{{ route('roomCoordinator.updateSchedulePairing', $schedulePairing->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="days">Select Two Days</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="days[]" value="Monday" id="dayMonday" {{ in_array('Monday', json_decode($schedulePairing->days)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="dayMonday">Monday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="days[]" value="Tuesday" id="dayTuesday" {{ in_array('Tuesday', json_decode($schedulePairing->days)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="dayTuesday">Tuesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="days[]" value="Wednesday" id="dayWednesday" {{ in_array('Wednesday', json_decode($schedulePairing->days)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="dayWednesday">Wednesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="days[]" value="Thursday" id="dayThursday" {{ in_array('Thursday', json_decode($schedulePairing->days)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="dayThursday">Thursday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="days[]" value="Friday" id="dayFriday" {{ in_array('Friday', json_decode($schedulePairing->days)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="dayFriday">Friday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="days[]" value="Saturday" id="daySaturday" {{ in_array('Saturday', json_decode($schedulePairing->days)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="daySaturday">Saturday</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update Pairing</button>
            </form>
        </div>
    </div>
</div>
@endsection
