@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to Subjects Record, manage subjects for the Term</h2>

        <!-- Search and Filter Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('dashboard.adminIndex') }}" method="GET" class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search by Subject name or Code" aria-label="Search" name="search" style="width: 280px;">
                    <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit" style="color: black;">Search <i class="fa fa-search" style="color: black;"></i></button>
                </form>
            </div>
        
         @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </ul>
            </div>
        @endif
        
        <!-- Subjects List -->
        <div class="row">
            @forelse ($subjects as $subject)
            <div class="col-md-4">
                <div class="card mb-4 animated fadeIn card-hover">
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
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <!-- Delete Button -->
                                <form action="{{ route('admin.subjects.delete', $subject->id) }}" method="POST" onsubmit="return confirmDeleteIndiv()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger rounded-pill btn-block">Delete</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <!-- Edit Button -->
                                <form action="{{ route('admin.subjects.edit', $subject->id) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-warning rounded-pill btn-block">Edit</button>
                                </form>
                            </div>
                        </div>
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

    function confirmDeleteIndiv() {
        return confirm("Are you sure you want to delete this Subject in the Table?");
    }
</script>
