<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PLN Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #e4e4e4; /* Light grey background like the screenshot */
            font-family: 'Poppins', sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            border-radius: 25px; /* Distinct rounded corners */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden; /* Ensures the image respects the border radius */
            border: none;
            width: 100%;
            max-width: 900px; /* Wider card to accommodate the split */
        }

        /* Left Side Styling */
        .form-section {
            padding: 50px;
        }

        .brand-text {
            color: #17a2b8; /* Teal color */
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
            display: block;
        }

        .login-title {
            font-weight: 700;
            font-size: 2.5rem;
            color: #000;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #17a2b8;
        }

        .btn-custom {
            background-color: #17a2b8; /* Match the teal button */
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-custom:hover {
            background-color: #138496;
            color: white;
        }

        /* Right Side Styling */
        .image-section {
            /* Linear Gradient Overlay + Background Image */
            /* REPLACE 'background.jpg' with your actual building image path */
            background: linear-gradient(rgba(23, 162, 184, 0.85), rgba(23, 162, 184, 0.85)), url('assets/icon/bangunan.png');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 40px;
        }
        
        .pln-text {
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .system-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .system-desc {
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="card login-card">
            <div class="row g-0">
                <div class="col-md-6 form-section">
                    <span class="brand-text">TI FV USU</span>
                    <h1 class="login-title">Login</h1>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Username</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Username">
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="*************">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-custom w-100">Login</button>
                    </form>
                </div>

                <div class="col-md-6 image-section d-none d-md-flex">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('assets/icon/pln.svg') }}" alt="Profile" width="45" height="45">
                        <span class="pln-text ms-3 ">PLN</span>
                    </div>

                    <h2 class="system-title">Sistem Monitoring<br>Tiang Listrik</h2>
                    <p class="system-desc">
                        Pantau sebaran tiang listrik yang terkait dengan<br>
                        PT PLN (Persero) UP3 Lubuk Pakam secara mendetail dan akurat.
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>