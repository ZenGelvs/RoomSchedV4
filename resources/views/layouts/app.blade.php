<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Welcome to Pirate Nav Arrrgh!')</title>
    <link rel="icon" type="image/png" href="{{ asset('LPU_Logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-oQDd8Jce04qWynMzO/2s4IsNtKnBLIdjyS6K7jOEYZgfuWqZlO4A+OMs1I1dL3sN" crossorigin="anonymous"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Afacad&display=swap');

        body {
            background-color: #f8f9fa !important;
            font-family: 'Afacad', sans-serif;
        }

        .navbar {
            background-color: #b20000ea !important;
            font-family: 'Afacad', sans-serif;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #f8f9fa !important;
        }

        .navbar-light .navbar-toggler-icon {
            background-color: #fff !important;
        }

        .navbar-light .navbar-toggler {
            border-color: #fff !important;
        }

        .navbar-light .navbar-toggler:hover,
        .navbar-light .navbar-toggler:focus {
            background-color: #fff !important;
        }

        .navbar-light .navbar-toggler:hover .navbar-toggler-icon,
        .navbar-light .navbar-toggler:focus .navbar-toggler-icon {
            background-color: #b20000ea !important;
        }

        .navbar-light .navbar-nav .nav-link.dropdown-toggle::after {
            border-top-color: #666666 !important;
        }

        .navbar-light .navbar-nav .nav-link.dropdown-toggle:hover::after {
            border-top-color: #fff !important;
        }

        .navbar-light .navbar-nav .nav-link.dropdown-toggle:focus::after {
            border-top-color: #fff !important;
        }

        .navbar-light .navbar-brand {
            color: #fff !important;
        }
    </style>

    @yield('styles') 
</head>
<body>

    @auth
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="">OCCUPIrate</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- Navigation links here -->

                    <!-- Check for Admin Role -->
                @if(Auth::user()->college === 'ADMIN' && Auth::user()->department === 'ADMIN')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.subjects.index') }}">Add Subject</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.adminIndex') }}">Manage Subjects</a>
                    </li>

                @endif
                <!-- Check for Room Coordinator Role -->
                @if(Auth::user()->college === 'ROOM COORDINATOR' && Auth::user()->department === 'ROOM COORDINATOR')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.roomCoordIndex') }}">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('roomCoordinator.facultySchedIndex') }}">Faculty</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('roomCoordinator.sectionScheduleIndex') }}">Sections</a>
                    </li>
                @endif
                <!-- Default Links Department Head-->
                @if(Auth::user()->college !== 'ADMIN' && Auth::user()->college !== 'ROOM COORDINATOR')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.schedule') }}">Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.faculty') }}">Faculty</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.subjects') }} ">Subjects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.sections') }}">Sections</a>
                    </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Hi, {{ Auth::user()->name }}!
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    @endauth

    @yield('content')

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    @yield('scripts') {{-- Additional scripts for specific views --}}
</body>
</html>
