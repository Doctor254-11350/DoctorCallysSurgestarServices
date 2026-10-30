<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | DoctorCally's Surgestar Services</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            margin: 0;
            color: #1f2937;
        }

        header {
            background: #123c69;
            color: white;
            padding: 22px;
        }

        main {
            max-width: 1000px;
            margin: 35px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #eef3f9;
        }

        .button {
            display: inline-block;
            background: #087f5b;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
        }

        .status {
            font-weight: bold;
        }

        @media (max-width: 650px) {
            .table-wrapper {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>DoctorCally's Surgestar Services</h1>
    <p>Customer Dashboard</p>
</header>

<main>

    <section class="card">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>

        <p>
            Manage your services, view your payment history,
            and access your receipts here.
        </p>

        <a href="/" class="button">Explore Services</a>
    </section>

    
        <section class="card">
        <h2>My Purchased Services</h2>

        @php
            $serviceLinks = [
                'Educational Resources' => 'education',
                'Programming & Coding' => 'programming',
                'Website Development' => 'website',
                'Online Services' => 'online',
            ];

            $purchasedServices = $payments
                ->where('status', 'paid')
                ->pluck('service_name')
                ->unique();
        @endphp

        @if ($purchasedServices->isEmpty())

            <p>You have not purchased any services yet.</p>

        @else

            @foreach ($purchasedServices as $serviceName)

                @if (isset($serviceLinks[$serviceName]))

                    <p style="margin: 15px 0;">
                        <strong>{{ $serviceName }}</strong>

                        <a
                            href="{{ route('services.show', $serviceLinks[$serviceName]) }}"
                            class="button"
                        >
                            Access Service
                        </a>
                    </p>

                @endif

            @endforeach

        @endif

    </section>
    <section class="card">
        <h2>My Payments and Receipts</h2>

        @if ($payments->isEmpty())

            <p>You have no payment records yet.</p>

        @else

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Receipt Number</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>
                                    @if ($payment->status === 'paid')
                                        {{ $payment->receipt_number }}
                                    @else
                                        Not yet issued
                                    @endif
                                </td>

                                <td>{{ $payment->service_name }}</td>

                                <td>
                                    KSh {{ number_format((float) $payment->amount, 2) }}
                                </td>

                                <td class="status">
                                    @if ($payment->status === 'paid')
                                        Payment Successful
                                    @elseif ($payment->status === 'pending')
                                        Awaiting Payment
                                    @else
                                        Payment Not Completed
                                    @endif
                                </td>

                                <td>
                                    @if ($payment->status === 'paid')
                                        <a href="{{ route('payments.receipt', $payment) }}">
                                            View Receipt
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </section>

</main>

</body>
</html>