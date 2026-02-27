@include('partials._loginheader.login-header')

<style>
    body {
        margin: 0;
        padding: 0;
        background: #ffffff;
        font-family: 'Segoe UI', sans-serif;
        color: #333;
    }

    .policy-wrapper {
        padding: 60px 8%;
    }

    .logo-section {
        margin-bottom: 30px;
    }

    .logo-section img {
        max-width: 200px;
    }

    .policy-title {
        font-weight: 600;
        font-size: 30px;
        color: #FF2A00;
        margin-bottom: 20px;
    }

    .policy-content {
        font-size: 15px;
        line-height: 1.9;
    }

    .policy-section {
        margin-bottom: 35px;
    }

    .policy-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #222;
    }

    .policy-section ul {
        padding-left: 20px;
    }

    .policy-section li {
        margin-bottom: 6px;
    }

    .contact-box {
        margin-top: 20px;
    }

    .contact-box a {
        color: #FF2A00;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .policy-wrapper {
            padding: 30px 20px;
        }

        .policy-title {
            font-size: 24px;
        }
    }
</style>

<body>
    <div >

        <div class="logo-section">
            <img src="{{ asset('media/logos/car.png') }}" alt="Logo"/>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="policy-card">

                    <h1 class="policy-title">Privacy Policy</h1>

                    <div class="policy-content">

                        <div class="policy-section">
                            <h3>Effective Date</h3>
                            <p><strong>27 February 2026</strong></p>
                            <p>
                                Clear My Car respects your privacy and is committed to protecting your personal information.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>1. Information We Collect</h3>

                            <p><strong>Personal Information</strong></p>
                            <ul>
                                <li>Name</li>
                                <li>Phone number</li>
                                <li>Email address</li>
                                <li>Vehicle details</li>
                                <li>Service address/location</li>
                            </ul>

                            <p><strong>Technical Information</strong></p>
                            <ul>
                                <li>Device type</li>
                                <li>IP address</li>
                                <li>App usage data</li>
                            </ul>
                        </div>

                        <div class="policy-section">
                            <h3>2. How We Use Information</h3>
                            <p>We use collected data to:</p>
                            <ul>
                                <li>Process bookings</li>
                                <li>Deliver car wash services</li>
                                <li>Send booking confirmations and updates</li>
                                <li>Improve user experience</li>
                                <li>Provide customer support</li>
                            </ul>
                        </div>

                        <div class="policy-section">
                            <h3>3. Data Sharing</h3>
                            <p>We may share information with:</p>
                            <ul>
                                <li>Assigned service personnel</li>
                                <li>Payment gateway providers</li>
                                <li>Legal authorities (if required by law)</li>
                            </ul>
                            <p><strong>We do not sell personal data to third parties.</strong></p>
                        </div>

                        <div class="policy-section">
                            <h3>4. Data Security</h3>
                            <p>
                                We implement reasonable technical and administrative measures to protect your data. 
                                However, no online system can guarantee complete security.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>5. Data Retention</h3>
                            <p>
                                We retain personal data only as long as necessary for service delivery, 
                                legal compliance, and business purposes.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>6. User Rights</h3>
                            <p>You may:</p>
                            <ul>
                                <li>Request access to your data</li>
                                <li>Request correction</li>
                                <li>Request deletion</li>
                                <li>Withdraw consent</li>
                            </ul>
                            <p>Requests can be made via customer support.</p>
                        </div>

                        <div class="policy-section">
                            <h3>7. Children’s Privacy</h3>
                            <p>
                                Our services are not intended for individuals under 18 years of age.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>8. Updates to Policy</h3>
                            <p>
                                We may update this Privacy Policy periodically. Continued use of the App 
                                constitutes acceptance of changes.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Contact Us</h3>
                            <div class="contact-box">
                                <p><strong>Email:</strong> 
                                    <a href="mailto:info@clearmycar.in">info@clearmycar.in</a>
                                </p>
                                <p><strong>Website:</strong> 
                                    <a href="https://www.clearmycar.in" target="_blank">
                                        www.clearmycar.in
                                    </a>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
