<?php $page = 'signin-2'; ?>
@extends('layout.mainlayout')

@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #F4F7FC;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .verify-card {
            width: 450px;
            max-width: 100%;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            padding: 40px;
            box-shadow:
                0 10px 25px rgba(0, 0, 0, .05),
                0 25px 60px rgba(0, 0, 0, .08);
            position: relative;
            overflow: hidden;
        }

        .verify-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #4F46E5;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo img {
            height: 75px;
        }

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h2 {
            color: #111827;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .heading p {
            color: #6B7280;
            font-size: 15px;
            line-height: 24px;
        }

        .form-label {
            display: block;
            color: #374151;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .form-control {
            height: 58px;
            border: 1px solid #D1D5DB;
            border-right: none;
            background: #FFFFFF;
            color: #111827;
            border-radius: 12px 0 0 12px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 8px;
        }

        .form-control::placeholder {
            color: #9CA3AF;
            letter-spacing: 3px;
            font-size: 18px;
        }

        .form-control:focus {
            border-color: #4F46E5;
            background: #FFFFFF;
            color: #111827;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, .10);
        }

        .input-group-text {
            background: #F9FAFB;
            border: 1px solid #D1D5DB;
            border-left: none;
            color: #6B7280;
            border-radius: 0 12px 12px 0;
        }

        .form-control:focus+.input-group-text {
            border-color: #4F46E5;
        }

        .btn-verify {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 12px;
            background: #4F46E5;
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        .btn-verify:hover {
            background: #4338CA;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, .20);
        }

        .btn-verify:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .bottom-links {
            margin-top: 25px;
            text-align: center;
        }

        .bottom-links a {
            color: #4F46E5;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }

        .bottom-links a:hover {
            color: #4338CA;
            text-decoration: underline;
        }

        .bottom-links i {
            margin-right: 5px;
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .text-danger {
            font-size: 13px;
        }

        @media(max-width:576px) {

            .login-wrapper {
                padding: 20px;
            }

            .verify-card {
                padding: 30px 22px;
                border-radius: 18px;
            }

            .logo img {
                height: 60px;
            }

            .heading h2 {
                font-size: 24px;
            }

            .heading p {
                font-size: 14px;
            }

            .form-control {
                font-size: 18px;
                letter-spacing: 5px;
            }

        }
    </style>

    <div class="login-wrapper">

        <div class="verify-card">

            <div class="logo">
                <img src="{{ asset($site->site_logo) }}" alt="Logo">
            </div>

            <div class="heading">
                <h2>Verify OTP 🔐</h2>
                <p>Enter the 6-digit verification code sent to your registered email.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.verifyOtp') }}">
                @csrf

                <input type="hidden" name="email" value="{{ session('email') }}">

                <label class="form-label">OTP Code</label>

                <div class="input-group">

                    <input type="text" name="otp" maxlength="6" autocomplete="one-time-code"
                        class="form-control @error('otp') is-invalid @enderror" placeholder="000000" required>

                    <span class="input-group-text">
                        <i class="ti ti-key"></i>
                    </span>

                </div>

                @error('otp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <button type="submit" class="btn-verify">
                    Verify OTP
                </button>

                <div class="bottom-links mt-4">

                    <a href="{{ route('admin.password.request') }}">
                        <i class="ti ti-refresh"></i>
                        Resend OTP
                    </a>

                    <br><br>

                    <a href="{{ route('admin.login') }}">
                        <i class="ti ti-arrow-left"></i>
                        Back to Login
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection