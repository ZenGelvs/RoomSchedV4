@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')
    <div class="container mt-4">
        <div class="login-container">
            <h2 class="text-center mb-4">Welcome to Admin Page, manage stuff!</h2>

                <div class="row">
                    <!-- Manage Subjects Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Manage Subjects</h5>
                                <p class="card-text">Manage Subjects for the term</p>
                                <a href=" " class="btn btn-dark">Manage Subjects</a>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-center mb-4">Supposed to be the Subject will be shown here in card Format!</h2>

                </div>
            </div>
        </div>
    </div>
@endsection