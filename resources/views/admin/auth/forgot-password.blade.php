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
            margin: 0;
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

        .forgot-card {
            width: 450px;
            max-width: 100%;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            padding: 45px;
            box-shadow:
                0 10px 25px rgba(0, 0, 0, .05),
                0 25px 60px rgba(0, 0, 0, .08);
            position: relative;
            overflow: hidden;
        }

        .forgot-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
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
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
        }

        .heading p {
            color: #6B7280;
            font-size: 15px;
            line-height: 24px;
        }

        .form-label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-group {
            margin-bottom: 25px;
        }

        .input-group-text {
            background: #F9FAFB;
            border: 1px solid #D1D5DB;
            border-left: none;
            color: #6B7280;
            border-radius: 0 12px 12px 0;
        }

        .form-control {
            height: 56px;
            border: 1px solid #D1D5DB;
            border-right: none;
            background: #FFFFFF;
            color: #111827;
            border-radius: 12px 0 0 12px;
            font-size: 15px;
        }

        .form-control::placeholder {
            color: #9CA3AF;
        }

        .form-control:focus {
            background: #FFFFFF;
            color: #111827;
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, .10);
        }

        .form-control:focus+.input-group-text {
            border-color: #4F46E5;
        }

        .btn-send {
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

        .btn-send:hover {
            background: #4338CA;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, .20);
        }

        .btn-send:focus {
            box-shadow: none;
        }

        .back-login {
            text-align: center;
            margin-top: 25px;
        }

        .back-login a {
            color: #4F46E5;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }

        .back-login a:hover {
            color: #4338CA;
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            color: #9CA3AF;
            font-size: 13px;
        }

        /* Mobile */

        @media(max-width:576px) {

            .login-wrapper {
                padding: 20px;
            }

            .forgot-card {
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

        }
    </style>

    <div class="login-wrapper">

        <div class="forgot-card">

            <div class="logo">
                <img src="{{ asset($site->site_logo) }}" alt="Logo">
            </div>

            <div class="heading">
                <h2>Forgot Password 🔐</h2>
                <p>Enter your registered email to receive an OTP.</p>
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

            <form method="POST" action="{{ route('admin.password.sendOtp') }}">
                @csrf

                <label class="form-label">Email Address</label>

                <div class="input-group">

                    <input type="email" name="email" class="form-control" placeholder="Enter Email Address" required>

                    <span class="input-group-text">
                        <i class="ti ti-mail"></i>
                    </span>

                </div>

                <button type="submit" class="btn-send">
                    Send OTP
                </button>

                <div class="back-login">
                    <a href="{{ route('admin.login') }}">
                        <i class="ti ti-arrow-left"></i> Back to Login
                    </a>
                </div>

            </form>

        </div>

    </div>

@endsection