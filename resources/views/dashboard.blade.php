@extends('layouts.app')

@section('title', 'Department Head Page')

@section('content')
<div class="container mt-4">
    <div class="login-container">
        <h2 class="text-center mb-4">Welcome to OCCUPIrate, Schedule Classes for the upcoming term!</h2>

        <div class="row">
            <!-- Faculty and Assigned Subjects Card -->
            <div class="col-12 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Faculty and Assigned Subjects</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Faculty Name</th>
                                        <th>Assigned Subjects with Sections</th>
                                        <th>Total Units of Assigned Subjects</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faculties as $faculty)
                                    <tr>
                                        <td>{{ $faculty->name }}</td>
                                        <td>
                                            <ul class="list-unstyled">
                                                @foreach($faculty->subjects as $subject)
                                                    <li>{{ $subject->Subject_Code }}</li>
                                                    <ul>
                                                        @foreach($subject->sections as $section)
                                                            <li>{{ $section->program_name }} - {{ $section->section }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $faculty->total_units }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('department.faculty') }}" class="btn btn-dark mt-3">Add Faculty</a>
                    </div>
                </div>
            </div>
            <!-- Sections and Subjects without Schedules Card -->
            <div class="col-12 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Sections and Subjects without Schedules</h5>
                        
                        <!-- Dropdowns for filtering -->
                        <div class="form-group">
                            <label for="programDropdown">Select Program</label>
                            <select class="form-control" id="programDropdown">
                                <option value="">Select Program</option>
                                @foreach($programs as $programName => $sections)
                                    <option value="{{ $programName }}">{{ $programName }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sectionDropdown">Select Section</label>
                            <select class="form-control" id="sectionDropdown" disabled>
                                <option value="">Select Section</option>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Program Name</th>
                                        <th>Section</th>
                                        <th>Subject Code</th>
                                        <th>Description</th>
                                        <th>Missing Lecture</th>
                                        <th>Missing Lab</th>
                                    </tr>
                                </thead>
                                <tbody id="subjectsTableBody">
                                    @foreach($sectionsWithoutSchedules as $section)
                                        @foreach($section->subjects as $subject)
                                            @php
                                                $hasLecture = $subject->schedules->where('type', 'Lecture')->count();
                                                $hasLab = $subject->schedules->where('type', 'Lab')->count();
                                            @endphp
                                            <tr data-program="{{ $section->program_name }}" data-section="{{ $section->id }}">
                                                <td>{{ $section->program_name }}</td>
                                                <td>{{ $section->section }}</td>
                                                <td>{{ $subject->Subject_Code }}</td>
                                                <td>{{ $subject->Description }}</td>
                                                <td>
                                                    @if($subject->Lec > 0 && !$hasLecture)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($subject->Lab > 0 && !$hasLab)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('department.subjects') }}" class="btn btn-dark mt-3">Assign Subjects</a>
                        <a href="{{ route('department.sections') }}" class="btn btn-dark mt-3">Add Sections</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const programDropdown = document.getElementById('programDropdown');
    const sectionDropdown = document.getElementById('sectionDropdown');
    const subjectsTableBody = document.getElementById('subjectsTableBody');

    const sections = @json($sectionsWithoutSchedules->groupBy('program_name'));

    programDropdown.addEventListener('change', function () {
        const programName = this.value;
        sectionDropdown.innerHTML = '<option value="">Select Section</option>';

        if (programName) {
            const programSections = sections[programName];
            programSections.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.section;
                sectionDropdown.appendChild(option);
            });

            sectionDropdown.disabled = false;
        } else {
            sectionDropdown.disabled = true;
        }

        filterSubjects();
    });

    sectionDropdown.addEventListener('change', function () {
        filterSubjects();
    });

    function filterSubjects() {
        const programName = programDropdown.value;
        const sectionId = sectionDropdown.value;

        const rows = subjectsTableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const rowProgramName = row.getAttribute('data-program');
            const rowSectionId = row.getAttribute('data-section');
            
            if ((programName && rowProgramName !== programName) || (sectionId && rowSectionId !== sectionId)) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
            }
        });
    }
});
</script>
@endsection
