<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AFE Sport</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #e67e22;
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-check label {
            font-size: 14px;
            color: #666;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(230, 126, 34, 0.4);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #e67e22;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .demo-accounts {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 25px;
            font-size: 13px;
        }

        .demo-accounts h4 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #e67e22;
        }

        .demo-accounts p {
            margin: 5px 0;
            color: #666;
        }

        .demo-accounts strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('images/logo.png') }}" alt="AFE Sport" style="height: 50px; width: auto; margin-bottom: 15px;">
            <h1>AFE Sport</h1>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                       required placeholder="Masukkan password">
            </div>

            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="back-link">
            <a href="{{ route('home') }}">← Kembali ke Beranda</a>
        </div>

        <!-- Demo Accounts Info -->
        <div class="demo-accounts">
            <h4>🔑 Akun Demo:</h4>
            <p><strong>Admin:</strong> admin@tapem.com / admin123</p>
            <p><strong>Customer:</strong> customer@tapem.com / customer123</p>
        </div>
    </div>
</body>
</html>
