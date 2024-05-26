@extends('layouts.app')

@section('title', 'Manage Sections')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Manage Sections</h2>
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

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Add Sections</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-danger mb-3" type="button" data-toggle="collapse" data-target="#addSectionForm" aria-expanded="false" aria-controls="addSectionForm">
                    Add Section
                </button>
                <div class="collapse" id="addSectionForm">
                    <div class="card card-body">
                        <form action="{{ route('department.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="program_name">Program Name:</label>
                                <select class="form-control" id="program_name" name="program_name" required>
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->program_name }}">{{ $program->program_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year_level">Year Level:</label>
                                <select class="form-control" id="year_level" name="year_level" required>
                                    <option value="">Select A Year Level</option>
                                    @foreach($programs as $program)
                                        @for($i = 1; $i <= $program->years; $i++)
                                            <option value="{{ $i }}">{{ $i == 1 ? $i . 'st' : ($i == 2 ? $i . 'nd' : ($i == 3 ? $i . 'rd' : $i . 'th')) }}</option>
                                        @endfor
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="section">Section:</label>
                                <label id="year_level_label" class="mb-2"></label>
                                <input type="number" class="form-control" id="section" name="section" placeholder="Enter section suffix" min="1" max="9" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Section</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Search Sections</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('department.sections') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by Program Name, Year Level, or Section" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($sections->isEmpty())
            <p>No records found.</p>
        @else
            <div class="table-responsive">
                <div class="mb-3">
                    <form action="{{ route('department.deleteAll') }}" method="POST" id="deleteAllForm">
                        @csrf
                        <button type="button" class="btn btn-danger" id="deleteAllBtn">Delete All</button>
                    </form>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Program Name</th>
                            <th>Year Level</th>
                            <th>Section</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <td>{{ $section->program_name }}</td>
                                <td>{{ $section->year_level }}</td>
                                <td>{{ $section->section }}</td>
                                <td>
                                    <a href="{{ route('department.editSection', $section->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('department.destroy', $section->id) }}" method="POST" class="deleteForm d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm deleteBtn" data-message="Are you sure you want to delete this section?">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $sections->appends(['search' => request('search')])->links() }}
        @endif
    </div>

    @section('scripts')
        <script>
            function updateYearLevelLabel() {
                var yearLevel = $('#year_level').val();
                var section = $('#section').val();

                if (yearLevel && section) {
                    $('#year_level_label').text(yearLevel + "0" + section);
                } else {
                    $('#year_level_label').text('');
                }
            }

            $('form').submit(function() {
                var yearLevel = $('#year_level').val();
                var section = $('#section').val();

                $('#section').val(yearLevel + "0" + section);
            });

            $('#program_name').change(function() {
                var programName = $(this).val();
                var program = {!! $programs->toJson() !!}.find(program => program.program_name === programName);
                var years = program ? program.years : 0;
                $('#year_level').empty();
                for (var i = 1; i <= years; i++) {
                    $('#year_level').append(`<option value="${i}">${i == 1 ? i + 'st' : (i == 2 ? i + 'nd' : (i == 3 ? i + 'rd' : i + 'th'))}</option>`);
                }
                updateYearLevelLabel();
            });

            $('#year_level').change(function() {
                updateYearLevelLabel();
            });

            $('#section').on('input', function() {
                updateYearLevelLabel(); 
            });

            $(document).ready(function() {
                $('#program_name').change();
                updateYearLevelLabel(); 
                
                $('.deleteForm').submit(function(e) {
                    var message = $(this).find('.deleteBtn').data('message');
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });

                $('#deleteAllBtn').click(function() {
                    var message = "Are you sure you want to delete all sections?";
                    if (confirm(message)) {
                        $('#deleteAllForm').submit();
                    }
                });
            });
        </script>
    @endsection

@endsection
