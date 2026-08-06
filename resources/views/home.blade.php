@extends('layouts.app')

@section('title', 'Healing Guides | Complete Digital Healthcare Platform')

@section(
    'meta_description',
    'Healing Guides connects patients, doctors, hospitals, diagnostics, pharmacies and ambulance services through one digital healthcare ecosystem.'
)


@push('styles')

<style>

/* =========================================================
   HOME HERO
========================================================= */

.home-hero {
    position: relative;
    overflow: hidden;
    padding: 100px 0 110px;

    background:
        radial-gradient(
            circle at 90% 20%,
            rgba(255,86,56,.12),
            transparent 30%
        ),
        radial-gradient(
            circle at 5% 90%,
            rgba(118,22,173,.12),
            transparent 32%
        ),
        linear-gradient(
            135deg,
            #ffffff 0%,
            #fcf8ff 52%,
            #fff9f6 100%
        );
}

.home-hero::before {
    content: "";
    position: absolute;

    width: 420px;
    height: 420px;

    border-radius: 50%;

    background: rgba(118,22,173,.04);

    left: -200px;
    top: -180px;
}

.hero-content {
    position: relative;
    z-index: 3;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 9px;

    padding: 9px 16px;

    background: #fff;

    border: 1px solid #eee3f5;
    border-radius: 50px;

    color: var(--primary);

    font-size: 12px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .6px;

    box-shadow:
        0 8px 25px
        rgba(70,25,90,.06);

    margin-bottom: 23px;
}

.hero-badge span {
    width: 8px;
    height: 8px;

    border-radius: 50%;

    background: var(--secondary);
}

.home-hero h1 {
    font-size: 60px;
    line-height: 1.08;

    font-weight: 850;

    letter-spacing: -2.2px;

    margin-bottom: 23px;
}

.home-hero h1 span {
    display: block;

    background:
        linear-gradient(
            90deg,
            var(--primary),
            #a516bf,
            var(--secondary)
        );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-description {
    max-width: 630px;

    color: #686171;

    font-size: 17px;
    line-height: 1.85;

    margin-bottom: 30px;
}

.hero-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.btn-outline-custom {
    padding: 14px 25px;

    border: 1px solid #ded5e3;
    border-radius: 12px;

    color: #393341;

    font-weight: 700;

    background: #fff;

    transition: .3s;
}

.btn-outline-custom:hover {
    color: var(--primary);

    border-color:
        rgba(118,22,173,.30);

    background: var(--primary-light);

    transform: translateY(-2px);
}


/* TRUST */

.hero-trust {
    display: flex;
    flex-wrap: wrap;

    gap: 23px;

    margin-top: 32px;
}

.trust-item {
    display: flex;
    align-items: center;

    gap: 7px;

    color: #615a68;

    font-size: 13px;
    font-weight: 650;
}

.trust-item i {
    color: var(--success);
}


/* =========================================================
   HERO VISUAL
========================================================= */

.hero-visual {
    position: relative;

    min-height: 520px;

    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-circle {
    width: 380px;
    height: 380px;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    position: relative;

    background:
        linear-gradient(
            145deg,
            #f3e4fb,
            #fff1eb
        );

    box-shadow:
        0 30px 70px
        rgba(80,28,104,.12);
}

.hero-circle::before {
    content: "";

    position: absolute;

    inset: 25px;

    border-radius: 50%;

    border:
        1px dashed
        rgba(118,22,173,.20);
}

.hero-circle i {
    font-size: 145px;

    background:
        linear-gradient(
            135deg,
            var(--primary),
            #a51ac4
        );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.floating-card {
    position: absolute;

    min-width: 190px;

    padding: 14px;

    display: flex;
    align-items: center;

    gap: 12px;

    background:
        rgba(255,255,255,.96);

    border: 1px solid #f0e9f3;

    border-radius: 15px;

    box-shadow:
        0 15px 35px
        rgba(60,25,75,.10);
}

.float-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: #fff;

    font-size: 17px;
}

.floating-card strong {
    display: block;

    font-size: 13px;
    margin-bottom: 2px;
}

.floating-card small {
    color: #8a8390;
    font-size: 11px;
}

.card-doctor {
    left: -10px;
    top: 80px;
}

.card-doctor .float-icon {
    background:
        linear-gradient(
            135deg,
            #7616ad,
            #a62ad1
        );
}

.card-hospital {
    right: -5px;
    top: 155px;
}

.card-hospital .float-icon {
    background:
        linear-gradient(
            135deg,
            #2e78df,
            #4ba2f5
        );
}

.card-ambulance {
    right: 20px;
    bottom: 85px;
}

.card-ambulance .float-icon {
    background:
        linear-gradient(
            135deg,
            #ff5638,
            #ff8a20
        );
}

.card-medicine {
    left: 15px;
    bottom: 50px;
}

.card-medicine .float-icon {
    background:
        linear-gradient(
            135deg,
            #17a66a,
            #39c788
        );
}


/* =========================================================
   COMMON SECTION
========================================================= */

.section-space {
    padding: 90px 0;
}

.section-light {
    background: #faf9fc;
}

.section-label {
    display: inline-flex;
    align-items: center;

    gap: 8px;

    color: var(--primary);

    font-size: 12px;
    font-weight: 800;

    letter-spacing: .7px;
    text-transform: uppercase;

    margin-bottom: 13px;
}

.section-label::before {
    content: "";

    width: 25px;
    height: 3px;

    border-radius: 10px;

    background:
        linear-gradient(
            90deg,
            var(--primary),
            var(--secondary)
        );
}

.section-heading {
    font-size: 43px;
    line-height: 1.15;

    font-weight: 850;

    letter-spacing: -1.2px;

    margin-bottom: 16px;
}

.section-heading span {
    color: var(--primary);
}

.section-description {
    max-width: 700px;

    color: #716b77;

    font-size: 16px;
    line-height: 1.8;
}


/* =========================================================
   APPLICATIONS
========================================================= */

.app-card {
    height: 100%;

    position: relative;
    overflow: hidden;

    padding: 27px;

    background: #fff;

    border: 1px solid #eee8f3;
    border-radius: 20px;

    transition: .35s;

    box-shadow:
        0 10px 35px
        rgba(48,22,63,.04);
}

.app-card:hover {
    transform: translateY(-7px);

    box-shadow:
        0 20px 45px
        rgba(48,22,63,.09);

    border-color:
        rgba(118,22,173,.16);
}

.app-icon {
    width: 58px;
    height: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;

    color: #fff;

    font-size: 22px;

    margin-bottom: 20px;
}

.app-purple {
    background:
        linear-gradient(135deg,#7616ad,#a72bd0);
}

.app-blue {
    background:
        linear-gradient(135deg,#286fd2,#53a9f7);
}

.app-green {
    background:
        linear-gradient(135deg,#15915e,#39c785);
}

.app-orange {
    background:
        linear-gradient(135deg,#ff8a20,#ff5638);
}

.app-red {
    background:
        linear-gradient(135deg,#e94057,#f26a7c);
}

.app-teal {
    background:
        linear-gradient(135deg,#0c9992,#35c7bd);
}

.app-card h4 {
    font-size: 18px;
    font-weight: 800;

    margin-bottom: 10px;
}

.app-card p {
    color: #77707d;

    font-size: 14px;
    line-height: 1.7;

    margin-bottom: 18px;
}

.app-features {
    list-style: none;

    padding: 0;
    margin: 0;
}

.app-features li {
    display: flex;

    gap: 8px;

    color: #6d6672;

    font-size: 13px;

    margin-bottom: 8px;
}

.app-features i {
    color: var(--success);
    margin-top: 3px;
}


/* =========================================================
   FEATURES
========================================================= */

.feature-card {
    height: 100%;

    padding: 25px;

    border-radius: 18px;

    background: #fff;

    border: 1px solid #eee8f3;

    transition: .3s;
}

.feature-card:hover {
    transform: translateY(-5px);

    box-shadow:
        0 15px 35px
        rgba(50,25,65,.07);
}

.feature-icon {
    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background: var(--primary-light);
    color: var(--primary);

    font-size: 18px;

    margin-bottom: 17px;
}

.feature-card h5 {
    font-size: 16px;
    font-weight: 800;
    margin-bottom: 8px;
}

.feature-card p {
    color: #79717f;

    font-size: 13px;
    line-height: 1.7;

    margin: 0;
}


/* =========================================================
   HOW IT WORKS
========================================================= */

.process-wrapper {
    position: relative;
}

.process-card {
    position: relative;

    height: 100%;

    padding: 27px;

    background: #fff;

    border: 1px solid #eee8f3;
    border-radius: 18px;

    text-align: center;
}

.process-number {
    width: 52px;
    height: 52px;

    margin: 0 auto 18px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background:
        linear-gradient(
            135deg,
            var(--primary),
            #a620c7
        );

    color: #fff;

    font-weight: 850;

    box-shadow:
        0 8px 20px
        rgba(118,22,173,.20);
}

.process-card h5 {
    font-size: 17px;
    font-weight: 800;
}

.process-card p {
    color: #79717f;

    font-size: 13px;
    line-height: 1.7;

    margin-bottom: 0;
}


/* =========================================================
   STATS
========================================================= */

.stats-box {
    padding: 45px;

    border-radius: 25px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #5c0c91,
            #8215ae
        );

    position: relative;
    overflow: hidden;
}

.stats-box::after {
    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    border-radius: 50%;

    background:
        rgba(255,255,255,.05);

    right: -120px;
    top: -160px;
}

.stat-item {
    position: relative;
    z-index: 2;

    text-align: center;
}

.stat-item h3 {
    font-size: 36px;
    font-weight: 850;

    margin-bottom: 5px;
}

.stat-item p {
    margin: 0;

    color:
        rgba(255,255,255,.72);

    font-size: 13px;
}


/* =========================================================
   CTA
========================================================= */

.cta-box {
    position: relative;
    overflow: hidden;

    padding: 60px;

    border-radius: 28px;

    background:
        linear-gradient(
            135deg,
            #fff7f3,
            #f8edff
        );

    border: 1px solid #eee2f4;
}

.cta-box::after {
    content: "";

    position: absolute;

    width: 260px;
    height: 260px;

    border-radius: 50%;

    background:
        rgba(118,22,173,.05);

    right: -90px;
    bottom: -150px;
}

.cta-box h2 {
    font-size: 40px;
    font-weight: 850;

    letter-spacing: -1px;
}

.cta-box p {
    color: #716a78;

    line-height: 1.8;

    max-width: 650px;
}


/* =========================================================
   CONTACT
========================================================= */

.contact-info,
.contact-form {
    height: 100%;

    padding: 35px;

    background: #fff;

    border: 1px solid #eee8f3;
    border-radius: 22px;

    box-shadow:
        0 12px 40px
        rgba(50,24,65,.05);
}

.contact-info {
    color: #fff;

    background:
        linear-gradient(
            145deg,
            #5d0b95,
            #8116ad
        );

    border: none;
}

.contact-info h3 {
    font-size: 28px;
    font-weight: 850;
}

.contact-info > p {
    color:
        rgba(255,255,255,.72);

    line-height: 1.8;
}

.contact-item {
    display: flex;
    align-items: center;

    gap: 13px;

    margin-top: 24px;
}

.contact-item-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background:
        rgba(255,255,255,.12);
}

.contact-item small {
    display: block;

    color:
        rgba(255,255,255,.60);

    margin-bottom: 2px;
}

.contact-item strong {
    font-size: 14px;
}

.contact-form h3 {
    font-size: 25px;
    font-weight: 850;

    margin-bottom: 7px;
}

.contact-form > p {
    color: #79717f;

    font-size: 14px;

    margin-bottom: 25px;
}

.contact-form .form-label {
    color: #4e4754;

    font-size: 13px;
    font-weight: 700;
}

.contact-form .form-control {
    min-height: 50px;

    border: 1px solid #e8e1eb;
    border-radius: 11px;

    padding:
        11px 14px;

    box-shadow: none;
}

.contact-form .form-control:focus {
    border-color:
        rgba(118,22,173,.50);

    box-shadow:
        0 0 0 3px
        rgba(118,22,173,.07);
}

.contact-form textarea.form-control {
    min-height: 130px;
    resize: none;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 991px) {

    .home-hero {
        padding: 75px 0;
    }

    .home-hero h1 {
        font-size: 46px;
    }

    .hero-visual {
        margin-top: 50px;
    }

    .section-heading {
        font-size: 36px;
    }

}


@media(max-width: 576px) {

    .home-hero {
        padding: 55px 0 65px;
    }

    .home-hero h1 {
        font-size: 38px;
        letter-spacing: -1px;
    }

    .hero-description {
        font-size: 15px;
    }

    .hero-buttons {
        display: block;
    }

    .hero-buttons .btn {
        display: block;
        width: 100%;

        margin-bottom: 11px;
    }

    .hero-trust {
        gap: 13px;
    }

    .hero-visual {
        min-height: 410px;
    }

    .hero-circle {
        width: 270px;
        height: 270px;
    }

    .hero-circle i {
        font-size: 100px;
    }

    .floating-card {
        min-width: 155px;

        padding: 10px;

        gap: 8px;
    }

    .float-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
    }

    .card-doctor {
        left: 0;
        top: 40px;
    }

    .card-hospital {
        right: 0;
        top: 120px;
    }

    .card-ambulance {
        right: 0;
        bottom: 55px;
    }

    .card-medicine {
        left: 0;
        bottom: 15px;
    }

    .section-space {
        padding: 65px 0;
    }

    .section-heading {
        font-size: 30px;
    }

    .stats-box {
        padding: 30px 20px;
    }

    .stat-item {
        padding: 15px 0;
    }

    .cta-box {
        padding: 35px 23px;
    }

    .cta-box h2 {
        font-size: 30px;
    }

    .contact-info,
    .contact-form {
        padding: 27px 20px;
    }

}

</style>

@endpush



@section('content')


{{-- ============================================================
     HERO
============================================================ --}}

<section class="home-hero" id="home">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <div class="hero-content">

                    <div class="hero-badge">

                        <span></span>

                        Complete Digital Healthcare Platform

                    </div>


                    <h1>

                        Healthcare Made

                        <span>
                            Simple, Smart & Connected.
                        </span>

                    </h1>


                    <p class="hero-description">

                        One powerful healthcare ecosystem connecting
                        patients, doctors, hospitals, diagnostics,
                        pharmacies and emergency services to deliver
                        better care for every family.

                    </p>


                    <div class="hero-buttons">

                        <a href="#apps"
                           class="btn btn-gradient">

                            Explore Services

                            <i class="fa-solid fa-arrow-right ms-2"></i>

                        </a>


                        <a href="#contact"
                           class="btn btn-outline-custom">

                            <i class="fa-regular fa-calendar-check me-2"></i>

                            Get Started

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

                            Connected Care

                        </div>

                    </div>

                </div>

            </div>



            {{-- HERO VISUAL --}}

            <div class="col-lg-6">

                <div class="hero-visual">


                    <div class="hero-circle">

                        <i class="fa-solid fa-heart-pulse"></i>

                    </div>


                    <div class="floating-card card-doctor">

                        <div class="float-icon">

                            <i class="fa-solid fa-user-doctor"></i>

                        </div>

                        <div>

                            <strong>
                                Doctors
                            </strong>

                            <small>
                                Book consultations
                            </small>

                        </div>

                    </div>


                    <div class="floating-card card-hospital">

                        <div class="float-icon">

                            <i class="fa-solid fa-hospital"></i>

                        </div>

                        <div>

                            <strong>
                                Hospitals
                            </strong>

                            <small>
                                Connected healthcare
                            </small>

                        </div>

                    </div>


                    <div class="floating-card card-ambulance">

                        <div class="float-icon">

                            <i class="fa-solid fa-truck-medical"></i>

                        </div>

                        <div>

                            <strong>
                                Ambulance
                            </strong>

                            <small>
                                Emergency support
                            </small>

                        </div>

                    </div>


                    <div class="floating-card card-medicine">

                        <div class="float-icon">

                            <i class="fa-solid fa-pills"></i>

                        </div>

                        <div>

                            <strong>
                                Medicines
                            </strong>

                            <small>
                                Medicine services
                            </small>

                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>

</section>



{{-- ============================================================
     APPLICATIONS
============================================================ --}}

<section class="section-space section-light"
         id="apps">

    <div class="container">


        <div class="text-center mb-5">

            <div class="section-label">
                Our Applications
            </div>

            <h2 class="section-heading">

                One Ecosystem.
                <span>Multiple Applications.</span>

            </h2>

            <p class="section-description mx-auto">

                Dedicated applications designed for every
                participant in the healthcare ecosystem.

            </p>

        </div>


        <div class="row g-4">


            {{-- CUSTOMER --}}

            <div class="col-lg-4 col-md-6">

                <div class="app-card">

                    <div class="app-icon app-purple">

                        <i class="fa-solid fa-user"></i>

                    </div>

                    <h4>
                        Customer App
                    </h4>

                    <p>

                        A single application for patients and families
                        to access healthcare services.

                    </p>

                    <ul class="app-features">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Find & book doctors
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Diagnostic lab tests
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Order medicines
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Book ambulances
                        </li>

                    </ul>

                </div>

            </div>



            {{-- HOSPITAL --}}

            <div class="col-lg-4 col-md-6">

                <div class="app-card">

                    <div class="app-icon app-blue">

                        <i class="fa-solid fa-hospital"></i>

                    </div>

                    <h4>
                        Hospital App
                    </h4>

                    <p>

                        Manage hospital services from doctors and
                        appointments to medicines and ambulances.

                    </p>

                    <ul class="app-features">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Doctor management
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Appointment management
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Medicine inventory & orders
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Ambulance requests
                        </li>

                    </ul>

                </div>

            </div>



            {{-- DOCTOR --}}

            <div class="col-lg-4 col-md-6">

                <div class="app-card">

                    <div class="app-icon app-green">

                        <i class="fa-solid fa-user-doctor"></i>

                    </div>

                    <h4>
                        Doctor App
                    </h4>

                    <p>

                        Give doctors an easier way to manage
                        appointments and patient consultations.

                    </p>

                    <ul class="app-features">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Appointment schedule
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Patient details
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Consultation management
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Availability management
                        </li>

                    </ul>

                </div>

            </div>



            {{-- DIAGNOSTIC --}}

            <div class="col-lg-4 col-md-6">

                <div class="app-card">

                    <div class="app-icon app-orange">

                        <i class="fa-solid fa-flask-vial"></i>

                    </div>

                    <h4>
                        Diagnostic Services
                    </h4>

                    <p>

                        Connect diagnostic centres and laboratory
                        services with patients and hospitals.

                    </p>

                    <ul class="app-features">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Lab test catalogue
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Diagnostic pricing
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Home collection
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Report management
                        </li>

                    </ul>

                </div>

            </div>



            {{-- AMBULANCE --}}

            <div class="col-lg-4 col-md-6">

                <div class="app-card">

                    <div class="app-icon app-red">

                        <i class="fa-solid fa-truck-medical"></i>

                    </div>

                    <h4>
                        Ambulance Services
                    </h4>

                    <p>

                        Manage ambulance availability, bookings,
                        assignment and trip status.

                    </p>

                    <ul class="app-features">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Ambulance availability
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Emergency requests
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Driver information
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Trip management
                        </li>

                    </ul>

                </div>

            </div>



            {{-- MARKETING --}}

            <div class="col-lg-4 col-md-6">

                <div class="app-card">

                    <div class="app-icon app-teal">

                        <i class="fa-solid fa-people-group"></i>

                    </div>

                    <h4>
                        Marketing App
                    </h4>

                    <p>

                        Empower field and marketing teams with a
                        dedicated healthcare business application.

                    </p>

                    <ul class="app-features">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Lead management
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Field visits
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Follow-ups
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Provider onboarding
                        </li>

                    </ul>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- ============================================================
     FEATURES
============================================================ --}}

<section class="section-space"
         id="features">

    <div class="container">


        <div class="row align-items-end mb-5">

            <div class="col-lg-7">

                <div class="section-label">
                    Platform Features
                </div>

                <h2 class="section-heading">

                    Everything Healthcare Needs
                    <span>In One Platform.</span>

                </h2>

            </div>


            <div class="col-lg-5">

                <p class="section-description">

                    Built to simplify healthcare operations while
                    giving patients convenient access to essential
                    services.

                </p>

            </div>

        </div>



        <div class="row g-4">


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>

                    <h5>
                        Appointments
                    </h5>

                    <p>
                        Search doctors, view availability and manage
                        healthcare appointments.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-video"></i>
                    </div>

                    <h5>
                        Consultations
                    </h5>

                    <p>
                        Support clinic, video and other available
                        consultation options.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>

                    <h5>
                        Diagnostics
                    </h5>

                    <p>
                        Browse lab tests, diagnostics and home
                        collection services.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-pills"></i>
                    </div>

                    <h5>
                        Medicines
                    </h5>

                    <p>
                        Manage medicine inventory, availability
                        and customer medicine orders.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>

                    <h5>
                        Ambulances
                    </h5>

                    <p>
                        Handle emergency ambulance requests,
                        assignments and trips.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <h5>
                        Location Services
                    </h5>

                    <p>
                        Location-aware healthcare services for
                        ambulances and home collections.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>

                    <h5>
                        Digital Payments
                    </h5>

                    <p>
                        Support convenient payment flows across
                        healthcare services.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>

                    <h5>
                        Secure Access
                    </h5>

                    <p>
                        Secure account authentication and
                        role-based healthcare applications.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- ============================================================
     HOW IT WORKS
============================================================ --}}

<section class="section-space section-light"
         id="about">

    <div class="container">


        <div class="text-center mb-5">

            <div class="section-label">
                How It Works
            </div>

            <h2 class="section-heading">

                Healthcare Access Made
                <span>Simple.</span>

            </h2>

            <p class="section-description mx-auto">

                Healing Guides connects healthcare participants
                through a simple and coordinated digital journey.

            </p>

        </div>



        <div class="row g-4 process-wrapper">


            <div class="col-lg-3 col-md-6">

                <div class="process-card">

                    <div class="process-number">
                        01
                    </div>

                    <h5>
                        Create Account
                    </h5>

                    <p>

                        Register and securely access the
                        appropriate Healing Guides application.

                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="process-card">

                    <div class="process-number">
                        02
                    </div>

                    <h5>
                        Find a Service
                    </h5>

                    <p>

                        Explore doctors, hospitals, diagnostics,
                        medicines or ambulance services.

                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="process-card">

                    <div class="process-number">
                        03
                    </div>

                    <h5>
                        Request or Book
                    </h5>

                    <p>

                        Book an appointment, order medicines or
                        submit a healthcare service request.

                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="process-card">

                    <div class="process-number">
                        04
                    </div>

                    <h5>
                        Receive Care
                    </h5>

                    <p>

                        The appropriate healthcare provider
                        processes and fulfils the requested service.

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- ============================================================
     ECOSYSTEM
============================================================ --}}

<section class="section-space">

    <div class="container">

        <div class="stats-box">

            <div class="row g-4">


                <div class="col-lg-3 col-6">

                    <div class="stat-item">

                        <h3>
                            6+
                        </h3>

                        <p>
                            Connected Services
                        </p>

                    </div>

                </div>


                <div class="col-lg-3 col-6">

                    <div class="stat-item">

                        <h3>
                            1
                        </h3>

                        <p>
                            Healthcare Ecosystem
                        </p>

                    </div>

                </div>


                <div class="col-lg-3 col-6">

                    <div class="stat-item">

                        <h3>
                            24/7
                        </h3>

                        <p>
                            Digital Access
                        </p>

                    </div>

                </div>


                <div class="col-lg-3 col-6">

                    <div class="stat-item">

                        <h3>
                            360°
                        </h3>

                        <p>
                            Connected Healthcare
                        </p>

                    </div>

                </div>


            </div>

        </div>

    </div>

</section>



{{-- ============================================================
     CTA
============================================================ --}}

<section class="pb-5">

    <div class="container">

        <div class="cta-box">

            <div class="row align-items-center position-relative"
                 style="z-index:2;">

                <div class="col-lg-8">

                    <div class="section-label">
                        Healing Guides
                    </div>

                    <h2>

                        Building a Better Connected
                        Healthcare Experience.

                    </h2>

                    <p class="mb-lg-0">

                        From appointments and diagnostics to
                        medicines and emergency ambulance services,
                        Healing Guides brings healthcare services
                        together on one connected platform.

                    </p>

                </div>


                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <a href="#contact"
                       class="btn btn-gradient">

                        Contact Us

                        <i class="fa-solid fa-arrow-right ms-2"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- ============================================================
     CONTACT
============================================================ --}}

<section class="section-space section-light"
         id="contact">

    <div class="container">


        <div class="text-center mb-5">

            <div class="section-label">
                Contact Us
            </div>

            <h2 class="section-heading">

                Let's Build Better
                <span>Healthcare Together.</span>

            </h2>

            <p class="section-description mx-auto">

                Have questions about Healing Guides or interested
                in joining our healthcare ecosystem? Contact our team.

            </p>

        </div>



        <div class="row g-4">


            {{-- CONTACT INFORMATION --}}

            <div class="col-lg-5">

                <div class="contact-info">

                    <h3>
                        Get In Touch
                    </h3>

                    <p>

                        Contact Healing Guides Wellness Services
                        for application, partnership and platform
                        related enquiries.

                    </p>


                    <div class="contact-item">

                        <div class="contact-item-icon">

                            <i class="fa-solid fa-phone"></i>

                        </div>

                        <div>

                            <small>
                                Phone
                            </small>

                            <strong>
                                +91 98765 43210
                            </strong>

                        </div>

                    </div>


                    <div class="contact-item">

                        <div class="contact-item-icon">

                            <i class="fa-solid fa-envelope"></i>

                        </div>

                        <div>

                            <small>
                                Email
                            </small>

                            <strong>
                                info@healingguides.com
                            </strong>

                        </div>

                    </div>


                    <div class="contact-item">

                        <div class="contact-item-icon">

                            <i class="fa-solid fa-location-dot"></i>

                        </div>

                        <div>

                            <small>
                                Location
                            </small>

                            <strong>
                                H NO - 37 - 103/2
SREE COLONEY NEREDMET X ROADS
SECUNDERABAD. 50006. TELANGANA
                            </strong>

                        </div>

                    </div>


                    <div class="contact-item">

                        <div class="contact-item-icon">

                            <i class="fa-solid fa-clock"></i>

                        </div>

                        <div>

                            <small>
                                Support
                            </small>

                            <strong>
                                Healthcare Platform Support
                            </strong>

                        </div>

                    </div>


                </div>

            </div>



            {{-- CONTACT FORM --}}

            <div class="col-lg-7">

                <div class="contact-form">

                    <h3>
                        Send Us a Message
                    </h3>

                    <p>

                        Fill in your details and our team
                        will get in touch with you.

                    </p>


                    <form action="#"
                          method="POST">

                        @csrf


                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Your Name

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Enter your name"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Phone Number

                                </label>

                                <input
                                    type="text"
                                    name="mobile"
                                    class="form-control"
                                    placeholder="Enter phone number"
                                >

                            </div>


                        </div>


                        <div class="mb-3">

                            <label class="form-label">

                                Email Address

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter email address"
                            >

                        </div>


                        <div class="mb-4">

                            <label class="form-label">

                                Message

                            </label>

                            <textarea
                                name="message"
                                class="form-control"
                                placeholder="How can we help you?"
                            ></textarea>

                        </div>


                        <button
                            type="button"
                            class="btn btn-gradient"
                        >

                            Send Message

                            <i class="fa-solid fa-paper-plane ms-2"></i>

                        </button>


                    </form>

                </div>

            </div>


        </div>

    </div>

</section>


@endsection