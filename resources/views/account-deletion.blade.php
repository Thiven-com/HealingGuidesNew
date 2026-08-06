@extends('layouts.app')


@section('title', 'Delete Account | Healing Guides')

@section(
    'meta_description',
    'Request deletion of your Healing Guides account and associated personal information.'
)


@push('styles')

<style>

    .delete-section {
        padding: 75px 0 90px;
        background: #faf9fc;
    }

    .delete-wrapper {
        max-width: 950px;
        margin: 0 auto;
    }

    .delete-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 42px;

        box-shadow:
            0 15px 45px
            rgba(50,24,65,.06);
    }


    /* =========================================================
       INTRO
    ========================================================= */

    .delete-intro {
        display: flex;
        align-items: flex-start;
        gap: 18px;

        padding: 24px;

        background:
            linear-gradient(
                135deg,
                #fbf6ff,
                #fff9f7
            );

        border: 1px solid #eee4f3;
        border-radius: 17px;

        margin-bottom: 35px;
    }

    .delete-intro-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 15px;

        background: var(--primary-light);
        color: var(--primary);

        font-size: 20px;
    }

    .delete-intro h4 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .delete-intro p {
        color: #6d6672;
        line-height: 1.75;
        margin: 0;
        font-size: 14px;
    }


    /* =========================================================
       SECTION
    ========================================================= */

    .delete-item {
        padding-bottom: 34px;
        margin-bottom: 34px;

        border-bottom:
            1px solid #eee9f1;
    }

    .delete-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    .delete-title {
        display: flex;
        align-items: center;
        gap: 14px;

        margin-bottom: 18px;
    }

    .delete-title-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: var(--primary-light);
        color: var(--primary);

        font-size: 18px;
    }

    .delete-title h2 {
        margin: 0;

        font-size: 21px;
        font-weight: 800;

        color: var(--dark);
    }

    .delete-item p {
        color: #716b77;
        font-size: 15px;
        line-height: 1.85;
        margin-bottom: 14px;
    }


    /* =========================================================
       STEPS
    ========================================================= */

    .delete-steps {
        margin-top: 25px;
    }

    .delete-step {
        display: flex;
        gap: 17px;
        position: relative;
        padding-bottom: 25px;
    }

    .delete-step:last-child {
        padding-bottom: 0;
    }

    .delete-step:not(:last-child)::before {
        content: "";

        position: absolute;

        width: 2px;

        left: 21px;
        top: 44px;
        bottom: 0;

        background: #eadcf2;
    }

    .step-number {
        width: 44px;
        height: 44px;
        min-width: 44px;

        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                #a61bd2
            );

        color: #fff;

        font-size: 14px;
        font-weight: 800;

        box-shadow:
            0 7px 18px
            rgba(118,22,173,.20);
    }

    .step-content {
        padding-top: 2px;
    }

    .step-content h5 {
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .step-content p {
        font-size: 14px;
        line-height: 1.7;
        margin: 0;
    }


    /* =========================================================
       DATA CARDS
    ========================================================= */

    .data-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);

        gap: 14px;

        margin-top: 20px;
    }

    .data-card {
        display: flex;
        align-items: flex-start;
        gap: 13px;

        padding: 17px;

        background: #fff;

        border: 1px solid #eee8f3;
        border-radius: 14px;
    }

    .data-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: var(--primary-light);
        color: var(--primary);
    }

    .data-card h6 {
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 3px;
    }

    .data-card p {
        font-size: 13px;
        margin: 0;
        line-height: 1.55;
    }


    /* =========================================================
       WARNING
    ========================================================= */

    .delete-warning {
        display: flex;
        gap: 14px;

        margin-top: 20px;

        padding: 20px;

        background: #fff8f5;

        border: 1px solid #f6ded5;
        border-radius: 14px;

        color: #745d55;
    }

    .delete-warning > i {
        color: var(--secondary);
        font-size: 20px;
        margin-top: 3px;
    }

    .delete-warning h6 {
        font-weight: 800;
        margin-bottom: 5px;
        color: #57443f;
    }

    .delete-warning p {
        margin: 0;
        font-size: 14px;
        line-height: 1.7;
    }


    /* =========================================================
       EMAIL REQUEST
    ========================================================= */

    .request-box {
        position: relative;
        overflow: hidden;

        padding: 32px;

        border-radius: 20px;

        color: #fff;

        background:
            linear-gradient(
                145deg,
                #5d0b95,
                #8116ad
            );

        margin-top: 20px;
    }

    .request-box::after {
        content: "";

        position: absolute;

        width: 230px;
        height: 230px;

        border-radius: 50%;

        background:
            rgba(255,255,255,.05);

        right: -90px;
        bottom: -130px;
    }

    .request-content {
        position: relative;
        z-index: 2;
    }

    .request-icon {
        width: 55px;
        height: 55px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 15px;

        background:
            rgba(255,255,255,.13);

        font-size: 21px;

        margin-bottom: 17px;
    }

    .request-box h4 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .request-box p {
        color: rgba(255,255,255,.78);
        font-size: 14px;
        line-height: 1.75;
        margin-bottom: 20px;
    }

    .request-email-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;

        padding: 13px 21px;

        background: #fff;

        color: var(--primary);

        border-radius: 11px;

        font-size: 14px;
        font-weight: 800;

        transition: .3s;
    }

    .request-email-btn:hover {
        color: var(--primary);
        transform: translateY(-2px);
    }


    /* =========================================================
       RETENTION
    ========================================================= */

    .retention-note {
        position: relative;
        overflow: hidden;

        padding: 20px;

        margin-top: 18px;

        border-radius: 14px;

        background: #fbf7fd;
        border: 1px solid #eee2f5;

        color: #6d6573;

        font-size: 14px;
        line-height: 1.75;
    }

    .retention-note::before {
        content: "";

        position: absolute;

        top: 0;
        bottom: 0;
        left: 0;

        width: 4px;

        background:
            linear-gradient(
                var(--primary),
                var(--secondary)
            );
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 767px) {

        .delete-section {
            padding: 45px 0 65px;
        }

        .delete-card {
            padding: 26px 19px;
            border-radius: 18px;
        }

        .data-grid {
            grid-template-columns: 1fr;
        }

        .delete-title {
            align-items: flex-start;
        }

        .delete-title h2 {
            font-size: 19px;
            padding-top: 8px;
        }

        .delete-intro {
            flex-direction: column;
        }

        .request-box {
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

                Account Management

            </div>


            <h1>

                Delete Your
                <span>Account</span>

            </h1>


            <p>

                You can request deletion of your Healing Guides
                account and associated personal information.
                Please review the information below before
                submitting your request.

            </p>

        </div>

    </div>

</section>



{{-- ============================================================
     DELETE ACCOUNT
============================================================ --}}

<section class="delete-section">

    <div class="container">

        <div class="delete-wrapper">

            <div class="delete-card">


                {{-- INTRO --}}

                <div class="delete-intro">

                    <div class="delete-intro-icon">

                        <i class="fa-solid fa-user-shield"></i>

                    </div>


                    <div>

                        <h4>
                            Account Deletion Request
                        </h4>

                        <p>

                            Healing Guides Wellness Services allows
                            users to request deletion of their account
                            and personal information associated with
                            that account.

                        </p>

                    </div>

                </div>



                {{-- WHO CAN REQUEST --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-solid fa-mobile-screen-button"></i>

                        </div>

                        <h2>
                            1. Applications Covered
                        </h2>

                    </div>


                    <p>

                        Account deletion requests may apply to
                        registered accounts created through Healing
                        Guides applications and services, including
                        applicable customer, hospital and other
                        authorized user applications.

                    </p>

                </div>



                {{-- HOW TO DELETE --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-solid fa-list-check"></i>

                        </div>

                        <h2>
                            2. How to Request Account Deletion
                        </h2>

                    </div>


                    <p>

                        You can submit an account deletion request
                        using the following process.

                    </p>


                    <div class="delete-steps">


                        <div class="delete-step">

                            <div class="step-number">
                                1
                            </div>

                            <div class="step-content">

                                <h5>
                                    Contact Healing Guides
                                </h5>

                                <p>

                                    Send an account deletion request
                                    to our support email address using
                                    the mobile number or email address
                                    associated with your account.

                                </p>

                            </div>

                        </div>



                        <div class="delete-step">

                            <div class="step-number">
                                2
                            </div>

                            <div class="step-content">

                                <h5>
                                    Provide Account Details
                                </h5>

                                <p>

                                    Include your registered name,
                                    mobile number and email address
                                    so we can identify the correct
                                    account.

                                </p>

                            </div>

                        </div>



                        <div class="delete-step">

                            <div class="step-number">
                                3
                            </div>

                            <div class="step-content">

                                <h5>
                                    Account Verification
                                </h5>

                                <p>

                                    We may verify that the request
                                    was submitted by the account
                                    owner before processing the
                                    deletion request.

                                </p>

                            </div>

                        </div>



                        <div class="delete-step">

                            <div class="step-number">
                                4
                            </div>

                            <div class="step-content">

                                <h5>
                                    Delete Account
                                </h5>

                                <p>

                                    After verification, the account
                                    deletion request will be processed
                                    in accordance with applicable
                                    requirements and our data retention
                                    obligations.

                                </p>

                            </div>

                        </div>


                    </div>

                </div>



                {{-- DATA DELETED --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-solid fa-trash-can"></i>

                        </div>

                        <h2>
                            3. Information That May Be Deleted
                        </h2>

                    </div>


                    <p>

                        Subject to applicable retention requirements,
                        deletion of an account may include deletion
                        or de-identification of personal information
                        associated with that account.

                    </p>


                    <div class="data-grid">


                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-user"></i>

                            </div>

                            <div>

                                <h6>
                                    Profile Information
                                </h6>

                                <p>

                                    Name, profile photo and other
                                    account profile information.

                                </p>

                            </div>

                        </div>



                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-address-card"></i>

                            </div>

                            <div>

                                <h6>
                                    Contact Information
                                </h6>

                                <p>

                                    Mobile number, email and other
                                    contact information.

                                </p>

                            </div>

                        </div>



                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-location-dot"></i>

                            </div>

                            <div>

                                <h6>
                                    Saved Addresses
                                </h6>

                                <p>

                                    Saved addresses and applicable
                                    location information.

                                </p>

                            </div>

                        </div>



                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-gear"></i>

                            </div>

                            <div>

                                <h6>
                                    Account Preferences
                                </h6>

                                <p>

                                    Preferences and other information
                                    associated with your account.

                                </p>

                            </div>

                        </div>


                    </div>

                </div>



                {{-- RETAINED DATA --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-solid fa-box-archive"></i>

                        </div>

                        <h2>
                            4. Information We May Retain
                        </h2>

                    </div>


                    <p>

                        Some information may not be deleted immediately
                        where retention is necessary or permitted for
                        legitimate purposes.

                    </p>


                    <div class="data-grid">


                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-receipt"></i>

                            </div>

                            <div>

                                <h6>
                                    Transaction Records
                                </h6>

                                <p>

                                    Records required for payment,
                                    accounting or transaction purposes.

                                </p>

                            </div>

                        </div>



                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-file-medical"></i>

                            </div>

                            <div>

                                <h6>
                                    Required Records
                                </h6>

                                <p>

                                    Records that must be retained
                                    under applicable legal or
                                    regulatory requirements.

                                </p>

                            </div>

                        </div>



                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-shield-halved"></i>

                            </div>

                            <div>

                                <h6>
                                    Security Records
                                </h6>

                                <p>

                                    Information required for security,
                                    fraud prevention or abuse
                                    investigation.

                                </p>

                            </div>

                        </div>



                        <div class="data-card">

                            <div class="data-icon">

                                <i class="fa-solid fa-scale-balanced"></i>

                            </div>

                            <div>

                                <h6>
                                    Legal Records
                                </h6>

                                <p>

                                    Information necessary to comply
                                    with applicable legal obligations
                                    or resolve disputes.

                                </p>

                            </div>

                        </div>


                    </div>


                    <div class="retention-note">

                        <strong>Please note:</strong>

                        Where information must be retained for
                        legitimate legal, regulatory, transaction,
                        security or operational purposes, it may be
                        stored for the required retention period and
                        deleted or anonymized when that retention
                        requirement ends.

                    </div>

                </div>



                {{-- EFFECT OF DELETION --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-solid fa-circle-exclamation"></i>

                        </div>

                        <h2>
                            5. What Happens After Deletion?
                        </h2>

                    </div>


                    <p>

                        Once your account has been deleted, you may
                        lose access to information and services
                        associated with the account.

                    </p>


                    <div class="delete-warning">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                        <div>

                            <h6>
                                Account deletion may be permanent
                            </h6>

                            <p>

                                After the deletion process has been
                                completed, some account information
                                may not be recoverable. If you use
                                Healing Guides again, you may need
                                to create a new account.

                            </p>

                        </div>

                    </div>

                </div>



                {{-- PROCESSING TIME --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-regular fa-clock"></i>

                        </div>

                        <h2>
                            6. Processing Your Request
                        </h2>

                    </div>


                    <p>

                        We will review valid account deletion requests
                        and process them within a reasonable period,
                        subject to identity verification and any
                        applicable legal or regulatory requirements.

                    </p>


                    <p>

                        We may contact you if additional information
                        is required to verify or process your request.

                    </p>

                </div>



                {{-- REQUEST --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-solid fa-envelope-open-text"></i>

                        </div>

                        <h2>
                            7. Submit a Deletion Request
                        </h2>

                    </div>


                    <p>

                        To request deletion of your Healing Guides
                        account, contact our support team using the
                        email address below.

                    </p>


                    <div class="request-box">

                        <div class="request-content">


                            <div class="request-icon">

                                <i class="fa-solid fa-user-xmark"></i>

                            </div>


                            <h4>
                                Delete My Account
                            </h4>


                            <p>

                                Send us an email from your registered
                                email address or include your registered
                                mobile number.

                                Please use
                                <strong>"Account Deletion Request"</strong>
                                as the email subject.

                            </p>


                            <a
                                href="mailto:support@healingguides.in?subject=Account%20Deletion%20Request"
                                class="request-email-btn"
                            >

                                <i class="fa-solid fa-envelope"></i>

                                Request Account Deletion

                            </a>


                        </div>

                    </div>

                </div>



                {{-- CONTACT --}}

                <div class="delete-item">

                    <div class="delete-title">

                        <div class="delete-title-icon">

                            <i class="fa-solid fa-headset"></i>

                        </div>

                        <h2>
                            8. Contact Us
                        </h2>

                    </div>


                    <p>

                        If you have questions about account deletion
                        or the information associated with your
                        account, contact:

                    </p>


                    <p>

                        <strong>
                            Healing Guides Wellness Services
                        </strong>

                        <br>

                        Email:
                        <a href="mailto:support@healingguides.in"
                           style="color:var(--primary);font-weight:700;">

                            support@healingguides.in

                        </a>

                        <br>

                        Phone: +91 98765 43210

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