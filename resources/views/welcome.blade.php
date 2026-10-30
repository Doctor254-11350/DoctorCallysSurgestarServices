<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DoctorCally's Surgestar Services</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f7f9fc;
            color: #172033;
        }

        header {
            background: #101d3a;
            color: white;
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo {
            font-size: 23px;
            font-weight: bold;
        }

        .logo span {
            color: #20c997;
        }

        nav {
            display: flex;
            gap: 25px;
            align-items: center;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        nav a:hover {
            color: #20c997;
        }

        .hero {
            background: linear-gradient(120deg, #101d3a, #164e75);
            color: white;
            padding: 100px 7%;
            text-align: center;
        }

        .hero h1 {
            font-size: 45px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 17px;
            line-height: 1.8;
            max-width: 700px;
            margin: auto;
            color: #dce8f5;
        }

        .button {
            display: inline-block;
            margin: 25px 8px 0;
            padding: 14px 28px;
            background: #20c997;
            color: #101d3a;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .button:hover {
            background: #17a77d;
        }

        .section {
            padding: 75px 7%;
            text-align: center;
        }

        .section h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .section-intro {
            color: #64748b;
            margin-bottom: 40px;
        }

        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 35px 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
            text-align: left;
        }

        .card h3 {
            margin-bottom: 15px;
            color: #164e75;
        }

        .card p {
            color: #64748b;
            line-height: 1.7;
        }

        .card a {
            display: inline-block;
            margin-top: 20px;
            color: #0b9871;
            text-decoration: none;
            font-weight: bold;
        }

        .about {
            background: white;
        }

        .about-content {
            max-width: 800px;
            margin: auto;
            line-height: 1.9;
            color: #64748b;
        }

        .cta {
            background: #e9f7f3;
            padding: 65px 7%;
            text-align: center;
        }

        footer {
            background: #101d3a;
            color: white;
            text-align: center;
            padding: 30px;
            line-height: 2;
        }

        @media(max-width: 700px) {
            header {
                gap: 20px;
            }

            nav {
                gap: 15px;
            }

            .hero h1 {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="logo">
        DoctorCally's <span>Surgestar</span>
    </div>

    <nav>
        <a href="/">Home</a>
        <a href="#services">Services</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    </nav>
</header>

<section class="hero">
    <h1>Welcome to DoctorCally's Surgestar Services</h1>

    <p>
        Your destination for technology, programming, digital solutions
        and educational resources. Learn new skills, explore opportunities
        and access reliable online services.
    </p>

    <a href="{{ route('register') }}" class="button">Get Started</a>
    <a href="#services" class="button">Explore Services</a>
</section>

<section class="section" id="services">
    <h2>Our Services</h2>

    <p class="section-intro">
        Explore our services and discover opportunities to grow.
    </p>

    <div class="services">

        <div class="card">
            <h3>Educational Resources</h3>
            <p>
                Access learning materials, programming tutorials,
                technology guides and useful educational resources.
            </p>
            <a href="{{ route('checkout', ['service' => 'education']) }}">
    Explore Educational Resources
</a>
        </div>

        <div class="card">
            <h3>Programming & Coding</h3>
            <p>
                Learn HTML, CSS, JavaScript, PHP, Laravel,
                Python and other programming technologies.
            </p>
            <a href="{{ route('checkout', ['service' => 'programming']) }}">
    Explore Programming & Coding
</a>
        </div>

        <div class="card">
            <h3>Website Development</h3>
            <p>
                Access website design, development and
                digital solutions for individuals and businesses.
            </p>
            <a href="{{ route('checkout', ['service' => 'website']) }}">
    Explore Website Development
</a>
        </div>

        <div class="card">
            <h3>Online Services</h3>
            <p>
                Discover digital services, technology assistance
                and other online solutions.
            </p>
    <a href="{{ route('checkout', ['service' => 'online']) }}">
    Explore Online Services
</a>
        </div>

    </div>
</section>

<section class="section about" id="about">
    <h2>About Us</h2>

    <div class="about-content">
        <p>
            DoctorCally's Surgestar Services is a digital platform
            focused on providing educational resources, programming
            knowledge and online technology services.
        </p>

        <p>
            Our goal is to make technology and digital opportunities
            more accessible by providing useful resources and services
            through one convenient platform.
        </p>
    </div>
</section>

<section class="cta" id="contact">
    <h2>Ready to Get Started?</h2>

    <p>Create your account today and explore what we offer.</p>

    <a href="{{ route('register') }}" class="button">
        Create Your Account
    </a>
</section>

<footer>
    <p>© {{ date('Y') }} DoctorCally's Surgestar Services.</p>
    <p>All Rights Reserved.</p>
</footer>

</body>
</html>