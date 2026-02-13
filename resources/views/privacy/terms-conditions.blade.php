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
                            <li>This document is an electronic record in terms of Information Technology Act, 2000 and rules
there under as applicable and the amended provisions pertaining to electronic records in various
statutes as amended by the Information Technology Act, 2000. This electronic record is generated
by a computer system and does not require any physical or digital signatures.</li>

                            <li>This document is published in accordance with the provisions of Rule 3 (1) of the Information
Technology (Intermediaries guidelines) Rules, 2011 that require publishing the rules and
regulations, privacy policy and Terms of Use for access or usage of domain name
https://clearmycar.in/ ('Website'), including the related mobile site and mobile application
(hereinafter referred to as 'Platform').</li>

                            <li>The Platform is owned by 9765602932, a company incorporated under the Companies Act, 1956
with its registered office at 252,3 Devratna Apt Nr PMC, Shirole Lane Shivajinagar Pune
411030 (hereinafter referred to as ‘Platform Owner’, 'we', 'us', 'our')..</li>

                            <li>Your use of the Platform and services and tools are governed by the following terms and
conditions (“Terms of Use”) as applicable to the Platform including the applicable policies which
are incorporated herein by way of reference. If You transact on the Platform, You shall be subject
to the policies that are applicable to the Platform for such transaction. By mere use of the Platform,
You shall be contracting with the Platform Owner and these terms and conditions including the
policies constitute Your binding obligations, with Platform Owner. These Terms of Use relate to
your use of our website, goods (as applicable) or services (as applicable) (collectively, 'Services').
Any terms and conditions proposed by You which are in addition to or which conflict with these
Terms of Use are expressly rejected by the Platform Owner and shall be of no force or effect.
These Terms of Use can be modified at any time without assigning any reason. It is your
responsibility to periodically review these Terms of Use to stay informed of updates..</li>

                            <li>For the purpose of these Terms of Use, wherever the context so requires ‘you’, 'your' or ‘user’ shall
mean any natural or legal person who has agreed to become a user.</li>

                            <li>ACCESSING, BROWSING OR OTHERWISE USING THE PLATFORM INDICATES YOUR
AGREEMENT TO ALL THE TERMS AND CONDITIONS UNDER THESE TERMS OF USE,
SO PLEASE READ THE TERMS OF USE CAREFULLY BEFORE PROCEEDING.</li>

                            <li>To access and use the Services, you agree to provide true, accurate and complete information
to us during and after registration, and you shall be responsible for all acts done through the
use of your registered account on the Platform..</li>

                            <li>We do not provide warranties regarding accuracy or completeness...</li>

                            <li>Your use of our Services and the Platform is solely and entirely at your own risk and
discretion for which we shall not be liable to you in any manner. You are required to
independently assess and ensure that the Services meet your requirements...</li>

                            <li>The contents of the Platform and the Services are proprietary to us and are licensed to us.
                            <li>
                                You will not have any authority to claim any intellectual property rights, title, or interest in
its contents. The contents includes and is not limited to the design, layout, look and graphics..</li>
<li>You acknowledge that unauthorized use of the Platform and/or the Services may lead to
action against you as per these Terms of Use and/or applicable laws..</li>
<li>You agree to pay us the charges associated with availing the Services..</li>
<li>You agree not to use the Platform and/ or Services for any purpose that is unlawful, illegal or
forbidden by these Terms, or Indian or local laws that might apply to you.</li>
<li>You agree and acknowledge that website and the Services may contain links to other third
party websites. On accessing these links, you will be governed by the terms of use, privacy
policy and such other policies of such third party websites. These links are provided for your
convenience for provide further information..</li>
<li>You understand that upon initiating a transaction for availing the Services you are entering
into a legally binding and enforceable contract with the Platform Owner for the Services..</li>
<li>You shall indemnify and hold harmless Platform Owner, its affiliates, group companies (as
applicable) and their respective officers, directors, agents, and employees, from any claim or
demand, or actions including reasonable attorney's fees, made by any third party or penalty
imposed due to or arising out of Your breach of this Terms of Use, privacy Policy and other
Policies, or Your violation of any law, rules or regulations or the rights (including
infringement of intellectual property rights) of a third party.</li>
<li>Notwithstanding anything contained in these Terms of Use, the parties shall not be liable for
any failure to perform an obligation under these Terms if performance is prevented or
delayed by a force majeure event..</li>
<li>These Terms and any dispute or claim relating to it, or its enforceability, shall be governed
by and construed in accordance with the laws of India..</li>
<li>All disputes arising out of or in connection with these Terms shall be subject to the exclusive
jurisdiction of the courts in Pune and Maharashtra.</li>
<li>All concerns or communications relating to these Terms must be communicated to us using
the contact information provided on this website</li>

                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
