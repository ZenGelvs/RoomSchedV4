@extends('layouts.app')

@section('title', 'Schedule')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <h2 class="mb-4">View Schedule</h2>
            <!-- Dropdown to select schedule -->
            <div class="form-group">
                <label for="scheduleSelect">Select Schedule:</label>
                <select class="form-control" id="scheduleSelect">
                    <option>Select Schedule...</option>
                    <!-- Populate dropdown with schedules -->
                </select>
            </div>
            <!-- Display selected schedule here -->
            <div id="scheduleDetails">
                <!-- Schedule details will be displayed here using JavaScript -->
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="mb-4">Create Schedule</h2>
            <!-- Form to create a new schedule -->
            <form action="" method="POST">
                @csrf
                <div class="form-group">
                    <label for="scheduleName">Schedule Name:</label>
                    <input type="text" class="form-control" id="scheduleName" name="scheduleName">
                </div>
                <!-- Add more fields as needed -->
                <button type="submit" class="btn btn-primary">Create Schedule</button>
            </form>
        </div>
    </div>
</div>

@endsection
