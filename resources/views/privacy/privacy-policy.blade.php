@include('partials._loginheader.login-header')

<style>
    body {
        background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
                    url('{{ asset('media/bg/bg-3.jpg') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
    }

    .policy-wrapper {
        margin-top: 40px;
        margin-bottom: 60px;
    }

    .policy-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        padding: 40px;
    }

    .policy-title {
        font-weight: 600;
        font-size: 28px;
        color: #FF2A00;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .policy-content {
        font-size: 15px;
        line-height: 1.8;
        color: #444;
    }

    .policy-section {
        margin-bottom: 30px;
    }

    .policy-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #222;
        position: relative;
    }

    .policy-section h3::after {
        content: "";
        display: block;
        width: 40px;
        height: 3px;
        background: #FF2A00;
        margin-top: 6px;
    }

    .logo-section {
        text-align: center;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .logo-section img {
        max-width: 220px;
    }

    .contact-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .contact-box a {
        color: #FF2A00;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .policy-card {
            padding: 25px;
        }

        .policy-title {
            font-size: 22px;
        }
    }
</style>

<body>
    <div class="container policy-wrapper">

        <div class="logo-section">
            <img src="{{ asset('media/logos/car.png') }}" alt="Logo"/>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="policy-card">

                    <h1 class="policy-title">Privacy Policy</h1>

                    <div class="policy-content">

                        <div class="policy-section">
                            <h3>Introduction</h3>
                            <p>
                                This Privacy Policy describes how 9765602932 and its affiliates collect,
                                use, share and protect your personal information through our website
                                https://clearmycar.in/ (“Platform”).
                            </p>
                            <p>
                                By using this Platform, you agree to the collection and processing of
                                your information in accordance with this policy and applicable laws of India.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Information We Collect</h3>
                            <p>
                                We may collect personal details such as your name, date of birth, address,
                                phone number, email ID, identity proof, payment details, and other
                                information provided during registration or while using our services.
                            </p>
                            <p>
                                Sensitive information such as bank details or biometric data will only be
                                collected with your explicit consent and in accordance with applicable laws.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Usage of Information</h3>
                            <p>
                                Your data is used to provide services, process transactions, enhance
                                customer experience, resolve disputes, detect fraud, conduct marketing
                                research, and communicate offers and updates.
                            </p>
                            <p>
                                You may opt-out of marketing communications at any time.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Sharing of Information</h3>
                            <p>
                                We may share your information with affiliates, sellers, logistics partners,
                                payment providers, and government authorities where required by law.
                                Third-party partners are governed by their respective privacy policies.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Security Measures</h3>
                            <p>
                                We adopt reasonable security practices to protect your information.
                                However, internet transmission carries inherent risks, and users are
                                responsible for safeguarding their login credentials.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Data Retention & Deletion</h3>
                            <p>
                                You may delete your account via your profile settings. We may retain
                                certain information where legally required or necessary to prevent fraud.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Your Rights</h3>
                            <p>
                                You may access, update, or correct your personal information through
                                your account dashboard. You may also withdraw consent by contacting us.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Changes to This Policy</h3>
                            <p>
                                We may update this Privacy Policy periodically. Please review it regularly
                                to stay informed about how we protect your information.
                            </p>
                        </div>

                        <div class="policy-section">
                            <h3>Grievance Officer & Contact</h3>

                            <div class="contact-box">
                                <p><strong>Email:</strong> 
                                    <a href="mailto:info@clearmycar.in">info@clearmycar.in</a>
                                </p>
                                <p><strong>Website:</strong> 
                                    <a href="https://www.clearmycar.in" target="_blank">
                                        www.clearmycar.in
                                    </a>
                                </p>
                                <p><strong>Support Hours:</strong> Monday – Friday (9:00 AM – 6:00 PM)</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
