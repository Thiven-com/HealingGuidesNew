<?php $page = 'signin-2'; ?>
@extends('layout.mainlayout')

@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #6D28D9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
            animation: float 8s ease-in-out infinite;
        }

        body::before {
            width: 320px;
            height: 320px;
            top: -120px;
            left: -80px;
        }

        body::after {
            width: 260px;
            height: 260px;
            bottom: -120px;
            right: -80px;
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(30px);
            }
        }

        .login-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
        }

        .login-card {
            width: 430px;
            max-width: 100%;
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .15);
            position: relative;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #6D28D9;
            border-radius: 20px 20px 0 0;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo {
            width: 80px;
            margin-bottom: 15px;
        }

        .login-header h2 {
            color: #111827;
            font-size: 28px;
            font-weight: 700;
        }

        .login-header p {
            color: #6B7280;
        }

        .form-group {

            margin-bottom: 22px;

        }

        .form-group label {

            display: block;

            font-weight: 600;

            margin-bottom: 8px;

            color: #374151;

        }

        .input-box {

            position: relative;

        }

        .input-box i {

            position: absolute;

            top: 18px;

            left: 18px;

            font-size: 20px;

            color: #9CA3AF;

        }

        .input-box input {

            width: 100%;

            height: 58px;

            border: 1px solid #E5E7EB;

            border-radius: 14px;

            padding-left: 55px;

            padding-right: 50px;

            font-size: 15px;

            transition: .3s;

            outline: none;

        }

        .input-box input:focus {
            border-color: #6D28D9;
            box-shadow: 0 0 0 4px rgba(109, 40, 217, .12);
        }

        .toggle-password {

            position: absolute;

            right: 18px;

            top: 18px;

            cursor: pointer;

            color: #9CA3AF;

        }

        .login-options {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 28px;

            font-size: 14px;

        }

        .login-options a {
            color: #6D28D9;
            text-decoration: none;
            font-weight: 600;
        }

        .login-btn {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 12px;
            background: #6D28D9;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        .login-btn:hover {
            background: #5B21B6;
        }

        .bottom-text {

            text-align: center;

            margin-top: 30px;

            color: #6B7280;

            font-size: 13px;

        }

        .alert {

            border-radius: 12px;

            margin-bottom: 20px;

        }

        @media(max-width:576px) {

            .login-card {

                padding: 30px 22px;

            }

            .login-header h2 {

                font-size: 26px;

            }

        }
    </style>

    <div class="login-wrapper">

        <div class="login-card">

            <div class="login-header">

                <img src="{{ asset($site->site_logo ?? ' ') }}" class="login-logo">

                <h2>Welcome Back 👋</h2>

                <p>Sign in to continue to your dashboard</p>

            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.loginAction') }}" method="POST">

                @csrf

                <div class="form-group">

                    <label>Email Address</label>

                    <div class="input-box">

                        <i class="ti ti-mail"></i>

                        <input type="email" name="email" value="{{ old('email', $email ?? '') }}"
                            placeholder="Enter your email" required>

                    </div>

                </div>

                <div class="form-group">

                    <label>Password</label>

                    <div class="input-box">

                        <i class="ti ti-lock"></i>

                        <input type="password" class="pass-input" name="password" placeholder="Enter your password"
                            required>

                        <span class="toggle-password">

                            <i class="ti ti-eye-off"></i>

                        </span>

                    </div>

                </div>

                <div class="login-options">

                    <label>

                        <input type="checkbox" name="remember">

                        Remember Me

                    </label>

                    <a href="{{ route('admin.password.request') }}">

                        Forgot Password?

                    </a>

                </div>

                <button type="submit" class="login-btn">

                    <i class="ti ti-login"></i>

                    Sign In

                </button>

            </form>

            <div class="bottom-text">

                © {{ date('Y') }} {{ $site->site_name ?? ' '}}

            </div>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const toggle = document.querySelector(".toggle-password");
            const input = document.querySelector(".pass-input");

            toggle.addEventListener("click", function () {

                if (input.type === "password") {

                    input.type = "text";

                    this.innerHTML = '<i class="ti ti-eye"></i>';

                } else {

                    input.type = "password";

                    this.innerHTML = '<i class="ti ti-eye-off"></i>';

                }

            });

        });
    </script>

@endsection