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

                    <div class="form-group">
                        <label for="faculty_type">Faculty Type:</label>
                        <select class="form-control" id="faculty_type" name="faculty_type" required>
                            <option value="Full-Time" {{ old('faculty_type', $faculty->type) === 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                            <option value="Part-Time" {{ old('faculty_type', $faculty->type) === 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" onclick="return confirmUpdate()" >Save Changes</button>
                    <a href="{{ route('department.faculty') }}" onclick="return confirmCancelation()" type="button" class="btn btn-secondary" >Cancel</a>
                </form>
            </div>
        </div>
    </div>

<script>
    function confirmUpdate() {
        return confirm('Are you sure you want to update this faculty?');
    }
    function confirmCancelation() {
            return confirm("Are you sure you want to Cancel? All changes won't be saved?");
        }
</script>
@endsection
