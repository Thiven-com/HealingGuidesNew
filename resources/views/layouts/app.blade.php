<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="@yield('meta_description', 'Healing Guides Wellness Services')">

    <title>
        @yield('title', 'Healing Guides Wellness Services')
    </title>


    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <style>
        :root {
            --primary: #7616ad;
            --primary-dark: #4d087d;
            --primary-light: #f7effd;
            --secondary: #ff5638;
            --orange: #ff8a20;
            --dark: #171326;
            --text: #565267;
            --light: #faf9fc;
            --white: #ffffff;
            --border: #eee8f3;
            --success: #20a464;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--dark);
            background: #fff;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .main-navbar {
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid #f1edf5;

            box-shadow:
                0 5px 25px rgba(57, 25, 77, .05);

            padding: 10px 0;

            z-index: 1050;
        }

        .nav-logo {
            width: 280px;
            max-width: 100%;
            height: 74px;
            object-fit: contain;
        }

        .navbar-nav {
            align-items: center;
            gap: 8px;
        }

        .navbar-nav .nav-link {
            color: #312c3c;
            font-weight: 600;
            font-size: 15px;

            padding:
                12px 14px !important;

            transition: .3s;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary);
        }

        .navbar-nav .nav-link.active {
            color: var(--primary);
        }

        .nav-contact-btn {
            background:
                linear-gradient(135deg,
                    var(--primary),
                    #a61bd2);

            color: #fff !important;

            border-radius: 12px;

            padding:
                12px 22px !important;

            box-shadow:
                0 8px 20px rgba(118, 22, 173, .20);
        }

        .nav-contact-btn:hover {
            transform: translateY(-1px);

            box-shadow:
                0 12px 25px rgba(118, 22, 173, .28);
        }


        /* =====================================================
           COMMON PAGE HERO
        ===================================================== */

        .page-hero {

            min-height: 360px;

            display: flex;
            align-items: center;

            position: relative;
            overflow: hidden;

            background:

                radial-gradient(circle at 90% 10%,
                    rgba(255, 95, 54, .14),
                    transparent 28%),

                radial-gradient(circle at 8% 80%,
                    rgba(118, 22, 173, .13),
                    transparent 32%),

                linear-gradient(135deg,
                    #fff 0%,
                    #fcf8ff 48%,
                    #fff8f5 100%);
        }

        .page-hero::before {
            content: "";

            position: absolute;

            width: 370px;
            height: 370px;

            border-radius: 50%;

            background:
                rgba(118, 22, 173, .05);

            top: -200px;
            left: -100px;
        }

        .page-hero::after {
            content: "";

            position: absolute;

            width: 330px;
            height: 330px;

            border-radius: 50%;

            border:
                70px solid rgba(255, 86, 56, .035);

            right: -120px;
            bottom: -160px;
        }

        .page-hero-content {
            position: relative;
            z-index: 2;
        }

        .page-badge {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 8px 16px;

            background: #fff;

            border: 1px solid #eee2f6;

            border-radius: 50px;

            color: var(--primary);

            font-size: 12px;
            font-weight: 800;

            text-transform: uppercase;
            letter-spacing: .7px;

            box-shadow:
                0 6px 25px rgba(68, 28, 90, .06);

            margin-bottom: 20px;
        }

        .page-badge span {
            width: 8px;
            height: 8px;

            border-radius: 50%;

            background:
                var(--secondary);
        }

        .page-hero h1 {
            font-size: 52px;

            line-height: 1.1;

            font-weight: 800;

            letter-spacing: -1.5px;

            margin-bottom: 18px;
        }

        .page-hero h1 span {

            background:
                linear-gradient(90deg,
                    var(--primary),
                    #a516bf,
                    var(--secondary));

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;
        }

        .page-hero p {
            max-width: 700px;

            margin: auto;

            color: var(--text);

            font-size: 17px;

            line-height: 1.8;
        }


        /* =====================================================
           COMMON BUTTON
        ===================================================== */

        .btn-gradient {

            background:
                linear-gradient(135deg,
                    var(--primary),
                    #a516c3);

            border: none;

            color: #fff;

            padding: 14px 26px;

            border-radius: 12px;

            font-weight: 700;

            box-shadow:
                0 10px 25px rgba(118, 22, 173, .23);

            transition: .3s;
        }

        .btn-gradient:hover {

            color: #fff;

            transform:
                translateY(-2px);

            box-shadow:
                0 14px 30px rgba(118, 22, 173, .30);
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        footer {

            background: #171322;

            color: #fff;

            padding:
                75px 0 25px;
        }

        .footer-logo {
            width: 260px;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .footer-about {
            color: #a9a3b0;

            line-height: 1.8;

            font-size: 14px;

            max-width: 380px;
        }

        .footer-title {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #aaa4b1;
            font-size: 14px;
            transition: .3s;
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 4px;
        }

        .social-link {

            width: 40px;
            height: 40px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            background:
                rgba(255, 255, 255, .08);

            color: #fff;

            border-radius: 10px;

            margin-right: 7px;

            transition: .3s;
        }

        .social-link:hover {
            color: #fff;

            background:
                var(--primary);

            transform:
                translateY(-3px);
        }

        .copyright {

            border-top:
                1px solid rgba(255, 255, 255, .08);

            margin-top: 45px;

            padding-top: 22px;

            color: #8f8997;

            font-size: 13px;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 991px) {

            .nav-logo {
                width: 220px;
                height: 60px;
            }

            .navbar-nav {
                align-items: stretch;
                padding: 15px 0;
            }

        }

        @media (max-width: 767px) {

            .page-hero {
                min-height: 320px;
                padding: 55px 0;
            }

            .page-hero h1 {
                font-size: 38px;
            }

            .page-hero p {
                font-size: 15px;
            }

        }
    </style>


    {{-- Page Specific CSS --}}
    @stack('styles')

</head>


<body>



    {{-- ============================================================
    NAVBAR
    ============================================================ --}}

    <nav class="navbar navbar-expand-lg main-navbar sticky-top">

        <div class="container">


            <a class="navbar-brand" href="{{ url('/') }}">

                <img src="{{ asset('logo/logo.png') }}" alt="Healing Guides" class="nav-logo">

            </a>


            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainMenu">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse" id="mainMenu">

                <ul class="navbar-nav ms-auto">


                    <li class="nav-item">

                        <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">

                            Home

                        </a>

                    </li>


                    <li class="nav-item">

                        <a href="{{ url('/#apps') }}" class="nav-link">

                            Applications

                        </a>

                    </li>


                    <li class="nav-item">

                        <a href="{{ url('/#features') }}" class="nav-link">

                            Features

                        </a>

                    </li>


                    <li class="nav-item">

                        <a href="{{ url('/#about') }}" class="nav-link">

                            How It Works

                        </a>

                    </li>


                    <li class="nav-item">

                        <a href="{{ route('privacy-policy') }}" class="nav-link
                       {{ request()->routeIs('privacy-policy') ? 'active' : '' }}">

                            Privacy Policy

                        </a>

                    </li>


                    <li class="nav-item ms-lg-2">

                        <a href="{{ url('/#contact') }}" class="nav-link nav-contact-btn">

                            Contact Us

                        </a>

                    </li>


                </ul>

            </div>

        </div>

    </nav>



    {{-- ============================================================
    PAGE CONTENT
    ============================================================ --}}

    @yield('content')



    {{-- ============================================================
    FOOTER
    ============================================================ --}}

    <footer>

        <div class="container">


            <div class="row g-4">


                {{-- Company --}}

                <div class="col-lg-5">

                    <img src="{{ asset('logo/logo.png') }}" class="footer-logo" alt="Healing Guides">


                    <p class="footer-about">

                        Connecting customers, doctors, hospitals,
                        diagnostics, ambulances and medical services
                        through one powerful digital healthcare ecosystem.

                    </p>


                    <div class="mt-4">


                        <a href="#" class="social-link">

                            <i class="fa-brands fa-facebook-f"></i>

                        </a>


                        <a href="#" class="social-link">

                            <i class="fa-brands fa-instagram"></i>

                        </a>


                        <a href="#" class="social-link">

                            <i class="fa-brands fa-linkedin-in"></i>

                        </a>


                        <a href="#" class="social-link">

                            <i class="fa-brands fa-x-twitter"></i>

                        </a>


                    </div>

                </div>



                {{-- Quick Links --}}

                <div class="col-lg-2 col-md-4">

                    <h5 class="footer-title">
                        Quick Links
                    </h5>


                    <ul class="footer-links">

                        <li>
                            <a href="{{ url('/') }}">
                                Home
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/#apps') }}">
                                Applications
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/#features') }}">
                                Features
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/#about') }}">
                                How It Works
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/#contact') }}">
                                Contact
                            </a>
                        </li>

                    </ul>

                </div>



                {{-- Legal --}}

                <div class="col-lg-2 col-md-4">

                    <h5 class="footer-title">
                        Legal
                    </h5>


                    <ul class="footer-links">

                        <li>

                            <a href="{{ route('privacy-policy') }}">
                                Privacy Policy
                            </a>

                        </li>


                        <li>

                            <a href="{{ route('terms-and-conditions') }}">
                                Terms & Conditions
                            </a>

                        </li>


                        <li>

                            <a href="{{ route('account-deletion') }}">
                                Account Deletion
                            </a>

                        </li>

                    </ul>

                </div>



                {{-- Contact --}}

                <div class="col-lg-3 col-md-4">

                    <h5 class="footer-title">
                        Contact
                    </h5>


                    <ul class="footer-links">


                        <li>

                            <a href="tel:+919876543210">

                                <i class="fa-solid fa-phone me-2"></i>

                                +91 98765 43210

                            </a>

                        </li>


                        <li>

                            <a href="mailto:support@healingguides.in">

                                <i class="fa-solid fa-envelope me-2"></i>

                                support@healingguides.in

                            </a>

                        </li>


                        <li>

                            <a href="#">

                                <i class="fa-solid fa-location-dot me-2"></i>

                                H NO - 37 - 103/2
                                SREE COLONEY NEREDMET X ROADS
                                SECUNDERABAD. 50006. TELANGANA

                            </a>

                        </li>


                    </ul>

                </div>


            </div>



            <div class="copyright">

                <div class="row align-items-center">


                    <div class="col-md-6">

                        © {{ date('Y') }}
                        Healing Guides Wellness Services.
                        All Rights Reserved, Developed by <a href="https://www.thiven.com" style="color: #8f8997">Thiven</a>

                    </div>


                    <div class="col-md-6 text-md-end mt-2 mt-md-0">

                        Privacy • Security • Trusted Healthcare

                    </div>


                </div>

            </div>


        </div>

    </footer>



    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    {{-- Page Specific Scripts --}}
    @stack('scripts')


</body>

</html>