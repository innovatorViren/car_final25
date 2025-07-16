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
}
