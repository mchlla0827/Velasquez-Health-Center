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
        <img  
            id="togglePassword"
            style="
                position: absolute;
                right: 1px;
                top: 50%;
                transform: translateY(-50%);
                width: 20px;
                cursor: pointer;
                opacity: 0.5;
                transition: 0.2s;
            "
        >
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
        togglePassword.addEventListener('click', function () {
            const isHidden = password.type === 'password';

            password.type = isHidden ? 'text' : 'password';

            this.style.opacity = isHidden ? "1" : "0.5";
            this.style.filter = isHidden ? "brightness(0) saturate(100%) invert(32%) sepia(98%) saturate(1863%) hue-rotate(213deg)" : "none";
        });
    }
</script>
</body>
</html>