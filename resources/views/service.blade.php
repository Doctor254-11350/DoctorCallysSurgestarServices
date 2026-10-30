<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title }} | DoctorCally's Surgestar Services</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f8fc;
            color: #172033;
        }

        header {
            background: #101d3a;
            padding: 22px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        header h2 {
            color: white;
        }

        header span {
            color: #20c997;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        .content {
            max-width: 950px;
            margin: 60px auto;
            padding: 25px;
        }

        .content h1 {
            color: #164e75;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .content > p {
            color: #64748b;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .items {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        }

        .items h2 {
            margin-bottom: 20px;
            color: #101d3a;
        }

        .resource {
            display: block;
            padding: 18px;
            margin-bottom: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            color: #164e75;
            font-weight: bold;
            transition: 0.3s;
        }

        .resource:hover {
            background: #e6fffa;
            border-color: #20c997;
            transform: translateX(5px);
        }

        .resource span {
            color: #20c997;
            float: right;
        }

        .button {
            display: inline-block;
            background: #20c997;
            color: #101d3a;
            padding: 14px 25px;
            margin-top: 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            background: #101d3a;
            color: white;
            text-align: center;
            padding: 25px;
        }

        @media (max-width: 600px) {
            .content {
                margin: 25px auto;
            }

            .content h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>DoctorCally's <span>Surgestar</span></h2>

    <a href="{{ route('dashboard') }}">My Dashboard</a>
</header>

<main class="content">

    <h1>{{ $title }}</h1>

    <p>{{ $description }}</p>

    <div class="items">

        <h2>Explore Your Learning Resources</h2>

        <p>
            Select any resource below to visit its official website
            and explore the available learning materials and tools.
        </p>

        @foreach ($resources as $resource)

            <a
                class="resource"
                href="{{ $resource['url'] }}"
                target="_blank"
                rel="noopener noreferrer"
            >
                {{ $resource['title'] }}

                <span>Visit →</span>
            </a>

        @endforeach

    </div>

    <a href="{{ route('dashboard') }}" class="button">
        Back to Dashboard
    </a>

</main>

<footer>
    @Daktari&copy; {{ date('Y') }} DoctorCally's Surgestar Services.
    All Rights Reserved.
</footer>

</body>
</html>