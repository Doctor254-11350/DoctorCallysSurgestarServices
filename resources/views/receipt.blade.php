<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation | DoctorCally's Surgestar Services</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            padding: 25px;
        }

        .receipt {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,.08);
        }

        h1 {
            color: #087f5b;
        }

        .serial {
            background: #edf7f2;
            padding: 15px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            background: #123c69;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="receipt">

    <h1>Payment Successful!</h1>

    <p>Your payment has been verified successfully.</p>

    <p><strong>Receipt Number</strong></p>

    <div class="serial">
        {{ $payment->receipt_number }}
    </div>

    <p>
        <strong>Service:</strong>
        {{ $payment->service_name }}
    </p>

    <p>
        <strong>Amount:</strong>
        KSh {{ number_format((float) $payment->amount, 2) }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ strtoupper($payment->status) }}
    </p>

    <p>
        <strong>Date:</strong>
        {{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Not available' }}
    </p>

    <p>
        Thank you for using DoctorCally's Surgestar Services.
    </p>

    <a href="{{ route('dashboard') }}" class="button">
        Return to Dashboard
    </a>

    <button onclick="window.print()" class="button">
        Print Receipt
    </button>

</div>

</body>
</html>