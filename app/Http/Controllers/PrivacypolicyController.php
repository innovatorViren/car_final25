<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacypolicyController extends Controller
{
    public function index() 
    {
        $this->data['title']= 'Privacy-Policy';
        return view('privacy.privacy-policy');
    }

    public function termsConditions() 
    {
        $this->data['title']= 'Terms & Conditions';
        return view('privacy.terms-conditions');
    }

    public function refund() 
    {
        $this->data['title']= 'Refund and Cancellation policy';
        return view('privacy.refund');
    }
}
