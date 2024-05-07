@extends('layouts.app')

@section('title', 'Edit Section')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Edit Section</h2>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form id="updateForm" action="{{ route('department.updateSection', $section->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="program_name">Program Name:</label>
                        <label id="" class="mb-2">{{ $section->program_name }}</label>
                        <select class="form-control" id="program_name" name="program_name" required>
                            @foreach($programs as $program)
                                <option value="{{ $program->program_name }}">{{ $program->program_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year_level">Year Level:</label>
                        <label id="" class="mb-2">{{ $section->year_level }}</label>
                        <select class="form-control" id="year_level" name="year_level" required>
                            <option value="">Select A Year Level</option>
                            @foreach($programs as $program)
                                @for($i = 1; $i <= $program->years; $i++)
                                    <option value="{{ $i }}" {{ $i == $section->year_level ? 'selected' : '' }}>
                                        {{ $i == 1 ? $i . 'st' : ($i == 2 ? $i . 'nd' : ($i == 3 ? $i . 'rd' : $i . 'th')) }}
                                    </option>
                                @endfor
                            @endforeach
                        </select>
                    </div>                                    
                    <div class="form-group">
                        <label for="section">Section:</label>
                        <label id="year_level_label" class="mb-2">{{ $section->year_level }}01</label>
                        <input type="number" class="form-control" id="section" name="section" value="{{ substr($section->section, -1) }}" min="1" max="10" required>
                    </div>                    
                    <button type="button" id="submitButton" class="btn btn-success">Update Section</button>
                    <a href="{{ route('department.sections') }}" onclick="return confirmCancelation()" type="button" class="btn btn-secondary" >Cancel</a>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
         function confirmCancelation() {
            return confirm("Are you sure you want to Cancel? All changes won't be saved");
        }

        function updateYearLevelLabel() {
            var yearLevel = $('#year_level').val(); 
            var section = $('#section').val();
            
            var suffix = section % 10;
            
            $('#year_level_label').text(yearLevel + "0" + suffix);
        }

        function updateYearLevelOptions() {
            var programName = $('#program_name').val();
            var program = {!! $programs->toJson() !!}.find(program => program.program_name === programName);
            var years = program ? program.years : 0;
            $('#year_level').empty();
            for (var i = 1; i <= years; i++) {
                $('#year_level').append(`<option value="${i}">${i == 1 ? i + 'st' : (i == 2 ? i + 'nd' : (i == 3 ? i + 'rd' : i + 'th'))}</option>`);
            }
            updateYearLevelLabel();
        }

        $(document).ready(function() {
            updateYearLevelOptions();
            
            $('#program_name').change(function() {
                updateYearLevelOptions();
            });

            $('#year_level, #section').on('change input', function() {
                updateYearLevelLabel();
            });

            $('#submitButton').click(function() {
                if (confirm("Are you sure you want to update this section?")) {
                    $('#updateForm').submit();
                }
            });
        });
    </script>
    @endsection
    
@endsection
