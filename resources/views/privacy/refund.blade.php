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
        margin-bottom: 25px;
    }

    .policy-content {
        font-size: 15px;
        line-height: 1.8;
        color: #444;
    }

    .policy-content h3 {
        font-size: 20px;
        font-weight: 600;
        margin-top: 25px;
        margin-bottom: 15px;
        color: #222;
    }

    .policy-content ol {
        padding-left: 20px;
    }

    .policy-content li {
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
                    
                    <h1 class="policy-title">Refund & Cancellation Policy</h1>

                    <div class="policy-content">

                        <h3>Introduction</h3>
                        <p>
                            This Refund and Cancellation Policy outlines how you can cancel or seek a refund 
                            for a product or service purchased through our Platform.
                        </p>

                        <ol>
                            <li>
                                Cancellation requests will only be considered if made within 
                                <strong>7 days</strong> of placing the order. However, cancellation may not 
                                be accepted if the order has already been processed, shipped, or is out for delivery. 
                                In such cases, you may reject the product at the time of delivery.
                            </li>

                            <li>
                                We do not accept cancellation requests for perishable items such as flowers, 
                                eatables, etc. However, refunds or replacements may be provided if the delivered 
                                product quality is proven to be unsatisfactory.
                            </li>

                            <li>
                                In case of damaged or defective items, please report the issue to our customer 
                                service team within <strong>7 days</strong> of receipt. The seller/merchant will 
                                verify the complaint before approving any refund or replacement.
                            </li>

                            <li>
                                For products covered under manufacturer warranty, please contact the 
                                respective manufacturer directly for support and resolution.
                            </li>

                            <li>
                                Once a refund is approved, it will be processed within 
                                <strong>7 working days</strong>.
                            </li>
                        </ol>

                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
