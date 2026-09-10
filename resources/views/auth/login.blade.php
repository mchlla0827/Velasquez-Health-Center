<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Velasquez Health Center - Login</title>
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
            height: 450px;
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
            font-size: 22px;
            margin-bottom: 7px;
            color: #000;
            font-weight: bold;
        }

        .subtitle {
            font-size: 15px;
            color: #202020;
            margin-bottom: 30px;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }

        /* INPUT GROUP FIX */
        .input-group {
            text-align: left;
            width: 95%;
            margin-top: 23px;
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
            margin-top: 25px;
            cursor: pointer;
        }

        button:hover {
            background: #1e40af;
        }

        .forgot {
            margin-top: 15px;
            font-size: 13px;
        }

        .forgot a {
            color: #2563eb;
            text-decoration: none;
        }

        .forgot a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="card">

    <!-- LOGO -->
    <div class="logo">
        <img src="/bhclogo.jpg" width="80" style="margin-bottom:10px;">
    </div>

    <div class="title">Velasquez Health Center</div>
    <div class="subtitle">Health Information System</div>

    <!-- ERROR -->
    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- LOGIN FORM -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="your.email@example.com" required>
        </div>

        <!-- PASSWORD -->
        <div class="input-group">
    <label>Password</label>

    <div style="position: relative;">
        <input 
            type="password" 
            name="password" 
            id="password"
            placeholder="••••••••" 
            required
            style="width: 100%; padding-right: 10px;"
        >

        <!-- 👁️ ICON -->
        <svg id="togglePassword" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; cursor: pointer; opacity: 0.55; transition: 0.2s;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
    </div>
</div>

        <button type="submit">Login</button>
    </form>

    <!-- FORGOT PASSWORD -->
    <div class="forgot">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                Forgot Password?
            </a>
        @else
            <a href="#">
                Forgot Password?
            </a>
        @endif
    </div>

</div>
<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        const eyeOpenPath = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        const eyeClosedPath = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.6 18.6 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';

        togglePassword.addEventListener('click', function () {
            const isHidden = password.type === 'password';

            password.type = isHidden ? 'text' : 'password';

            this.innerHTML = isHidden ? eyeClosedPath : eyeOpenPath;
            this.style.opacity = isHidden ? "0.9" : "0.55";
        });
    }
</script>
</body>
</html>