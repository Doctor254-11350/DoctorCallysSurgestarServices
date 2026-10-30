```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login | DoctorCally's Surgestar Services</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            margin: 0;
            padding: 30px 15px;
        }

        .login-box {
            max-width: 420px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        h1 {
            color: #123c69;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 18px;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            background: #087f5b;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .error {
            color: #b42318;
        }

        a {
            color: #123c69;
        }
    </style>
</head>

<body>
<div class="login-box">

    <h1>Customer Login</h1>

    <p>Log in to access your services and payment receipts.</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email Address</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="username"
        >

        <label for="password">Password</label>
        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
        >

        <button type="submit">Log In</button>
    </form>

    <p>
        Don't have an account?
        <a href="{{ route('register') }}">Register here</a>
    </p>

    <p>
        <a href="{{ route('password.request') }}">Forgot your password?</a>
    </p>

    <p><a href="/">Return to Home</a></p>

</div>
</body>
</html>
```
