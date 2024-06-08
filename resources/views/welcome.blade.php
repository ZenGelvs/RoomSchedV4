<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OCCUPirate</title>
    <link rel="icon" type="image/png" href="{{ asset('treasure-map.png')}}">

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <!-- LPU COLORS
    --LPU-red: #b20000ea;
    --LPU-grey: #666666;
    --LPU-white: #fff;
    -->
    <style>
        body {
            background-color: #f8f9fa;
            position: relative;
            min-height: 100vh;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            animation: slideInAnimation ease 1s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }

        @keyframes slideInAnimation {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label, h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }

        h2 {
            font-weight: bold;
        }

        .btn-danger {
            background-color: #b20000ea;
            border-color: #b20000ea;
        }

        .btn-danger:hover {
            background-color: #800000ea;
            border-color: #800000ea;
        }

        .footer {
            position: fixed;
            bottom: -60px; /* Initially hidden */
            width: 100%;
            background-color: #b20000ea;
            color: #fff;
            padding: 20px 0;
            transition: bottom 0.3s ease;
        }

        .footer:hover {
            bottom: 0;
        }

        /* Additional style for logo card */
        .logo-card {
            text-align: center;
            margin-bottom: 20px;
            animation: slideInAnimation ease 1s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }

        .logo-card img {
            max-width: 100%;
            height: auto;
            animation: pulseAnimation 1s infinite alternate;
        }

        @keyframes pulseAnimation {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.02);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="logo-card">
                <img src="{{ asset('OccuPirate (maroon transaprent).png') }}" alt="Logo">
            </div>
            <h2 class="text-center mb-4">LOGIN</h2>
            
            <!-- Login Form -->
            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Username:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
            
                <button type="submit" class="btn btn-danger btn-block">Login</button>
            </form>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <p style="margin-bottom: 20px;">Copyright © Lyceum of the Philippines University - Cavite 2024. All Rights Reserved.</p>
                    <p>Built with Laravel, a PHP-based web framework for web development.</p>
                    <p>Copyright © 2024 Laravel, All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
</body>
</html>
