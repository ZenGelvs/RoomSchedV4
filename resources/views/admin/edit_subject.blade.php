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
                    <input type="text" class="form-control" id="Department" name="Department" value="{{ $subject->Department }}" required>
                </div>
                <div class="form-group">
                    <label for="Program">Program:</label>
                    <input type="text" class="form-control" id="Program" name="Program" value="{{ $subject->Program }}" required>
                </div>
                <div class="form-group">
                    <label for="Academic_Year">Academic Year:</label>
                    <input type="text" class="form-control" id="Academic_Year" name="Academic_Year" value="{{ $subject->Academic_Year }}" required>
                </div>
                <button type="submit" class="btn btn-warning">Update</button>
                <a href="{{ route('admin.subjects.index') }}" onclick="return confirmCancelation()" type="button" class="btn btn-secondary" >Cancel</a>
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

        // Update Total Units when Lec or Lab changes
        document.getElementById("Lec").addEventListener("change", updateTotalUnits);
        document.getElementById("Lab").addEventListener("change", updateTotalUnits);
        
        function updateTotalUnits() {
            var lec = parseInt(document.getElementById("Lec").value);
            var lab = parseInt(document.getElementById("Lab").value);
            var totalUnits = lec + lab;
            document.getElementById("Units").value = totalUnits;
        }
    });
</script>
