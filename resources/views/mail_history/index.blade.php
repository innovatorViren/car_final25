{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')

@component('partials._subheader.subheader-v6',
   [
   'page_title' => __('mail_history.mail_history'),
   'back_text' => __('common.back'),
    'model_back_action' => route('masterPages'),
   ])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            
            <div class="card-body">
                <table class="table table-separate table-head-custom table-checkable" id="mail_history">
                    <thead>
                         <tr>
                            <th></th>
                            <th>{{__('mail_history.mail_type')}}</th>
                            <th></th>
                            <th>{{__('mail_history.send_date')}}</th>
                            <th>{{__('common.email')}}</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>{{__('common.action')}}</th>
                            <th>{{__('mail_history.mail_type')}}</th>
                            <th>{{__('mail_history.company_name')}}</th>
                            <th>{{__('mail_history.send_date')}}</th>
                            <th>{{__('common.email')}}</th>
                            <th>{{__('mail_history.total_due_amount')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <a href=""><i class="fa fa-eye"></i></a>
                            </td>
                            <td>QuotationEmail</td>
                            <td></td>
                            <td>02-02-2020</td>
                            <td>dalsukh.parmar@sphererays.net</td>
                            <td>171.69</td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <a href=""><i class="fa fa-eye"></i></a>
                            </td>
                            <td>QuotationEmail</td>
                            <td>Gopal</td>
                            <td>07-09-2019</td>
                            <td>dalsukh.parmar@sphererays.net</td>
                            <td>635.55</td>
                        </tr>                   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('mail_history.show')
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/pages/crud/datatables/mail_history.js') }}" type="text/javascript"></script>
@endsection