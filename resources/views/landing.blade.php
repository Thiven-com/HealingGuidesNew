<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Healing Guides Wellness Services</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

        /* =========================
           NAVBAR
        ========================= */

        .main-navbar {
            background: rgba(255,255,255,.96);
            border-bottom: 1px solid #f1edf5;
            box-shadow: 0 5px 25px rgba(57, 25, 77, .05);
            padding: 10px 0;
            z-index: 1000;
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
            padding: 12px 14px !important;
            transition: .3s;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary);
        }

        .nav-contact-btn {
            background: linear-gradient(135deg, var(--primary), #a61bd2);
            color: #fff !important;
            border-radius: 12px;
            padding: 12px 22px !important;
            box-shadow: 0 8px 20px rgba(118,22,173,.20);
        }

        /* =========================
           HERO
        ========================= */

        .hero {
            min-height: 680px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 90% 10%, rgba(255, 95, 54, .12), transparent 27%),
                radial-gradient(circle at 8% 80%, rgba(118, 22, 173, .10), transparent 30%),
                linear-gradient(135deg, #fff 0%, #fcf8ff 48%, #fff8f5 100%);
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: rgba(118,22,173,.05);
            top: -180px;
            left: -120px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            background: #fff;
            border: 1px solid #eee2f6;
            border-radius: 50px;
            color: var(--primary);
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 6px 25px rgba(68,28,90,.06);
            margin-bottom: 22px;
        }

        .hero-badge span {
            width: 8px;
            height: 8px;
            background: var(--secondary);
            border-radius: 50%;
        }

        .hero h1 {
            font-size: 60px;
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: -2px;
            margin-bottom: 22px;
        }

        .hero h1 span {
            background: linear-gradient(90deg, var(--primary), #a516bf, var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            color: var(--text);
            font-size: 18px;
            line-height: 1.8;
            max-width: 620px;
            margin-bottom: 30px;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), #a516c3);
            border: none;
            color: #fff;
            padding: 14px 26px;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(118,22,173,.23);
            transition: .3s;
        }

        .btn-gradient:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(118,22,173,.30);
        }

        .btn-outline-custom {
            border: 1px solid #ded4e6;
            background: #fff;
            color: var(--dark);
            padding: 14px 25px;
            border-radius: 12px;
            font-weight: 700;
            transition: .3s;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .hero-trust {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 35px;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #625c6c;
            font-size: 14px;
            font-weight: 600;
        }

        .trust-item i {
            color: var(--success);
        }

        /* HERO RIGHT */

        .hero-visual {
            position: relative;
            height: 530px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-circle {
            width: 390px;
            height: 390px;
            border-radius: 50%;
            background: linear-gradient(145deg, var(--primary), #9d1bc3, var(--secondary));
            box-shadow: 0 30px 70px rgba(111,22,166,.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            position: relative;
        }

        .hero-circle::after {
            content: "";
            position: absolute;
            inset: 20px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.22);
        }

        .hero-circle i {
            font-size: 160px;
        }

        .floating-card {
            position: absolute;
            background: #fff;
            border: 1px solid #f0e9f4;
            border-radius: 16px;
            padding: 15px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 190px;
            box-shadow: 0 15px 40px rgba(57,27,75,.13);
        }

        .float-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            flex-shrink: 0;
        }

        .float-icon.purple {
            background: linear-gradient(135deg, #7b1bb3, #a728cf);
        }

        .float-icon.orange {
            background: linear-gradient(135deg, #ff8a21, #ff5138);
        }

        .float-icon.green {
            background: linear-gradient(135deg, #22b477, #138b5c);
        }

        .floating-card h6 {
            margin: 0 0 2px;
            font-weight: 700;
            font-size: 14px;
        }

        .floating-card small {
            color: #8b8593;
        }

        .card-doctor {
            top: 55px;
            left: 0;
        }

        .card-ambulance {
            right: -15px;
            top: 210px;
        }

        .card-pharmacy {
            left: 30px;
            bottom: 40px;
        }

        /* =========================
           COMMON
        ========================= */

        .section-space {
            padding: 90px 0;
        }

        .section-badge {
            display: inline-block;
            color: var(--primary);
            background: var(--primary-light);
            border-radius: 50px;
            padding: 7px 15px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .section-heading {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 14px;
        }

        .section-heading span {
            color: var(--primary);
        }

        .section-description {
            color: #746e7d;
            font-size: 16px;
            line-height: 1.7;
            max-width: 680px;
            margin: auto;
        }

        /* =========================
           APPLICATIONS
        ========================= */

        .apps-section {
            background: #fff;
        }

        .app-card {
            height: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 28px;
            transition: .35s;
            position: relative;
            overflow: hidden;
        }

        .app-card::before {
            content: "";
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--primary-light);
            right: -45px;
            top: -45px;
            transition: .35s;
        }

        .app-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 50px rgba(66,25,88,.10);
            border-color: transparent;
        }

        .app-card:hover::before {
            transform: scale(1.4);
        }

        .app-icon {
            width: 65px;
            height: 65px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            color: #fff;
            margin-bottom: 22px;
        }

        .purple-bg {
            background: linear-gradient(135deg, #6f18aa, #a52bd0);
        }

        .orange-bg {
            background: linear-gradient(135deg, #ff8d21, #ff5138);
        }

        .green-bg {
            background: linear-gradient(135deg, #22b475, #138a5c);
        }

        .blue-bg {
            background: linear-gradient(135deg, #268cff, #5757e8);
        }

        .red-bg {
            background: linear-gradient(135deg, #ff5d62, #e82b42);
        }

        .app-card h4 {
            font-size: 21px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .app-card p {
            color: #777181;
            font-size: 14px;
            line-height: 1.7;
        }

        .app-features {
            list-style: none;
            padding: 0;
            margin: 20px 0 0;
        }

        .app-features li {
            padding: 8px 0;
            color: #5e5865;
            font-size: 14px;
        }

        .app-features i {
            width: 22px;
            color: var(--success);
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--primary);
            font-weight: 700;
            font-size: 14px;
            margin-top: 15px;
        }

        /* =========================
           FEATURES
        ========================= */

        .features-section {
            background: #faf8fc;
        }

        .feature-card {
            background: #fff;
            border: 1px solid #eee8f2;
            border-radius: 20px;
            padding: 30px 24px;
            height: 100%;
            transition: .3s;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 35px rgba(65,30,80,.08);
        }

        .feature-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 23px;
            margin-bottom: 20px;
        }

        .feature-card h5 {
            font-weight: 800;
        }

        .feature-card p {
            color: #79727f;
            font-size: 14px;
            line-height: 1.7;
            margin: 0;
        }

        /* =========================
           STATS
        ========================= */

        .stats-wrapper {
            margin-top: -1px;
            background: linear-gradient(120deg, #5c0b94, #8017ae 50%, #a225a9);
            color: #fff;
            padding: 65px 0;
            position: relative;
            overflow: hidden;
        }

        .stats-wrapper::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            border: 70px solid rgba(255,255,255,.04);
            right: -100px;
            top: -140px;
        }

        .stat-item {
            position: relative;
        }

        .stat-item h3 {
            font-size: 38px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-item p {
            margin: 0;
            color: rgba(255,255,255,.75);
            font-size: 14px;
        }

        /* =========================
           HOW IT WORKS
        ========================= */

        .step-card {
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .step-number {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            margin: auto auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 26px;
            position: relative;
        }

        .step-number span {
            position: absolute;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: var(--secondary);
            color: #fff;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            right: -7px;
            top: -7px;
        }

        .step-card h5 {
            font-weight: 800;
        }

        .step-card p {
            color: #77717e;
            font-size: 14px;
        }

        /* =========================
           TESTIMONIAL
        ========================= */

        .testimonial-section {
            background: #faf8fc;
        }

        .testimonial-card {
            background: #fff;
            border: 1px solid #eee7f2;
            border-radius: 20px;
            padding: 28px;
            height: 100%;
        }

        .stars {
            color: #ffad20;
            margin-bottom: 15px;
        }

        .testimonial-card .review {
            color: #68616f;
            line-height: 1.8;
            font-size: 15px;
        }

        .review-user {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-top: 22px;
        }

        .review-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .review-user h6 {
            margin: 0;
            font-weight: 800;
        }

        .review-user small {
            color: #99929f;
        }

        /* =========================
           FAQ
        ========================= */

        .faq-wrapper {
            max-width: 850px;
            margin: auto;
        }

        .accordion-item {
            border: 1px solid #ebe5ef !important;
            border-radius: 14px !important;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .accordion-button {
            font-weight: 700;
            padding: 20px 22px;
            box-shadow: none !important;
        }

        .accordion-button:not(.collapsed) {
            background: var(--primary-light);
            color: var(--primary);
        }

        .accordion-body {
            color: #726c78;
            line-height: 1.7;
        }

        /* =========================
           CONTACT
        ========================= */

        .contact-section {
            background: #fff;
        }

        .contact-info {
            height: 100%;
            border-radius: 25px;
            background: linear-gradient(145deg, #5d0b95, #8116ad);
            padding: 40px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .contact-info::after {
            content: "";
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            right: -90px;
            bottom: -90px;
        }

        .contact-info h3 {
            font-size: 30px;
            font-weight: 800;
        }

        .contact-info p {
            color: rgba(255,255,255,.75);
        }

        .contact-row {
            display: flex;
            gap: 14px;
            margin-top: 25px;
            align-items: center;
        }

        .contact-row .contact-icon {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,.13);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-form {
            background: #fff;
            border: 1px solid #eee7f1;
            box-shadow: 0 15px 45px rgba(50,24,65,.07);
            border-radius: 25px;
            padding: 40px;
        }

        .form-label {
            font-weight: 700;
            font-size: 13px;
        }

        .form-control {
            min-height: 50px;
            border-radius: 10px;
            border: 1px solid #e4dfe7;
            padding: 12px 15px;
        }

        textarea.form-control {
            min-height: 130px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(118,22,173,.08);
        }

        /* =========================
           CTA
        ========================= */

        .cta-box {
            border-radius: 28px;
            padding: 55px;
            color: #fff;
            background: linear-gradient(115deg, #5d0b95, #8115ae, #e64a61);
            position: relative;
            overflow: hidden;
        }

        .cta-box::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 60px solid rgba(255,255,255,.05);
            right: -70px;
            top: -100px;
        }

        .cta-box h2 {
            font-size: 38px;
            font-weight: 800;
        }

        .cta-box p {
            color: rgba(255,255,255,.8);
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            background: #171322;
            color: #fff;
            padding: 75px 0 25px;
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
            background: rgba(255,255,255,.08);
            color: #fff;
            border-radius: 10px;
            margin-right: 7px;
            transition: .3s;
        }

        .social-link:hover {
            color: #fff;
            background: var(--primary);
            transform: translateY(-3px);
        }

        .copyright {
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: 45px;
            padding-top: 22px;
            color: #8f8997;
            font-size: 13px;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 991px) {
            .nav-logo {
                width: 220px;
                height: 60px;
            }

            .navbar-nav {
                align-items: stretch;
                padding: 15px 0;
            }

            .hero {
                padding: 80px 0;
            }

            .hero h1 {
                font-size: 45px;
            }

            .hero-visual {
                margin-top: 50px;
            }

            .section-heading {
                font-size: 35px;
            }
        }

        @media (max-width: 576px) {
            .hero {
                padding: 65px 0;
            }

            .hero h1 {
                font-size: 37px;
                letter-spacing: -1px;
            }

            .hero-description {
                font-size: 16px;
            }

            .hero-buttons .btn {
                display: block;
                width: 100%;
                margin: 0 0 12px !important;
            }

            .hero-visual {
                height: 410px;
            }

            .hero-circle {
                width: 280px;
                height: 280px;
            }

            .hero-circle i {
                font-size: 110px;
            }

            .floating-card {
                min-width: 160px;
                padding: 11px;
            }

            .card-doctor {
                left: 0;
            }

            .card-ambulance {
                right: 0;
            }

            .section-space {
                padding: 65px 0;
            }

            .section-heading {
                font-size: 30px;
            }

            .cta-box {
                padding: 35px 25px;
            }

            .cta-box h2 {
                font-size: 30px;
            }

            .contact-info,
            .contact-form {
                padding: 28px;
            }
        }
    </style>
</head>

<body>

{{-- ================= NAVBAR ================= --}}

<nav class="navbar navbar-expand-lg main-navbar sticky-top">
    <div class="container">

        <a class="navbar-brand" href="#home">
            <img src="{{ asset('logo/logo.png') }}"
                 alt="Healing Guides"
                 class="nav-logo">
        </a>

        <button class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="#home" class="nav-link">Home</a>
                </li>

                <li class="nav-item">
                    <a href="#apps" class="nav-link">Applications</a>
                </li>

                <li class="nav-item">
                    <a href="#features" class="nav-link">Features</a>
                </li>

                <li class="nav-item">
                    <a href="#about" class="nav-link">How It Works</a>
                </li>

                <li class="nav-item">
                    <a href="#contact" class="nav-link">Contact</a>
                </li>

                <li class="nav-item ms-lg-2">
                    <a href="#contact" class="nav-link nav-contact-btn">
                        Get Started
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>


{{-- ================= HERO ================= --}}

<section class="hero" id="home">
    <div class="container position-relative">
        <div class="row align-items-center">

            <div class="col-lg-6">

                <div class="hero-badge">
                    <span></span>
                    Complete Digital Healthcare Platform
                </div>

                <h1>
                    Healthcare Made
                    <span>Simple, Smart & Connected.</span>
                </h1>

                <p class="hero-description">
                    One powerful healthcare ecosystem connecting patients,
                    doctors, hospitals, pharmacies and emergency services
                    to deliver better care for every family.
                </p>

                <div class="hero-buttons">
                    <a href="#apps" class="btn btn-gradient me-2">
                        Explore Services
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                    <a href="#contact" class="btn btn-outline-custom">
                        <i class="fa-regular fa-calendar-check me-2"></i>
                        Book a Demo
                    </a>
                </div>

                <div class="hero-trust">

                    <div class="trust-item">
                        <i class="fa-solid fa-circle-check"></i>
                        Verified Doctors
                    </div>

                    <div class="trust-item">
                        <i class="fa-solid fa-shield-halved"></i>
                        Secure Platform
                    </div>

                    <div class="trust-item">
                        <i class="fa-solid fa-headset"></i>
                        24/7 Support
                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="hero-visual">

                    <div class="hero-circle">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>

                    <div class="floating-card card-doctor">
                        <div class="float-icon purple">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div>
                            <h6>Expert Doctors</h6>
                            <small>500+ Specialists</small>
                        </div>
                    </div>

                    <div class="floating-card card-ambulance">
                        <div class="float-icon orange">
                            <i class="fa-solid fa-truck-medical"></i>
                        </div>
                        <div>
                            <h6>Emergency Care</h6>
                            <small>24/7 Ambulance</small>
                        </div>
                    </div>

                    <div class="floating-card card-pharmacy">
                        <div class="float-icon green">
                            <i class="fa-solid fa-pills"></i>
                        </div>
                        <div>
                            <h6>Online Pharmacy</h6>
                            <small>Doorstep Delivery</small>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>


{{-- ================= APPLICATIONS ================= --}}

<section class="section-space apps-section" id="apps">
    <div class="container">

        <div class="text-center mb-5">
            <span class="section-badge">Our Applications</span>

            <h2 class="section-heading">
                One Platform. <span>Complete Healthcare.</span>
            </h2>

            <p class="section-description">
                Purpose-built applications connecting every participant
                in the healthcare ecosystem.
            </p>
        </div>

        <div class="row g-4">

            {{-- Customer --}}

            <div class="col-lg-4 col-md-6">
                <div class="app-card">

                    <div class="app-icon purple-bg">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <h4>Customer App</h4>

                    <p>
                        Access healthcare services anytime from one
                        simple and convenient application.
                    </p>

                    <ul class="app-features">
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Doctor Appointments
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Video Consultation
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Lab Test Booking
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Medicine Orders
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Ambulance Booking
                        </li>
                    </ul>

                    <a href="#" class="card-link">
                        Explore Customer App
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>
            </div>


            {{-- Doctor --}}

            <div class="col-lg-4 col-md-6">
                <div class="app-card">

                    <div class="app-icon orange-bg">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>

                    <h4>Doctor App</h4>

                    <p>
                        A smart digital workspace for doctors to manage
                        patients and consultations.
                    </p>

                    <ul class="app-features">
                        <li><i class="fa-solid fa-circle-check"></i> Appointments</li>
                        <li><i class="fa-solid fa-circle-check"></i> Digital Prescriptions</li>
                        <li><i class="fa-solid fa-circle-check"></i> Patient Records</li>
                        <li><i class="fa-solid fa-circle-check"></i> Video Consultation</li>
                        <li><i class="fa-solid fa-circle-check"></i> Earnings Dashboard</li>
                    </ul>

                    <a href="#" class="card-link">
                        Explore Doctor App
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>
            </div>


            {{-- Hospital --}}

            <div class="col-lg-4 col-md-6">
                <div class="app-card">

                    <div class="app-icon blue-bg">
                        <i class="fa-solid fa-hospital"></i>
                    </div>

                    <h4>Hospital App</h4>

                    <p>
                        Simplify hospital operations with an integrated
                        digital management solution.
                    </p>

                    <ul class="app-features">
                        <li><i class="fa-solid fa-circle-check"></i> OP & IP Management</li>
                        <li><i class="fa-solid fa-circle-check"></i> Billing</li>
                        <li><i class="fa-solid fa-circle-check"></i> Staff Management</li>
                        <li><i class="fa-solid fa-circle-check"></i> Patient Records</li>
                        <li><i class="fa-solid fa-circle-check"></i> Reports & Analytics</li>
                    </ul>

                    <a href="#" class="card-link">
                        Explore Hospital App
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>
            </div>


            {{-- Ambulance --}}

            <div class="col-lg-6 col-md-6">
                <div class="app-card">

                    <div class="app-icon red-bg">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>

                    <h4>Ambulance App</h4>

                    <p>
                        Faster emergency response with live location
                        tracking and smart dispatch management.
                    </p>

                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="app-features">
                                <li><i class="fa-solid fa-circle-check"></i> Live Tracking</li>
                                <li><i class="fa-solid fa-circle-check"></i> GPS Navigation</li>
                            </ul>
                        </div>

                        <div class="col-sm-6">
                            <ul class="app-features">
                                <li><i class="fa-solid fa-circle-check"></i> Emergency Requests</li>
                                <li><i class="fa-solid fa-circle-check"></i> Driver Dashboard</li>
                            </ul>
                        </div>
                    </div>

                    <a href="#" class="card-link">
                        Explore Ambulance App
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>
            </div>


            {{-- Merchant --}}

            <div class="col-lg-6 col-md-6">
                <div class="app-card">

                    <div class="app-icon green-bg">
                        <i class="fa-solid fa-pills"></i>
                    </div>

                    <h4>Merchant App</h4>

                    <p>
                        Manage pharmacy inventory, medicine orders,
                        billing and delivery from one dashboard.
                    </p>

                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="app-features">
                                <li><i class="fa-solid fa-circle-check"></i> Inventory</li>
                                <li><i class="fa-solid fa-circle-check"></i> Online Orders</li>
                            </ul>
                        </div>

                        <div class="col-sm-6">
                            <ul class="app-features">
                                <li><i class="fa-solid fa-circle-check"></i> Billing</li>
                                <li><i class="fa-solid fa-circle-check"></i> Reports</li>
                            </ul>
                        </div>
                    </div>

                    <a href="#" class="card-link">
                        Explore Merchant App
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>


{{-- ================= WHY CHOOSE ================= --}}

<section class="section-space features-section" id="features">
    <div class="container">

        <div class="text-center mb-5">

            <span class="section-badge">Why Healing Guides</span>

            <h2 class="section-heading">
                Healthcare You Can <span>Trust.</span>
            </h2>

            <p class="section-description">
                Designed to make quality healthcare more accessible,
                secure and convenient.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-heart"></i>
                    </div>

                    <h5>100% Secure</h5>

                    <p>
                        Your medical records and personal information
                        are protected with secure technology.
                    </p>

                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>

                    <h5>Expert Doctors</h5>

                    <p>
                        Connect with experienced and verified
                        healthcare professionals.
                    </p>

                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>

                    <h5>Emergency Support</h5>

                    <p>
                        Book emergency ambulance services with
                        real-time tracking.
                    </p>

                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-pills"></i>
                    </div>

                    <h5>Online Pharmacy</h5>

                    <p>
                        Order medicines online and receive them
                        conveniently at your doorstep.
                    </p>

                </div>
            </div>

        </div>

    </div>
</section>


{{-- ================= COUNTERS ================= --}}

<section class="stats-wrapper">
    <div class="container position-relative">

        <div class="row text-center g-4">

            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-item">
                    <h3>500+</h3>
                    <p>Doctors</p>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-item">
                    <h3>120+</h3>
                    <p>Hospitals</p>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-item">
                    <h3>15K+</h3>
                    <p>Customers</p>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-item">
                    <h3>300+</h3>
                    <p>Ambulances</p>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-item">
                    <h3>1K+</h3>
                    <p>Medical Stores</p>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-item">
                    <h3>24/7</h3>
                    <p>Support</p>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ================= HOW IT WORKS ================= --}}

<section class="section-space" id="about">
    <div class="container">

        <div class="text-center mb-5">

            <span class="section-badge">Simple Process</span>

            <h2 class="section-heading">
                Healthcare in <span>4 Easy Steps.</span>
            </h2>

            <p class="section-description">
                Find and access the healthcare service you need
                without complicated processes.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">
                <div class="step-card">

                    <div class="step-number">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>1</span>
                    </div>

                    <h5>Create Account</h5>

                    <p>
                        Register using your mobile number
                        and create your profile.
                    </p>

                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">

                    <div class="step-number">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>2</span>
                    </div>

                    <h5>Find Service</h5>

                    <p>
                        Find doctors, hospitals, medicines
                        or ambulance services.
                    </p>

                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">

                    <div class="step-number">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>3</span>
                    </div>

                    <h5>Book Instantly</h5>

                    <p>
                        Select your preferred service and
                        complete your booking.
                    </p>

                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">

                    <div class="step-number">
                        <i class="fa-solid fa-heart-circle-check"></i>
                        <span>4</span>
                    </div>

                    <h5>Get Care</h5>

                    <p>
                        Receive reliable healthcare assistance
                        when you need it.
                    </p>

                </div>
            </div>

        </div>

    </div>
</section>


{{-- ================= TESTIMONIALS ================= --}}

<section class="section-space testimonial-section">
    <div class="container">

        <div class="text-center mb-5">

            <span class="section-badge">Testimonials</span>

            <h2 class="section-heading">
                Trusted by Our <span>Community.</span>
            </h2>

        </div>

        <div class="row g-4">

            <div class="col-lg-4">
                <div class="testimonial-card">

                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <p class="review">
                        “The platform makes finding doctors and booking
                        appointments extremely simple and convenient.”
                    </p>

                    <div class="review-user">
                        <div class="review-avatar">RS</div>
                        <div>
                            <h6>Rahul Sharma</h6>
                            <small>Customer</small>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="testimonial-card">

                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <p class="review">
                        “Managing my appointments, patient records and
                        prescriptions is now much more efficient.”
                    </p>

                    <div class="review-user">
                        <div class="review-avatar">PP</div>
                        <div>
                            <h6>Dr. Priya Patel</h6>
                            <small>Doctor</small>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="testimonial-card">

                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <p class="review">
                        “A very useful solution for coordinating hospital
                        operations and patient management.”
                    </p>

                    <div class="review-user">
                        <div class="review-avatar">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <div>
                            <h6>City Hospital</h6>
                            <small>Hospital Partner</small>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>


{{-- ================= FAQ ================= --}}

<section class="section-space">
    <div class="container">

        <div class="text-center mb-5">

            <span class="section-badge">FAQ</span>

            <h2 class="section-heading">
                Frequently Asked <span>Questions.</span>
            </h2>

        </div>

        <div class="faq-wrapper">

            <div class="accordion" id="faqAccordion">

                <div class="accordion-item">

                    <h2 class="accordion-header">

                        <button class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq1">

                            How do I register on Healing Guides?

                        </button>

                    </h2>

                    <div id="faq1"
                         class="accordion-collapse collapse show"
                         data-bs-parent="#faqAccordion">

                        <div class="accordion-body">
                            Download the application and register
                            using your mobile number.
                        </div>

                    </div>

                </div>


                <div class="accordion-item">

                    <h2 class="accordion-header">

                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq2">

                            Is my medical data secure?

                        </button>

                    </h2>

                    <div id="faq2"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">

                        <div class="accordion-body">
                            Yes. Your healthcare information is stored
                            securely and protected from unauthorized access.
                        </div>

                    </div>

                </div>


                <div class="accordion-item">

                    <h2 class="accordion-header">

                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq3">

                            Can hospitals join Healing Guides?

                        </button>

                    </h2>

                    <div id="faq3"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">

                        <div class="accordion-body">
                            Yes. Hospitals can join the platform and manage
                            doctors, patients, billing and reports.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>


{{-- ================= CTA ================= --}}

<section class="pb-5">
    <div class="container">

        <div class="cta-box">

            <div class="row align-items-center position-relative">

                <div class="col-lg-8">

                    <h2>
                        Ready for Smarter Healthcare?
                    </h2>

                    <p class="mb-lg-0 mt-3">
                        Join Healing Guides and experience connected
                        healthcare for your entire family.
                    </p>

                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <a href="#contact"
                       class="btn btn-light px-4 py-3 rounded-3 fw-bold">

                        Get Started Today

                        <i class="fa-solid fa-arrow-right ms-2"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>
</section>


{{-- ================= CONTACT ================= --}}

<section class="section-space contact-section" id="contact">
    <div class="container">

        <div class="text-center mb-5">

            <span class="section-badge">Contact Us</span>

            <h2 class="section-heading">
                We're Here to <span>Help.</span>
            </h2>

            <p class="section-description">
                Have questions about Healing Guides?
                Send us a message and our team will contact you.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-5">

                <div class="contact-info">

                    <h3>
                        Let's Talk
                    </h3>

                    <p class="mt-3">
                        Connect with our team to learn more about
                        Healing Guides Wellness Services.
                    </p>

                    <div class="contact-row">

                        <div class="contact-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>

                        <div>
                            <small>Call Us</small>
                            <div class="fw-bold">
                                +91 98765 43210
                            </div>
                        </div>

                    </div>

                    <div class="contact-row">

                        <div class="contact-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                        <div>
                            <small>Email Us</small>
                            <div class="fw-bold">
                                info@healingguides.com
                            </div>
                        </div>

                    </div>

                    <div class="contact-row">

                        <div class="contact-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <div>
                            <small>Location</small>
                            <div class="fw-bold">
                                India
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-7">

                <div class="contact-form">

                    <form>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Your Name
                                </label>

                                <input type="text"
                                       class="form-control"
                                       placeholder="Enter your name">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input type="text"
                                       class="form-control"
                                       placeholder="Enter phone number">

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input type="email"
                                   class="form-control"
                                   placeholder="Enter email address">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Message
                            </label>

                            <textarea class="form-control"
                                      placeholder="How can we help you?"></textarea>

                        </div>

                        <button type="submit"
                                class="btn btn-gradient">

                            Send Message

                            <i class="fa-solid fa-paper-plane ms-2"></i>

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</section>


{{-- ================= FOOTER ================= --}}

<footer>
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-5">

                <img src="{{ asset('logo/logo.png') }}"
                     class="footer-logo"
                     alt="Healing Guides">

                <p class="footer-about">
                    Connecting customers, doctors, hospitals,
                    ambulances and medical merchants through one
                    powerful digital healthcare ecosystem.
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


            <div class="col-lg-2 col-md-4">

                <h5 class="footer-title">
                    Quick Links
                </h5>

                <ul class="footer-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#apps">Applications</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">How It Works</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>

            </div>


            <div class="col-lg-2 col-md-4">

                <h5 class="footer-title">
                    Applications
                </h5>

                <ul class="footer-links">
                    <li><a href="#">Customer App</a></li>
                    <li><a href="#">Doctor App</a></li>
                    <li><a href="#">Hospital App</a></li>
                    <li><a href="#">Ambulance App</a></li>
                    <li><a href="#">Merchant App</a></li>
                </ul>

            </div>


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
                        <a href="mailto:info@healingguides.com">
                            <i class="fa-solid fa-envelope me-2"></i>
                            info@healingguides.com
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa-solid fa-location-dot me-2"></i>
                            India
                        </a>
                    </li>

                </ul>

            </div>

        </div>


        <div class="copyright">

            <div class="row align-items-center">

                <div class="col-md-6 text-center text-md-start">
                    © {{ date('Y') }} Healing Guides Wellness Services.
                    All Rights Reserved.
                </div>

                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    Privacy Policy &nbsp; • &nbsp; Terms & Conditions
                </div>

            </div>

        </div>

    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>