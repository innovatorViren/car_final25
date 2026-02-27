@include('partials._loginheader.login-header')

<style>
    body {
        margin: 0;
        padding: 0;
        background: #ffffff;   /* Remove dark overlay background */
        font-family: 'Segoe UI', sans-serif;
        color: #444;
    }

    .terms-wrapper {
        width: 100%;
        max-width: 100%;
        padding: 60px 10%;
    }

    .terms-title {
        font-weight: 600;
        font-size: 34px;
        color: #FF2A00;
        margin-bottom: 10px;
    }

    .effective-date {
        font-size: 14px;
        margin-bottom: 30px;
        color: #777;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .terms-section {
        margin-bottom: 35px;
    }

    .terms-section h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #222;
    }

    .terms-section ul {
        padding-left: 20px;
    }

    .terms-section li {
        margin-bottom: 8px;
    }

    .logo-section {
        text-align: center;
        padding: 40px 0 20px;
        background: #f8f9fa;
    }

    .logo-section img {
        max-width: 220px;
    }

    @media (max-width: 768px) {
        .terms-wrapper {
            padding: 30px 20px;
        }

        .terms-title {
            font-size: 26px;
        }
    }
</style>

<body>
<div>

    <div class="logo-section">
        <img src="{{ asset('media/logos/car.png') }}" alt="Logo"/>
    </div>

    <div class="terms-wrapper">

        <h1 class="terms-title">Terms & Conditions</h1>
        <p class="effective-date">
            <strong>Effective Date:</strong> 27 February 2026<br>
            <strong>App Name:</strong> Clear My Car<br>
            <strong>Platform:</strong> Android Application (carwash.clearMyCar)
        </p>

        <div class="terms-section">
            <h3>1. Acceptance of Terms</h3>
            <p>
                By downloading, registering, or using the Clear My Car mobile application (“App”),
                you agree to be bound by these Terms & Conditions. If you do not agree,
                please discontinue use of the App.
            </p>
        </div>

        <div class="terms-section">
            <h3>2. Services Provided</h3>
            <p>Clear My Car provides on-demand car wash and detailing services at customer-selected locations. Services may include:</p>
            <ul>
                <li>Exterior car wash</li>
                <li>Interior cleaning</li>
                <li>Waterless wash</li>
                <li>Subscription-based cleaning packages</li>
                <li>Add-on detailing services</li>
            </ul>
            <p>All services are subject to availability and service area limitations.</p>
        </div>

        <div class="terms-section">
            <h3>3. User Eligibility</h3>
            <ul>
                <li>Be at least 18 years old</li>
                <li>Provide accurate and complete registration details</li>
                <li>Use the App only for lawful purposes</li>
            </ul>
            <p>We reserve the right to suspend or terminate accounts that provide false or misleading information.</p>
        </div>

        <div class="terms-section">
            <h3>4. Booking & Payments</h3>
            <ul>
                <li>All bookings must be made through the App.</li>
                <li>Prices will be displayed before confirmation.</li>
                <li>Payments may be collected online or offline as specified.</li>
                <li>Clear My Car reserves the right to revise pricing at any time.</li>
            </ul>
            <p>Failure to complete payment may result in cancellation or suspension of services.</p>
        </div>

        <div class="terms-section">
            <h3>5. Cancellation Policy</h3>
            <ul>
                <li>Customers may cancel before service provider dispatch without charge.</li>
                <li>Cancellation after dispatch may attract a service fee.</li>
                <li>No-show at service location may be charged fully or partially.</li>
            </ul>
        </div>

        <div class="terms-section">
            <h3>6. Service Conditions</h3>
            <ul>
                <li>Customer must ensure vehicle accessibility at the scheduled time.</li>
                <li>Clear My Car is not responsible for pre-existing vehicle damage.</li>
                <li>Complaints must be raised within 24 hours of service completion.</li>
            </ul>
        </div>

        <div class="terms-section">
            <h3>7. Limitation of Liability</h3>
            <p>Clear My Car shall not be liable for:</p>
            <ul>
                <li>Indirect or incidental damages</li>
                <li>Delays due to weather or unforeseen circumstances</li>
                <li>Loss of personal belongings left inside the vehicle</li>
            </ul>
            <p>Maximum liability shall not exceed the service amount paid.</p>
        </div>

        <div class="terms-section">
            <h3>8. Intellectual Property</h3>
            <p>
                All app content, trademarks, logos, and branding belong to Clear My Car
                and may not be copied or reproduced without prior written consent.
            </p>
        </div>

        <div class="terms-section">
            <h3>9. Termination</h3>
            <p>We reserve the right to suspend or terminate accounts in case of:</p>
            <ul>
                <li>Fraudulent activity</li>
                <li>Abuse of staff</li>
                <li>Violation of these Terms</li>
            </ul>
        </div>

        <div class="terms-section">
            <h3>10. Governing Law</h3>
            <p>
                These Terms shall be governed by the laws of India.
                Disputes shall be subject to the jurisdiction of the courts where the company is registered.
            </p>
        </div>

    </div>
</div>
</body>