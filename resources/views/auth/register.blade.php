```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | DoctorCally's Surgestar Services</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            margin: 0;
            padding: 30px 15px;
        }

        .register-box {
            max-width: 450px;
            margin: 30px auto;
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
            margin-top: 16px;
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
<div class="register-box">

    <h1>Create Your Account</h1>

    <p>Registration is free. Activate your account after registration.</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="name">Full Name</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
            autocomplete="name"
        >

        <label for="email">Email Address</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="email"
        >

        <label for="password">Password</label>
        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="new-password"
        >

        <label for="password_confirmation">Confirm Password</label>
        <input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
        >

        <button type="submit">Create Account</button>
    </form>

    <p>
        Already registered?
        <a href="{{ route('login') }}">Log in here</a>
    </p>

    <p><a href="/">Return to Home</a></p>

</div>
</body>
</html>

