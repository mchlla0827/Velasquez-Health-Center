<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Velasquez Health Center</title>
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
            width: 360px;
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
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
            text-align: left;
        }

        .input-group {
            text-align: left;
            width: 90%;
            margin-top: 15px;
        }

        .input-group label {
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        .input-group input { box-sizing: border-box; width: 112%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; outline: none; }

        .password-wrap {
            position: relative;
        }

        .password-wrap input { padding-right: 34px; }

        .toggle-eye { position: absolute; right: -18px; top: 50%; transform: translateY(-50%); cursor: pointer; width: 18px; height: 18px; opacity: 0.5; transition: opacity 0.15s; }

        .toggle-eye:hover {
            opacity: 0.9;
        }

        button {
            width: 103%;
            padding: 10px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            margin-top: 22px;
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

    <div class="title">Reset Password</div>
    <div class="subtitle">Enter your new password below.</div>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="your.email@example.com" required autofocus>
        </div>

        <div class="input-group">
            <label>New Password</label>
            <div class="password-wrap">
                <input type="password" name="password" id="newPassword" placeholder="********" required>
                <svg class="toggle-eye" id="toggleNewPassword" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
        </div>

        <div class="input-group">
            <label>Confirm New Password</label>
            <div class="password-wrap">
                <input type="password" name="password_confirmation" id="confirmPassword" placeholder="********" required>
                <svg class="toggle-eye" id="toggleConfirmPassword" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
        </div>

        <button type="submit">Reset Password</button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">&larr; Back to Login</a>
    </div>

</div>

<script>
    function setupPasswordToggle(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        if (!input || !toggle) return;

        const eyeOpenPath = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        const eyeClosedPath = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.6 18.6 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';

        toggle.addEventListener('click', function () {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            toggle.innerHTML = isHidden ? eyeClosedPath : eyeOpenPath;
            toggle.style.opacity = isHidden ? '0.9' : '0.55';
        });
    }

    setupPasswordToggle('newPassword', 'toggleNewPassword');
    setupPasswordToggle('confirmPassword', 'toggleConfirmPassword');
</script>

</body>
</html>