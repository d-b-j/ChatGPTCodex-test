<?php
/*
|--------------------------------------------------------------------------
| Public Start / Login Page
|--------------------------------------------------------------------------
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Alumni Member Portal</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
<link
    href="assets/css/theme.css"
    rel="stylesheet"
>
    <style>
 
        :root {

            --primary-bg: #95150F;
            --secondary-bg: #6F100B;
            --light-bg: #F6A6A2;

            --white: #ffffff;
            --dark-text: #2b2b2b;
        }

        body {

            background:
                linear-gradient(
                    135deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            font-family:
                Inter,
                Arial,
                sans-serif;
        }

        .portal-wrapper {

            width: 100%;
            max-width: 1100px;

            background: var(--white);

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 10px 40px rgba(0,0,0,0.25);
        }

        .left-panel {

            background:
                linear-gradient(
                    180deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );

            color: var(--white);

            padding: 60px;

            height: 100%;
        }

        .brand-title {

            font-size: 42px;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-subtitle {

            margin-top: 20px;

            font-size: 18px;

            opacity: 0.9;
        }

        .feature-list {

            margin-top: 40px;
        }

        .feature-item {

            margin-bottom: 16px;

            padding-left: 28px;

            position: relative;
        }

        .feature-item::before {

            content: "•";

            position: absolute;

            left: 0;

            font-size: 22px;
        }

        .right-panel {

            padding: 60px;
            background: #fff;
        }

        .login-title {

            font-size: 34px;
            font-weight: 700;

            color: var(--primary-bg);
        }

        .login-subtitle {

            margin-top: 10px;

            color: #666;
        }

        .form-control {

            border-radius: 12px;

            padding: 14px;

            border: 1px solid #ddd;
        }

        .form-control:focus {

            border-color: var(--primary-bg);

            box-shadow:
                0 0 0 0.2rem rgba(149,21,15,0.15);
        }

        .btn-login {

            background: var(--primary-bg);

            color: var(--white);

            border: none;

            border-radius: 12px;

            padding: 14px;

            font-weight: 600;

            transition: 0.2s;
        }

        .btn-login:hover {

            background: var(--secondary-bg);
        }

        .quick-links {

            margin-top: 30px;
        }

        .quick-links a {

            color: var(--primary-bg);

            text-decoration: none;

            font-weight: 500;
        }

        .quick-links a:hover {

            text-decoration: underline;
        }

        .footer-text {

            margin-top: 30px;

            font-size: 13px;

            color: #888;
        }

        .school-badge {

            display: inline-block;

            background: rgba(255,255,255,0.1);

            padding: 10px 16px;

            border-radius: 999px;

            margin-bottom: 25px;

            font-size: 14px;
        }

        @media(max-width: 768px) {

            .left-panel,
            .right-panel {

                padding: 35px;
            }

            .brand-title {

                font-size: 32px;
            }
        } 

    </style>

</head>
<body>


<div class="portal-wrapper">

    <div class="row g-0">

        <!-- LEFT -->
        <div class="col-lg-6">

            <div class="left-panel">

                <div class="school-badge">
                    QR Powered Alumni System
                </div>

                <div class="brand-title">
                    Alumni Member Portal
                </div>

                <div class="brand-subtitle">
                    Secure alumni identity verification,
                    contribution tracking,
                    membership management,
                    and QR powered profile access.
                </div>

                <div class="feature-list">

                    <div class="feature-item">
                        QR based member verification
                    </div>

                    <div class="feature-item">
                        Annual membership fee tracking
                    </div>

                    <div class="feature-item">
                        Contributions & attachments
                    </div>

                    <div class="feature-item">
                        Secure member profile access
                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">
            <div class="right-panel">
                <div class="text-center">
                  <img src="/assets/images/ssckpca.png" alt="Logo" class="img-fluid" style="max-width: 70%; height: auto;">
                </div>
                <div class="login-title">
                    Sign In
                </div>

                <div class="login-subtitle">
                    Access the alumni management portal
                </div>

                <form
                    method="POST"
                    action="/login"
                    class="mt-4"
                >

                    <div class="mb-3">

                        <label class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember"
                            >

                            <label
                                class="form-check-label"
                                for="remember"
                            >
                                Remember me
                            </label>

                        </div>

                        <a href="#">
                            Forgot password?
                        </a>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-login w-100"
                    >
                        Login
                    </button>

                </form>

                <div class="quick-links">

                    <div>
                        <a href="#">
                            Create New Account
                        </a>
                    </div>

                    <div class="mt-2">
                        <a href="/member">
                            View Public Member Profile
                        </a>
                    </div>

                </div>

                <div class="footer-text">
                    Alumni Member Information System
                    · Secure Access Portal
                </div>

            </div>

        </div>

    </div>

</div>


</body>
</html>