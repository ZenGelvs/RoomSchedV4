@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to Admin Page, manage stuff!</h2>

        <div class="row">
            @foreach ($subjects as $subject)
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
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $subjects->links() }}
    </div>
</div>
@endsection
