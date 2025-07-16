<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public $title, $data;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->title = trans("reports.reports");
        view()->share('title', $this->title);
    }

    public function index()
    {
        return view('reports.index');
    }
}
