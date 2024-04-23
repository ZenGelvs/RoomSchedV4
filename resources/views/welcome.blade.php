<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OCCUPIrate</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

     <!-- LPU COLORS
    --LPU-red: #b20000ea;
    --LPU-grey: #666666;
    --LPU-white: #fff;
    -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Afacad&display=swap');

        body {
            background-color: #f8f9fa;
            position: relative;
            min-height: 100vh;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            padding: 20px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            background-color: #ffffff;
        }

        label {
            font-family: 'Afacad', sans-serif;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #b20000ea;
            color: #fff;
            padding: 20px 0;
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Login</h2>
            
            <!-- Login Form -->
            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
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
                    <p style="margin-bottom: 20px;">Copyright © Lyceum of the Philippines University - Cavite 2024. All Rights Reserved.</p><br>
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