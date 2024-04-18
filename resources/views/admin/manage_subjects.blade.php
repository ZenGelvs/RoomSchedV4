@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
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
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Subject Manually</h5>
                        <button id="toggleManualForm" class="btn btn-primary">Add Subject Manually</button>
                        <!-- Form for manually adding subjects -->
                        <form id="manualForm" action="{{ route('admin.subjects.store') }}" method="POST" style="display: none;">
                            @csrf
                            <div class="form-group">
                                <label for="Subject_Code">Subject Code:</label>
                                <input type="text" class="form-control" id="Subject_Code" name="Subject_Code" required>
                            </div>
                            <div class="form-group">
                                <label for="Description">Description:</label>
                                <input type="text" class="form-control" id="Description" name="Description" required>
                            </div>
                            <div class="form-group">
                                <label for="Lec">Lec:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="Lec" name="Lec" value="0" min="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Lab">Lab:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="Lab" name="Lab" value="0" min="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Units">Total Units:</label>
                                <div class="input-group">                        
                                 <input type="number" class="form-control" id="Units" name="Units" value="0" min="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Pre_Req">Pre-Requisite:</label>
                                <input type="text" class="form-control" id="Pre_Req" name="Pre_Req" required>
                            </div>
                            <div class="form-group">
                                <label for="Year_Level">Year Level:</label>
                                <select class="form-control" id="Year_Level" name="Year_Level" required>
                                    <option value="">Select Year Level</option>
                                    <option value="1st">1st</option>
                                    <option value="2nd">2nd</option>
                                    <option value="3rd">3rd</option>
                                    <option value="4th">4th</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Semester">Semester:</label>
                                <select class="form-control" id="Semester" name="Semester" required>
                                    <option value="">Select Semester</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="Summer">Summer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="College">College:</label>
                                <select class="form-control" id="College" name="College" required>
                                    <option value="">Select College</option>
                                    <option value="COECSA">COECSA</option>
                                    <option value="CAMS">CAMS</option>
                                    <option value="CAS">CAS</option>
                                    <option value="CBA">CBA</option>
                                    <option value="CFAD">CFAD</option>
                                    <option value="CITHM">CITHM</option>
                                    <option value="NURSING">NURSING</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Department">Department:</label>
                                <input type="text" class="form-control" id="Department" name="Department"required>
                            </div>
                            <div class="form-group">
                                <label for="Program">Program:</label>
                                <input type="text" class="form-control" id="Program" name="Program" required>
                            </div>
                            <div class="form-group">
                                <label for="Academic_Year">Academic Year:</label>
                                <input type="text" class="form-control" id="Academic_Year" name="Academic_Year" required>
                            </div>
                            <div id="unitsValidationMessage" class="text-danger" style="display: none;">Lec and Lab must equal Total Units.</div>

                            <button type="submit" class="btn btn-primary">Add Subject</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggleManualForm').addEventListener('click', function() {
            var form = document.getElementById('manualForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });
        document.getElementById('Units').addEventListener('input', function() {
            var lec = parseInt(document.getElementById('Lec').value);
            var lab = parseInt(document.getElementById('Lab').value);
            var units = parseInt(this.value);
            if (lec + lab !== units) {
                document.getElementById('unitsValidationMessage').style.display = 'block';
            } else {
                document.getElementById('unitsValidationMessage').style.display = 'none';
            }
        });
    </script>
@endsection
