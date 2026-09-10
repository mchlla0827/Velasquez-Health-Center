<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Velasquez Health Center</title>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 330px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        .logo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .title {
            font-size: 20px;
            margin-bottom: 7px;
            color: #000;
            font-weight: bold;
        }

        .subtitle {
            font-size: 13.5px;
            color: #4B5563;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .status {
            color: #166534;
            background: #DCFCE7;
            border: 1px solid #86EFAC;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group {
            text-align: left;
            width: 95%;
            margin-top: 10px;
        }

        .input-group label {
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
        }

        button {
            width: 103%;
            padding: 10px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            margin-top: 20px;
            cursor: pointer;
        }

        button:hover {
            background: #1e40af;
        }

        .back-link {
            margin-top: 15px;
            font-size: 13px;
        }

        .back-link a {
            color: #2563eb;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="card">

    <div class="logo">
        <img src="/bhclogo.jpg" width="80" style="margin-bottom:10px;">
    </div>

    <div class="title">Forgot Password</div>
    <div class="subtitle">Enter your registered email address and we'll send you a link to reset your password.</div>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com" required autofocus>
        </div>

        <button type="submit">Send Password Reset Link</button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">&larr; Back to Login</a>
    </div>

</div>

</body>
</html>