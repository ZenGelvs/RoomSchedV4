@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')
    <div class="container mt-4">
        <div class="login-container">
            <h2 class="text-center mb-4">Welcome to Room Coordinator Page, manage stuff!</h2>

                <div class="row">
                    <!-- Create Schedule Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src=" " alt="Subjects Image" 
                            style="width: 300px; height: 300px;" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">Schedules</h5>
                                <p class="card-text">Manage Schedules for the upcoming term</p>
                                <a href=" " class="btn btn-dark">Go to Schedule</a>
                            </div>
                        </div>
                    </div>

                    <!-- View Subjects Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src=" " alt="Subjects Image" 
                            style="width: 300px; height: 300px;" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">Subjects</h5>
                                <p class="card-text">Assign Professors to subjects for classes.</p>
                                <a href=" " class="btn btn-dark">Go to Subjects</a>
                            </div>
                        </div>
                    </div>

                    <!-- View Sections Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src=" " alt="Sections Image" 
                            style="width: 300px; height: 300px;" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">Sections</h5>
                                <p class="card-text">Manage sections and blocks for the upcoming term.</p>
                                <a href=" " class="btn btn-dark">Go to Sections</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection