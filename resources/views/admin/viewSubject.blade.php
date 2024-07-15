@extends('layouts.app')

@section('title', 'View Subject')

@section('content')
<div class="container mt-4">
    <h2>Subject Details</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $subject->Subject_Code }}</h5>
            <p class="card-text">{{ $subject->Description }}</p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><b>Lec:</b> {{ $subject->Lec }}</li>
                <li class="list-group-item"><b>Lab:</b> {{ $subject->Lab }}</li>
                <li class="list-group-item"><b>Units:</b> {{ $subject->Units }}</li>
                <li class="list-group-item"><b>Pre-Req:</b> {{ $subject->Pre_Req }}</li>
                <li class="list-group-item"><b>Year Level:</b> {{ $subject->Year_Level }}</li>
                <li class="list-group-item"><b>Semester:</b> {{ $subject->Semester }}</li>
                <li class="list-group-item"><b>College:</b> {{ $subject->College }}</li>
                <li class="list-group-item"><b>Department:</b> {{ $subject->Department }}</li>
                <li class="list-group-item"><b>Program:</b> {{ $subject->Program }}</li>
                <li class="list-group-item"><b>Curriculum:</b> {{ $subject->Academic_Year }}</li>
            </ul>
            <div class="row mt-3">
                <div class="col-md-6">
                    <form action="{{ route('admin.subjects.delete', $subject->id) }}" method="POST" onsubmit="return confirmDeleteIndiv()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">Delete</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-warning btn-block">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function confirmDeleteIndiv() {
        return confirm("Are you sure you want to delete this Subject?");
    }
</script>
