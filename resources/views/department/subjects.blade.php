@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Manage Subjects</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Description</th>
                        <th>Lecture Hours</th>
                        <th>Lab Hours</th>
                        <th>Units</th>
                        <th>Pre-Requisites</th>
                        <th>Year Level</th>
                        <th>Semester</th>
                        <th>Program</th>
                        <th>Academic Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                        <tr>
                            <td>{{ $subject->Subject_Code }}</td>
                            <td>{{ $subject->Description }}</td>
                            <td>{{ $subject->Lec }}</td>
                            <td>{{ $subject->Lab }}</td>
                            <td>{{ $subject->Units }}</td>
                            <td>{{ $subject->Pre_Req }}</td>
                            <td>{{ $subject->Year_Level }}</td>
                            <td>{{ $subject->Semester }}</td>
                            <td>{{ $subject->Program }}</td>
                            <td>{{ $subject->Academic_Year }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $subjects->links() }}
        </div>
    </div>
@endsection
