@include('partials._loginheader.login-header')

<style>
    body {
        background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
                    url('{{ asset('media/bg/bg-3.jpg') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
    }

    .terms-wrapper {
        margin-top: 40px;
        margin-bottom: 60px;
    }

    .terms-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        padding: 40px;
    }

    .terms-title {
        font-weight: 600;
        font-size: 30px;
        color: #FF2A00;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .terms-content {
        font-size: 15px;
        line-height: 1.8;
        color: #444;
    }

    .terms-content ol {
        padding-left: 20px;
    }

    .terms-content li {
        margin-bottom: 15px;
    }

    .logo-section {
        text-align: center;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .logo-section img {
        max-width: 220px;
    }

    @media (max-width: 768px) {
        .terms-card {
            padding: 25px;
        }

        .terms-title {
            font-size: 24px;
        }
    }
</style>

<body>
    <div class="container terms-wrapper">
        
        <div class="logo-section">
            <img src="{{ asset('media/logos/car.png') }}" alt="Logo"/>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="terms-card">
                    
                    <h1 class="terms-title">Terms & Conditions</h1>

                    <div class="terms-content">
                        <ol>
                            <li>This document is an electronic record in terms of the Information Technology Act, 2000...</li>

                            <li>This document is published in accordance with Rule 3 (1) of the Information Technology (Intermediaries Guidelines) Rules, 2011...</li>

                            <li>The Platform is owned by 9765602932, incorporated under the Companies Act, 1956 with its registered office at Pune...</li>

                            <li>Your use of the Platform and services are governed by these Terms of Use...</li>

                            <li>‘You’, ‘Your’, or ‘User’ refers to any natural or legal person who has agreed to become a user...</li>

                            <li>Accessing or using the platform indicates your agreement to these Terms...</li>

                            <li>To access Services, you agree to provide true and accurate information...</li>

                            <li>We do not provide warranties regarding accuracy or completeness...</li>

                            <li>Your use of the Platform is at your own risk...</li>

                            <li>You agree not to use the Platform for unlawful purposes...</li>

                            <li>You shall indemnify and hold harmless the Platform Owner...</li>

                            <li>These Terms shall be governed by the laws of India...</li>

                            <li>All disputes are subject to the exclusive jurisdiction of Pune courts...</li>

                            <li>All concerns must be communicated using contact information on this website.</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
