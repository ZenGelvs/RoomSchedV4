@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Upload Excel File</h5>
                        <form action="{{ route('admin.subjects.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="excelFile">Select Excel File:</label>
                                <input type="file" class="form-control-file" id="excelFile" name="excelFile">
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Subject Manually</h5>
                        <!-- Form for manually adding subjects -->
                        <!-- You can customize this form based on your requirements -->
                        <form action="{{ route('admin.subjects.store') }}" method="POST">
                            @csrf
                            <!-- Add form fields for subject details -->
                            <button type="submit" class="btn btn-primary">Add Subject</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
