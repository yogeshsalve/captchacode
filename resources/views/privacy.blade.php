@extends('layouts.app')

@section('content')

<style>
    /* Privacy Section Styles */
    .privacy {
        background-color: #f9f9f9;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
        max-height: 70vh; /* Allow content to grow, but give it a max height */
        overflow-y: auto; /* Enable vertical scrolling */
    }

    .privacy h1 {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
    }

    .privacy p,
    .privacy ul {
        font-size: 1rem;
        color: #555;
    }

    .privacy ul {
        list-style-type: square;
        padding-left: 20px;
    }

    .privacy li {
        margin-bottom: 10px;
    }

    .privacy a {
        text-decoration: none;
        color: #fff;
        background-color: #ffc107;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    .privacy a:hover {
        background-color: #e0a800;
    }

    /* Ensure the page looks good on mobile devices */
    @media (max-width: 768px) {
        .privacy h1 {
            font-size: 1.5rem;
        }

        .privacy p,
        .privacy ul {
            font-size: 0.95rem;
        }

        .privacy a {
            font-size: 1rem;
            padding: 8px 16px;
        }
    }
    
    /* Make sure the entire body is scrollable */
    body {
        height: 100vh;
        overflow-y: auto;
    }
</style>

<div class="container py-5">
    <div class="privacy">
        <h1>Privacy Policy</h1>
        
        <p><strong>Effective Date:</strong> Oct 2022</p>
        
        <p> CaptchaCode is committed to protecting your personal data. This policy outlines how we collect, use, and safeguard your information.</p>

        <h5 class="mt-4">1. <strong>What We Collect</strong></h5>
        <ul>
            <li>Name, email, phone number, and address</li>
            <li>Payment details (only transaction metadata)</li>
            <li>Captcha performance data (accuracy, timestamps)</li>
        </ul>

        <h5 class="mt-4">2. <strong>Use of Information</strong></h5>
        <ul>
            <li>To register you as a user</li>
            <li>To manage captcha records and process payouts</li>
            <li>To improve platform services</li>
        </ul>

        <h5 class="mt-4">3. <strong>Data Sharing</strong></h5>
        <ul>
            <li>We do not sell your data.</li>
            <li>Data may be shared with payment gateways or law enforcement if required.</li>
        </ul>

        <h5 class="mt-4">4. <strong>Cookies</strong></h5>
        <ul>
            <li>We use basic cookies to maintain user sessions and improve experience.</li>
        </ul>

        <h5 class="mt-4">5. <strong>Data Security</strong></h5>
        <ul>
            <li>We employ SSL, encrypted storage, and secured hosting for data protection.</li>
        </ul>

        <h5 class="mt-4">6. <strong>Your Rights</strong></h5>
        <ul>
            <li>You can request to access or delete your data by contacting <strong>support@taskit-labs.com</strong>.</li>
        </ul>

        <!-- Back to Home Button -->
        <div class="text-center mt-4">
            <a href="{{ url('/user/dashboard') }}" class="back-btn">Back to Home</a>
        </div>
    </div>
</div>

@endsection
