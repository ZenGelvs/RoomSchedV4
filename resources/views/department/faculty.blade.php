@extends('layouts.app')

@section('title', 'Manage Faculty')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Manage Faculty</h2>
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
                <h5 class="mb-0">Add Faculty</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-danger mb-3" type="button" data-toggle="collapse" data-target="#addFacultyForm" aria-expanded="false" aria-controls="addFacultyForm">
                    Add Faculty
                </button>
                <div class="collapse" id="addFacultyForm">
                    <div class="card card-body">
                        <form action="{{ route('faculty.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="faculty_name">Faculty Name:</label>
                                <input type="text" class="form-control" id="faculty_name" name="faculty_name" required>
                            </div>
                            <div class="form-group">
                                <label for="faculty_id">Faculty ID:</label>
                                <input type="number" class="form-control" id="faculty_id" name="faculty_id" required min="0">
                            </div>                            
                            <button type="submit" class="btn btn-success">Add Faculty</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($faculty->isEmpty())
            <p>No records found.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Faculty Name</th>
                            <th>Faculty ID</th>
                            <th>College</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faculty as $facultyMember)
                            <tr>
                                <td>{{ $facultyMember->name }}</td>
                                <td>{{ $facultyMember->faculty_id }}</td>
                                <td>{{ $facultyMember->college }}</td>
                                <td>{{ $facultyMember->department }}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('faculty.destroy', $facultyMember->id) }}" method="POST" class="deleteForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm deleteBtn" data-message="Are you sure you want to delete this faculty member?">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.deleteForm').submit(function(e) {
                    var message = $(this).find('.deleteBtn').data('message');
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    @endsection
@endsection
