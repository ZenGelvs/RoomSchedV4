@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')
    <div class="container mt-4">
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><b>Upload Excel File</b></h5>
                        <form action="{{ route('admin.subjects.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="excelFile">Select Excel File:</label>
                                <input type="file" class="form-control-file" id="excelFile" name="excelFile">
                            </div>
                            <button type="submit" class="btn btn-primary" id="uploadButton">Upload</button>
                            <div id="fileError" class="text-danger" style="display: none;">Please select a file.</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Add Subject Manually</b></h5>
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
                                    <input type="number" class="form-control" id="Units" name="Units" value="0" min="0" required readonly>
                                </div>
                                <div id="unitsValidationMessage" class="text-danger" style="display: none;">Lec and Lab must equal Total Units.</div>
                            </div>                            
                            <div class="form-group">
                                <label for="Pre_Req">Pre-Requisite (Subject Code/s):</label>
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
                                <select class="form-control" id="Department" name="Department" required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Program">Program:</label>
                                <select class="form-control" id="Program" name="Program" required>
                                    <option value="">Select Program</option>
                                </select>
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
        function updateTotalUnits() {
            var lec = parseInt(document.getElementById('Lec').value);
            var lab = parseInt(document.getElementById('Lab').value);
            var totalUnits = lec + lab;
            document.getElementById('Units').value = totalUnits;

            var unitsValidationMessage = document.getElementById('unitsValidationMessage');
            if (lec + lab !== totalUnits) {
                unitsValidationMessage.style.display = 'block';
            } else {
                unitsValidationMessage.style.display = 'none';
            }
        }

        document.getElementById('Lec').addEventListener('input', updateTotalUnits);
        document.getElementById('Lab').addEventListener('input', updateTotalUnits);

        document.getElementById('uploadButton').addEventListener('click', function(event) {
            var fileInput = document.getElementById('excelFile');
            var fileError = document.getElementById('fileError');
            var allowedExtensions = /(\.xlsx|\.xls)$/i;

            if (!fileInput.files.length) {
                fileError.innerText = 'Please select a file.';
                fileError.style.display = 'block';
                event.preventDefault();
            } else if (!allowedExtensions.test(fileInput.value)) {
                fileError.innerText = 'Please select a valid Excel file.';
                fileError.style.display = 'block';
                event.preventDefault();
            } else {
                fileError.style.display = 'none';
            }
        });

        const collegeDepartments = {
        'COECSA': ['DCS', 'DOE', 'DOA'],
        'CAMS': ['CAMS Dept', 'CAMS Dept1', 'CAMS Dept2'],
        'CAS': ['CAS Dept', 'CAS Dept1', 'CAS Dept2'],
        'CBA': ['CBA Dept', 'CBA Dept1', 'CBA Dept2'],
        'CFAD': ['CFAD Dept', 'CFAD Dept1', 'CFAD Dept2'],
        'CITHM': ['Tourism', 'CITHM Dept1', 'CITHM Dept2'],
        'NURSING': ['NURSING Dept', 'NURSING Dept1', 'NURSING Dept2']
    };

    function updateDepartments() {
        const collegeSelect = document.getElementById('College');
        const departmentSelect = document.getElementById('Department');
        const selectedCollege = collegeSelect.value;

        departmentSelect.innerHTML = '<option value="">Select Department</option>';

        if (selectedCollege && collegeDepartments[selectedCollege]) {
            collegeDepartments[selectedCollege].forEach(department => {
                const option = document.createElement('option');
                option.value = department;
                option.textContent = department;
                departmentSelect.appendChild(option);
            });
        }
    }

    document.getElementById('College').addEventListener('change', updateDepartments);
    
    updateDepartments();

    const programsData = {!! json_encode($programs) !!};

    function updatePrograms() {
        const collegeSelect = document.getElementById('College');
        const departmentSelect = document.getElementById('Department');
        const programSelect = document.getElementById('Program');
        const selectedCollege = collegeSelect.value;
        const selectedDepartment = departmentSelect.value;

        const filteredPrograms = programsData.filter(program => {
            return program.college === selectedCollege && program.department === selectedDepartment;
        });

        programSelect.innerHTML = '<option value="">Select Program</option>';

        filteredPrograms.forEach(program => {
            const option = document.createElement('option');
            option.value = program.program_ID;
            option.textContent = program.program_name;
            programSelect.appendChild(option);
        });
    }

    document.getElementById('College').addEventListener('change', updatePrograms);
    document.getElementById('Department').addEventListener('change', updatePrograms);

    updatePrograms();
    </script>
@endsection
