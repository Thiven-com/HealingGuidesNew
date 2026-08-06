@extends('layouts.app')


@section('title', 'Privacy Policy | Healing Guides')

@section(
    'meta_description',
    'Privacy Policy for Healing Guides Wellness Services and its healthcare applications.'
)



@push('styles')

<style>

    .privacy-section {
        padding: 75px 0 90px;
        background: #faf9fc;
    }

    .privacy-layout {

        display: grid;

        grid-template-columns:
            260px minmax(0, 1fr);

        gap: 30px;

        align-items: start;
    }


    /* Sidebar */

    .privacy-sidebar {

        position: sticky;

        top: 110px;

        background: #fff;

        border:
            1px solid var(--border);

        border-radius: 20px;

        padding: 22px;

        box-shadow:
            0 12px 35px
            rgba(57,27,75,.06);
    }

    .privacy-sidebar h5 {
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 17px;
    }

    .privacy-sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .privacy-sidebar li {
        margin-bottom: 4px;
    }

    .privacy-sidebar a {

        display: flex;

        align-items: center;

        gap: 10px;

        color: #6a6470;

        padding:
            10px 12px;

        border-radius: 9px;

        font-size: 13px;

        font-weight: 600;

        transition: .3s;
    }

    .privacy-sidebar a:hover {

        background:
            var(--primary-light);

        color:
            var(--primary);
    }


    /* Main Card */

    .privacy-card {

        background: #fff;

        border:
            1px solid var(--border);

        border-radius: 24px;

        padding: 42px;

        box-shadow:
            0 15px 45px
            rgba(50,24,65,.06);
    }


    .policy-intro {

        padding: 23px;

        border-radius: 16px;

        background:
            linear-gradient(
                135deg,
                #fbf6ff,
                #fff9f7
            );

        border:
            1px solid #eee4f3;

        color: #68616f;

        line-height: 1.8;

        margin-bottom: 38px;
    }


    .policy-item {

        padding-bottom: 34px;

        margin-bottom: 34px;

        border-bottom:
            1px solid #eee9f1;

        scroll-margin-top: 120px;
    }


    .policy-item:last-child {
        border-bottom: 0;
        margin-bottom: 0;
    }


    .policy-title {

        display: flex;

        align-items: center;

        gap: 14px;

        margin-bottom: 18px;
    }


    .policy-icon {

        width: 48px;
        height: 48px;

        min-width: 48px;

        border-radius: 14px;

        display: flex;

        align-items: center;

        justify-content: center;

        background:
            var(--primary-light);

        color:
            var(--primary);

        font-size: 18px;
    }


    .policy-title h2 {
        font-size: 21px;
        font-weight: 800;
        margin: 0;
    }


    .policy-item p {

        color: #716b77;

        font-size: 15px;

        line-height: 1.85;

        margin-bottom: 14px;
    }


    .policy-list {
        list-style: none;
        padding: 0;
        margin: 18px 0 0;
    }


    .policy-list li {

        display: flex;

        gap: 11px;

        color: #68616f;

        font-size: 14px;

        line-height: 1.7;

        margin-bottom: 11px;
    }


    .policy-list i {
        color: var(--success);
        margin-top: 5px;
    }


    @media(max-width: 991px) {

        .privacy-layout {
            grid-template-columns: 1fr;
        }

        .privacy-sidebar {
            position: static;
        }

    }


    @media(max-width: 767px) {

        .privacy-section {
            padding: 45px 0 65px;
        }

        .privacy-card {
            padding: 25px 20px;
        }

    }

</style>

@endpush



@section('content')


{{-- ============================================================
     HERO
============================================================ --}}

<section class="page-hero">

    <div class="container">

        <div class="page-hero-content text-center">


            <div class="page-badge">

                <span></span>

                Privacy & Security

            </div>


            <h1>

                Your Privacy.
                <span>Our Priority.</span>

            </h1>


            <p>

                Learn how Healing Guides Wellness Services
                collects, uses and protects your information
                across our connected healthcare ecosystem.

            </p>


            <div class="mt-3 text-muted">

                <i class="fa-regular fa-calendar-check me-2"
                   style="color:var(--primary)"></i>

                Last Updated: August 2026

            </div>


        </div>

    </div>

</section>



{{-- ============================================================
     PRIVACY CONTENT
============================================================ --}}

<section class="privacy-section">

    <div class="container">


        <div class="privacy-layout">


            {{-- Sidebar --}}

            <aside class="privacy-sidebar">

                <h5>

                    <i class="fa-solid fa-list me-2"
                       style="color:var(--primary)"></i>

                    Policy Contents

                </h5>


                <ul>

                    <li>
                        <a href="#introduction">
                            Introduction
                        </a>
                    </li>

                    <li>
                        <a href="#information">
                            Information We Collect
                        </a>
                    </li>

                    <li>
                        <a href="#usage">
                            How We Use Data
                        </a>
                    </li>

                    <li>
                        <a href="#location">
                            Location
                        </a>
                    </li>

                    <li>
                        <a href="#sharing">
                            Data Sharing
                        </a>
                    </li>

                    <li>
                        <a href="#security">
                            Security
                        </a>
                    </li>

                    <li>
                        <a href="#deletion">
                            Account Deletion
                        </a>
                    </li>

                    <li>
                        <a href="#contact">
                            Contact Us
                        </a>
                    </li>

                </ul>

            </aside>



            {{-- Content --}}

            <div class="privacy-card">


                <div class="policy-intro">

                    Welcome to
                    <strong>
                        Healing Guides Wellness Services.
                    </strong>

                    We respect your privacy and are committed
                    to protecting information you provide while
                    using our healthcare platform.

                </div>



                <div class="policy-item"
                     id="introduction">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-shield-heart"></i>

                        </div>

                        <h2>
                            1. Introduction
                        </h2>

                    </div>


                    <p>

                        Healing Guides provides a connected
                        healthcare ecosystem connecting customers,
                        doctors, hospitals, diagnostic services,
                        medicine services and ambulance services.

                    </p>

                </div>



                <div class="policy-item"
                     id="information">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-database"></i>

                        </div>

                        <h2>
                            2. Information We Collect
                        </h2>

                    </div>


                    <p>

                        Depending on the services you use,
                        we may collect information required
                        to provide and operate our services.

                    </p>


                    <ul class="policy-list">

                        <li>

                            <i class="fa-solid fa-circle-check"></i>

                            Name, mobile number and email address.

                        </li>


                        <li>

                            <i class="fa-solid fa-circle-check"></i>

                            Date of birth, gender and profile information.

                        </li>


                        <li>

                            <i class="fa-solid fa-circle-check"></i>

                            Address, city, state and pincode.

                        </li>


                        <li>

                            <i class="fa-solid fa-circle-check"></i>

                            Appointment and healthcare service information.

                        </li>


                        <li>

                            <i class="fa-solid fa-circle-check"></i>

                            Prescription and medicine order information.

                        </li>


                        <li>

                            <i class="fa-solid fa-circle-check"></i>

                            Diagnostic and ambulance booking information.

                        </li>

                    </ul>

                </div>



                <div class="policy-item"
                     id="usage">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-gears"></i>

                        </div>

                        <h2>
                            3. How We Use Information
                        </h2>

                    </div>


                    <p>

                        We use information to provide requested
                        healthcare services, manage accounts,
                        process bookings, provide support and
                        maintain platform security.

                    </p>

                </div>



                <div class="policy-item"
                     id="location">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-location-dot"></i>

                        </div>

                        <h2>
                            4. Location Information
                        </h2>

                    </div>


                    <p>

                        Location information may be used for
                        ambulance services, medicine delivery,
                        diagnostic home collection and other
                        location-based healthcare functionality.

                    </p>

                </div>



                <div class="policy-item"
                     id="sharing">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-share-nodes"></i>

                        </div>

                        <h2>
                            5. Information Sharing
                        </h2>

                    </div>


                    <p>

                        Information may be shared with relevant
                        hospitals, doctors, diagnostic providers,
                        ambulance providers, medicine providers
                        and technology partners when required
                        to provide requested services.

                    </p>

                </div>



                <div class="policy-item"
                     id="security">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-shield-halved"></i>

                        </div>

                        <h2>
                            6. Data Security
                        </h2>

                    </div>


                    <p>

                        We use reasonable administrative,
                        organizational and technical measures
                        designed to protect information against
                        unauthorized access, misuse or disclosure.

                    </p>

                </div>



                <div class="policy-item"
                     id="deletion">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-user-xmark"></i>

                        </div>

                        <h2>
                            7. Account & Data Deletion
                        </h2>

                    </div>


                    <p>

                        Users may request account and personal
                        information deletion by contacting
                        Healing Guides or using an account
                        deletion option when available.

                    </p>

                </div>



                <div class="policy-item"
                     id="contact">


                    <div class="policy-title">

                        <div class="policy-icon">

                            <i class="fa-solid fa-headset"></i>

                        </div>

                        <h2>
                            8. Contact Us
                        </h2>

                    </div>


                    <p>

                        For questions regarding this Privacy Policy,
                        please contact:

                    </p>


                    <p>

                        <strong>
                            Healing Guides Wellness Services
                        </strong>

                        <br>

                        Email:
                        info@healingguides.com

                        <br>

                        Phone:
                        +91 98765 43210

                        <br>

                        H NO - 37 - 103/2
SREE COLONEY NEREDMET X ROADS
SECUNDERABAD. 50006. TELANGANA

                    </p>

                </div>


            </div>

        </div>

    </div>

</section>


@endsection