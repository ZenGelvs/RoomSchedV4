@extends('layouts.app')

@section('title', 'Department Head Page')

@section('content')
    <div class="container mt-4">
        <div class="login-container">
            <h2 class="text-center mb-4">Welcome to OCCUPIrate, Schedule Classes for the upcoming term!</h2>

                <div class="row">

                    <!-- View Sections Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src=" " alt="Sections Image" 
                            style="width: 300px; height: 300px;" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">Sections</h5>
                                <p class="card-text">Manage sections and blocks for the upcoming term.</p>
                                <a href="{{ route('department.sections') }}" class="btn btn-dark">Go to Sections</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection