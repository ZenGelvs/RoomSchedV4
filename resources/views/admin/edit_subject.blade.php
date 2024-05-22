@extends('layouts.app')

@section('title', 'Edit Subject')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Subject</h5>
        </div>
        <div class="card-body">
            <form id="updateForm" action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="Subject_Code">Subject Code:</label>
                    <input type="text" class="form-control" id="Subject_Code" name="Subject_Code" value="{{ $subject->Subject_Code }}" required>
                </div>
                <div class="form-group">
                    <label for="Description">Description:</label>
                    <input type="text" class="form-control" id="Description" name="Description" value="{{ $subject->Description }}" required>
                </div>
                <div class="form-group">
                    <label for="Lec">Lec:</label>
                    <input type="number" class="form-control" id="Lec" name="Lec" value="{{ max(0, $subject->Lec) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label for="Lab">Lab:</label>
                    <input type="number" class="form-control" id="Lab" name="Lab" value="{{ max(0, $subject->Lab) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label for="Units">Total Units:</label>
                    <input type="number" class="form-control" id="Units" name="Units" value="{{ max(0, $subject->Units) }}" min="0" disabled>
                    @error('Units')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="Pre_Req">Pre-Requisite:</label>
                    <input type="text" class="form-control" id="Pre_Req" name="Pre_Req" value="{{ $subject->Pre_Req }}" required>
                </div>
                <div class="form-group">
                    <label for="Year_Level">Year Level:</label>
                    <select class="form-control" id="Year_Level" name="Year_Level" required>
                        <option value="1st" {{ $subject->Year_Level == '1st' ? 'selected' : '' }}>1st</option>
                        <option value="2nd" {{ $subject->Year_Level == '2nd' ? 'selected' : '' }}>2nd</option>
                        <option value="3rd" {{ $subject->Year_Level == '3rd' ? 'selected' : '' }}>3rd</option>
                        <option value="4th" {{ $subject->Year_Level == '4th' ? 'selected' : '' }}>4th</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Semester">Semester:</label>
                    <select class="form-control" id="Semester" name="Semester" required>
                        <option value="1" {{ $subject->Semester == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $subject->Semester == '2' ? 'selected' : '' }}>2</option>
                        <option value="Summer" {{ $subject->Semester == 'Summer' ? 'selected' : '' }}>Summer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="College">College:</label>
                    <select class="form-control" id="College" name="College" required>
                        <option value="COECSA" {{ $subject->College == 'COECSA' ? 'selected' : '' }}>COECSA</option>
                        <option value="CAMS" {{ $subject->College == 'CAMS' ? 'selected' : '' }}>CAMS</option>
                        <option value="CAS" {{ $subject->College == 'CAS' ? 'selected' : '' }}>CAS</option>
                        <option value="CBA" {{ $subject->College == 'CBA' ? 'selected' : '' }}>CBA</option>
                        <option value="CFAD" {{ $subject->College == 'CFAD' ? 'selected' : '' }}>CFAD</option>
                        <option value="CITHM" {{ $subject->College == 'CITHM' ? 'selected' : '' }}>CITHM</option>
                        <option value="NURSING" {{ $subject->College == 'NURSING' ? 'selected' : '' }}>NURSING</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Department">Department:</label>
                    <select class="form-control" id="Department" name="Department" required>
                        <option value="">Select a Department</option>
                        @foreach($collegeDepartments[$subject->College] as $department)
                            <option value="{{ $department }}" {{ $department == $subject->Department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="Program">Program:</label>
                    <select class="form-control" id="Program" name="Program" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->program_name }}" 
                                {{ $program->program_name == $subject->Program ? 'selected' : '' }}>
                                {{ $program->program_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="Academic_Year">Academic Year:</label>
                    <input type="text" class="form-control" id="Academic_Year" name="Academic_Year" value="{{ $subject->Academic_Year }}" required>
                </div>
                <button type="submit" class="btn btn-warning">Update</button>
                <a href="{{ route('dashboard.adminIndex') }}" onclick="return confirmCancelation()" type="button" class="btn btn-secondary" >Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
<script>
     function confirmCancelation() {
            return confirm("Are you sure you want to Cancel? All changes won't be saved");
        }
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("updateForm").addEventListener("submit", function(event) {
            var confirmation = confirm("Are you sure you want to save the changes?");
            if (!confirmation) {
                event.preventDefault();
            }
        });

        document.getElementById("Lec").addEventListener("change", updateTotalUnits);
        document.getElementById("Lab").addEventListener("change", updateTotalUnits);
        
        function updateTotalUnits() {
            var lec = parseInt(document.getElementById("Lec").value);
            var lab = parseInt(document.getElementById("Lab").value);
            var totalUnits = lec + lab;
            document.getElementById("Units").value = totalUnits;
        }

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
            option.value = program.program_name;
            option.textContent = program.program_name;
            programSelect.appendChild(option);
        });
    }

    document.getElementById('College').addEventListener('change', updatePrograms);
    document.getElementById('Department').addEventListener('change', updatePrograms);

    updatePrograms();
    });
</script>
