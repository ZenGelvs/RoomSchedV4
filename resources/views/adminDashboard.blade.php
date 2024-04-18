@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to Admin Page, manage stuff!</h2>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="{{ route('dashboard.adminIndex') }}" method="GET" class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
            <div class="col-md-4">
                <form id="deleteAllForm" action="{{ route('admin.subjects.deleteAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete()" class="btn btn-danger float-right">Delete All</button>
                </form>
            </div>
        </div>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="row">
            @forelse ($subjects as $subject)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><b>{{ $subject->Subject_Code }}</b></h5>
                        <p class="card-text"><strong>{{ $subject->Description }}</strong></p>
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
                            <li class="list-group-item"><b>Academic Year:</b> {{ $subject->Academic_Year }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-12 text-center">
                <p>No subjects found.</p>
            </div>
            @endforelse
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $subjects->links() }}
    </div>
</div>
@endsection

<script>
    function confirmDelete() {
        if (confirm("Are you sure you want to delete ALL the Subjects in the Table?")) {
            document.getElementById('deleteAllForm').submit();
        }
    }
</script>