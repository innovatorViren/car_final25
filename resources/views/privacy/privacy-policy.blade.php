@include('partials._loginheader.login-header')
<style>
    .listing-header {
        font-weight: 300 !important;
        font-size: 32px !important;
        color: #FF2A00;
    }
</style>
<body style="background-image: url('{{ asset('media/bg/bg-3.jpg') }}');">
    <div class="container">
        <div class="row">
            <div class="col-sm-12" style="height: 100px;">
                <img src="{{ asset('media/logos/car.png') }}" width="250" height="100"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div style="height: 15px;"></div>         
                <h1 class="h2 m-0 text-capitalize font-weight-bold">PRIVACY POLICY</h1>
                <div class="inews-item pt-4" style="margin-bottom: 0px;font-size: 16px !important;">
                    <p><strong>ClearMyCar respects your privacy and is committed to protecting your personal information. This policy explains how we collect, use, and safeguard your data.</strong></p>

                    <h3>Information We Collect</h3>
                    <p>We may collect personal information such as name, phone number, email address, service address, vehicle details, booking details (plan, date, time), and app usage data.</p>

                    <h3>How We Use Information</h3>
                    <p>We use your information to:</p>
                    <ul>
                        <li>Provide and manage car wash services</li>
                        <li>Schedule and assign service staff</li>
                        <li>Communicate booking updates and support</li>
                        <li>Improve app performance and user experience</li>
                        <li>Ensure security and prevent fraud</li>
                    </ul>

                    <h3>Location Data</h3>
                    <p>ClearMyCar collects location data only with user permission to identify service locations accurately and to help our staff reach customers. Location data is used strictly for service delivery and operational purposes.</p>

                    <h3>Data Sharing</h3>
                    <p>We do not sell your personal data. Data may be shared with trusted third-party services (such as payment or analytics providers) only as required to operate the app.</p>

                    <h3>Data Security</h3>
                    <p>We take reasonable measures to protect user data from unauthorized access or misuse.</p>

                    <h3>Children’s Privacy</h3>
                    <p>ClearMyCar does not knowingly collect personal data from children under 13 years of age.</p>

                    <h3>User Rights</h3>
                    <p>Users may request access, correction, or deletion of their personal data by contacting us.</p>

                    <h3>Contact Us</h3>
                    <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                    <p>Email: <a href="mailto:info@clearmycar.in">info@clearmycar.in</a></p>
                    <p>Website: <a href="https://www.clearmycar.in" target="_blank">https://www.clearmycar.in</a></p>

                </div>
            </div>
        </div>
    </div>
    <div style="height: 150px;"></div>
</body>
