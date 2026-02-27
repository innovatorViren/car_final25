@include('partials._loginheader.login-header')

<style>
    body {
        margin: 0;
        padding: 0;
        background: #ffffff;   /* Remove dark overlay background */
        font-family: 'Segoe UI', sans-serif;
        color: #444;
    }

    .policy-wrapper {
        width: 100%;
        padding: 60px 10%;
        background: rgba(255, 255, 255, 0.95);
        color: #444;
        min-height: 100vh;
    }

    .policy-title {
        font-weight: 600;
        font-size: 32px;
        color: #FF2A00;
        border-bottom: 2px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .effective-date {
        font-size: 14px;
        margin-bottom: 30px;
        color: #777;
    }

    .policy-content {
        font-size: 16px;
        line-height: 1.9;
    }

    .policy-content h3 {
        font-size: 20px;
        font-weight: 600;
        margin-top: 35px;
        margin-bottom: 15px;
        color: #222;
    }

    .policy-content ul {
        padding-left: 20px;
    }

    .policy-content li {
        margin-bottom: 10px;
    }

    .contact-box {
        background: #f8f9fa;
        padding: 20px;
        margin-top: 20px;
        border-left: 4px solid #FF2A00;
    }

    .logo-section {
        text-align: center;
        padding: 40px 0 20px;
        background: transparent;
    }

    .logo-section img {
        max-width: 220px;
    }

    @media (max-width: 768px) {
        .policy-wrapper {
            padding: 40px 20px;
        }

        .policy-title {
            font-size: 24px;
        }
    }
</style>

<body>

<div class="logo-section">
    <img src="{{ asset('media/logos/car.png') }}" alt="Logo"/>
</div>

<div class="policy-wrapper">

    <h1 class="policy-title">Refund Policy</h1>
    <p class="effective-date"><strong>Effective Date:</strong> 27 February 2026</p>

    <div class="policy-content">

        <h3>1. Eligible Refunds</h3>
        <p>Refunds may be granted if:</p>
        <ul>
            <li>Service was not delivered</li>
            <li>Payment was deducted but booking not confirmed</li>
            <li>Verified major service deficiency</li>
        </ul>

        <h3>2. Non-Refundable Cases</h3>
        <p>Refunds will not be provided for:</p>
        <ul>
            <li>Customer no-show</li>
            <li>Cancellation after service provider arrival</li>
            <li>Minor dissatisfaction without valid proof</li>
        </ul>

        <h3>3. Refund Process</h3>
        <ul>
            <li>Refund requests must be submitted within 24 hours of service.</li>
            <li>Include booking ID and reason.</li>
            <li>Approved refunds will be processed within 5–7 business days.</li>
        </ul>
        <p>Refunds will be credited to the original payment method.</p>

        <h3>4. Subscription Plans</h3>
        <ul>
            <li>Subscription fees are non-refundable once activated.</li>
            <li>Unused services may expire as per plan terms.</li>
        </ul>

        <h3>Contact Information</h3>
        <div class="contact-box">
            <p><strong>Business Name:</strong> KK META SERVICES</p>
            <p><strong>Address:</strong> 252,253/2, Ground Floor, Shop No-1, Devratna Apartment, Pune</p>
            <p><strong>Email:</strong> sales.kkmetaservices@gmail.com</p>
            <p><strong>Phone:</strong> 9765602932</p>
        </div>

    </div>

</div>

</body>