@extends('layouts.app')

@section('title', 'Edit Faculty')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Edit Faculty</h2>

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

        <div class="card">
            <div class="card-body">
                <form action="{{ route('faculty.update', $faculty->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="faculty_name">Faculty Name:</label>
                        <input type="text" class="form-control" id="faculty_name" name="faculty_name" value="{{ $faculty->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="faculty_id">Faculty ID:</label>
                        <input type="number" class="form-control" id="faculty_id" name="faculty_id" value="{{ $faculty->faculty_id }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
