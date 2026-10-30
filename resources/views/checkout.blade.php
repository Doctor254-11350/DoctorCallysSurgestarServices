<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | DoctorCally's Surgestar Services</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        header {
            background: #0f172a;
            color: white;
            padding: 22px 8%;
        }

        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 850px;
            margin: 40px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
        }

        h1 {
            color: #0f766e;
        }

        .service {
            display: block;
            padding: 18px;
            margin: 15px 0;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            cursor: pointer;
        }

        .service:hover {
            border-color: #0f766e;
            background: #f0fdfa;
        }

        .service input {
            margin-right: 10px;
        }

        .price {
            color: #0f766e;
            font-weight: bold;
        }

        input[type="tel"] {
            width: 100%;
            padding: 14px;
            margin: 10px 0 20px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 15px;
            background: #0f766e;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #115e59;
        }

        .message {
            padding: 13px;
            margin-bottom: 15px;
            border-radius: 7px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #64748b;
        }
    </style>
</head>

<body>

<header>
    <a href="/">DoctorCally's Surgestar Services</a>
</header>

<div class="container">

    <h1>Purchase a Service</h1>

    <p>Select the service you want to access. Each service is paid for separately.</p>

    @if(session('info'))
        <div class="message success">
            {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="message error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="message error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">

        @csrf

        <h2>Available Services</h2>

        @foreach($services as $id => $service)

            <label class="service">

                <input
                    type="radio"
                    name="service"
                    value="{{ $id }}"
                    {{ old('service', $selectedService) === $id ? 'checked' : '' }}
                    required
                >

                <strong>{{ $service['name'] }}</strong>

                <p>{{ $service['description'] }}</p>

                <span class="price">
                    KSh {{ number_format($service['price']) }}
                </span>

            </label>

        @endforeach

        <h2>M-Pesa Payment Details</h2>

        <label for="phone_number">M-Pesa Phone Number</label>

        <input
            type="tel"
            id="phone_number"
            name="phone_number"
            placeholder="0712345678"
            value="{{ old('phone_number') }}"
            required
        >

        <button type="submit">
            Pay with M-Pesa
        </button>

    </form>

</div>

<footer>
    &copy; {{ date('Y') }} DoctorCally's Surgestar Services.
    All Rights Reserved.
</footer>

</body>
</html>