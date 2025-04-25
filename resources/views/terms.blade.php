@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f9f9f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .terms-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 900px;
        margin: 50px auto;
        overflow-y: auto; /* Scrollable content */
        height: 80vh; /* Limit height for scroll */
    }

    h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 20px;
    }

    p {
        font-size: 1rem;
        line-height: 1.6;
        color: #333;
    }

    h5 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-top: 30px;
        color: #0d6efd;
    }

    ul {
        list-style-type: none;
        padding-left: 0;
    }

    ul li {
        font-size: 1rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 12px;
        padding-left: 20px;
        position: relative;
    }

    ul li:before {
        content: "";
        position: absolute;
        left: 0;
    }

    .back-btn {
        margin-top: 30px;
        padding: 12px 30px;
        font-size: 1.1rem;
        color: #fff;
        background-color: #0d6efd;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .back-btn:hover {
        background-color: #0b5ed7;
    }

    .terms-container p, .terms-container h5, .terms-container ul {
        margin-bottom: 20px;
    }

    /* Styling for smooth scroll behavior */
    .terms-container {
        scroll-behavior: smooth;
    }
</style>
<div class="terms-container">
    <h1>Terms & Conditions</h1>
    <p><strong>Effective Date:</strong> Oct 2022</p>
    <p>Welcome to <strong>{{ config('app.name') }}</strong>. By using our platform and services, you agree to the following:</p>

    <h5>1. User Eligibility</h5>
    <ul>
        <li>Users must be 18 years or older to register and participate.</li>
        <li>Accurate information must be provided during registration.</li>
    </ul>

    <h5>2. Services Offered</h5>
    <ul>
        <li>Registered users can complete captcha tasks in exchange for monetary rewards.</li>
        <li>A one-time non-refundable registration fee of ₹999 grants access to up to 15,000 captcha tasks.</li>
    </ul>

    <h5>3. Earnings & Rewards</h5>
    <ul>
        <li>For each correct captcha: ₹0.50 will be credited.</li>
        <li>For each incorrect captcha: ₹0.25 will be deducted.</li>
        <li>Only upon reaching ₹5,000 will a user be eligible to request withdrawal.</li>
    </ul>

    <h5>4. Withdrawal & KYC</h5>
    <ul>
        <li>Withdrawals require valid KYC documentation (PAN/Aadhaar).</li>
        <li>Withdrawals are processed within 7–14 working days.</li>
    </ul>

    <h5>5. Restrictions</h5>
    <ul>
        <li>Use of bots, scripts, or automation tools is strictly prohibited.</li>
        <li>Violators will be permanently banned with no refund or payout.</li>
    </ul>

    <h5>6. Refunds</h5>
    <ul>
        <li>The ₹999 registration fee is non-refundable under any circumstances.</li>
    </ul>

    <h5>7. Liability</h5>
    <ul>
        <li>We are not responsible for internet issues, downtime, or user error.</li>
        <li>We reserve the right to suspend accounts for any suspicious activity.</li>
    </ul>

    <h5>8. Changes to Terms</h5>
    <ul>
        <li>We may modify these terms at any time. Continued use of the platform means you accept the changes.</li>
    </ul>

    <ul>
        <li>If you have any questions, please contact us at <strong>support@taskit-labs.com</strong>.</li>
    </ul>
    <!-- Back to Home Button -->
    <a href="{{ url('/user/dashboard') }}" class="back-btn">Back to Home</a>
</div>
@endsection
