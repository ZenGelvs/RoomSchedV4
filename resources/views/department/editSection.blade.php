@extends('layouts.app')

@section('title', 'Edit Section')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Edit Section</h2>
        
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
                <form action="{{ route('department.updateSection', $section->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="program_name">Program Name:</label>
                        <input type="text" class="form-control" id="program_name" name="program_name" value="{{ $section->program_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="year_level">Year Level:</label>
                        <input type="number" class="form-control" id="year_level" name="year_level" value="{{ $section->year_level }}" required>
                    </div>
                    <div class="form-group">
                        <label for="section">Section:</label>
                        <input type="text" class="form-control" id="section" name="section" value="{{ $section->section }}" required>
                    </div>
                    <button type="submit" class="btn btn-success">Update Section</button>
                </form>
            </div>
        </div>
    </div>
@endsection
