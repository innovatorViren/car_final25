{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
    @component('partials._subheader.subheader-v6', [
        'page_title' => __('settings.general_settings'),
    ])
    @endcomponent
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-body">

                    <div class="example">
                        <div class="example-preview">
                            <div class="row">
                                <div class="col-lg-3">
                                    <!--begin::Navigation-->
                                    <ul class="navi navi-link-rounded navi-accent navi-hover navi-active nav flex-column mb-8 mb-lg-0"
                                        role="tablist">
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link active" id="home-tab-5" data-toggle="tab" href="#home-5">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.company') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="profile-tab-5" data-toggle="tab" href="#profile-5"
                                                aria-controls="profile">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.bank_details') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="contact-tab-5" data-toggle="tab" href="#contact-5"
                                                aria-controls="contact">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.barcode') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="contact-tab-6" data-toggle="tab" href="#contact-6"
                                                aria-controls="contact">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.quotation') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="contact-tab-7" data-toggle="tab" href="#contact-7"
                                                aria-controls="contact">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.hrm') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="contact-tab-8" data-toggle="tab" href="#contact-8"
                                                aria-controls="contact">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.production') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="contact-tab-9" data-toggle="tab" href="#contact-9"
                                                aria-controls="contact">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.account') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="contact-tab-10" data-toggle="tab" href="#contact-10"
                                                aria-controls="contact">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.others') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                        <!--begin::Nav Item-->
                                        <li class="navi-item mb-2">
                                            <a class="navi-link" id="contact-tab-11" data-toggle="tab" href="#contact-11"
                                                aria-controls="contact">
                                                <span class="nav-icon mr-3">
                                                    <i class="flaticon2-rocket-1"></i>
                                                </span>
                                                <span class="navi-text">{{ __('settings.smtp_configuration') }}</span>
                                            </a>
                                        </li>
                                        <!--end::Nav Item-->
                                    </ul>
                                    <!--end::Navigation-->
                                </div>
                                <div class="col-lg-9">
                                    <!--begin::Tab Content-->
                                    <div class="tab-content">
                                        <!--begin::Accordion-->
                                        <div class="tab-pane fade show active" id="home-5" role="tabpanel"
                                            aria-labelledby="home-tab-5">
                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label>{{ __('settings.project_title') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" placeholder="">
                                                </div>

                                                <div class="form-group col-lg-6">
                                                    <label>{{ __('settings.company_name') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>{{ __('settings.company_address') }} :<i
                                                        class="text-danger">*</i></label>
                                                <textarea class="form-control" id="exampleTextarea" rows="2"></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.gst_no') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.pan_no') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.state') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="profile-5" role="tabpanel"
                                            aria-labelledby="profile-tab-5">

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.account_number') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.ifsc_code') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.bank_name') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.phone_no') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.email') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.bill_print') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <select class="form-control" id="bill_print" name="bill_print"
                                                        style="width: 100%;">
                                                        <option value="AK">Original</option>
                                                        <option value="HI">Duplicate</option>
                                                        <option value="CA">Triplicate</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>{{ __('settings.term_condition') }} :<i
                                                        class="text-danger">*</i></label>
                                                <textarea class="form-control" id="exampleTextarea" rows="2"></textarea>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="contact-5" role="tabpanel"
                                            aria-labelledby="contact-tab-5">

                                            <div class="row">
                                                <div class="col-12 pb-4">
                                                    <h5>{{ __('settings.inward_category_barcode') }}</h5>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.inward') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.basic_printing') }} {{ __('settings.height') }}
                                                        :<i class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.lamination') }} {{ __('settings.height') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.ink') }} {{ __('settings.height') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.solvants') }} {{ __('settings.height') }}:<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.adhesive') }} {{ __('settings.height') }}:<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.doctor_blade') }}
                                                        {{ __('settings.height') }}:<i class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.sellotape') }} {{ __('settings.height') }}:<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.granules') }} {{ __('settings.height') }}:<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.wastage') }} {{ __('settings.height') }}:<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.pouch') }} {{ __('settings.height') }}:<i
                                                            class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-12 pb-4">
                                                    <h5>{{ __('settings.production_category_barcode') }}</h5>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.finish_good_barcode') }}
                                                        {{ __('settings.height') }} :<i class="text-danger">*</i></label>
                                                    <input class="form-control" type="text" value=""
                                                        id="example-text-input" placeholder="mm">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="contact-6" role="tabpanel"
                                            aria-labelledby="contact-tab-6">
                                            <div class="form-group">
                                                <label>{{ __('settings.quotation_roll_note') }} :<i
                                                        class="text-danger">*</i></label>
                                                <textarea class="form-control" id="exampleTextarea" rows="5"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>{{ __('settings.quotation_pouch_note') }} :<i
                                                        class="text-danger">*</i></label>
                                                <textarea class="form-control" id="exampleTextarea" rows="5"></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label>{{ __('settings.quotation_tax') }}:<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" name="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="contact-7" role="tabpanel"
                                            aria-labelledby="contact-tab-7">

                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label>{{ __('settings.labor_welfare_fund') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                style=" font-family: sans-serif;">₹</span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="">
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="form-group pt-3">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox checkbox-square col-lg-3">
                                                                    <input type="checkbox" checked="checked"
                                                                        name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.january') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.february') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.march') }}</label>

                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.april') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.may') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.june') }}</label>

                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.july') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.august') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.september') }}</label>

                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.october') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.november') }}</label>
                                                                <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                    <input type="checkbox" name="Checkboxes13_1">
                                                                    <span></span>{{ __('settings.december') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-6">
                                                    <label>{{ __('settings.provident_fund') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                style=" font-family: sans-serif;">₹</span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="contact-8" role="tabpanel"
                                            aria-labelledby="contact-tab-8">

                                            <div class="form-group">
                                                <label>{{ __('settings.variation_categories') }} :<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" placeholder="">
                                                <br>
                                                <div class="row">
                                                    <div class="form-group pt-3">
                                                        <div class="checkbox-inline">
                                                            <label class="checkbox checkbox-square col-lg-4">
                                                                <input type="checkbox" checked="checked"
                                                                    name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.basic_printing') }}</label>
                                                            <label class="checkbox checkbox-square col-lg-3">
                                                                <input type="checkbox" name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.lamination') }}</label>
                                                            <label class="checkbox checkbox-square col-lg-3">
                                                                <input type="checkbox" name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.ink') }}</label>

                                                            <label class="checkbox checkbox-square col-lg-4 pt-3">
                                                                <input type="checkbox" name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.solvants') }}</label>
                                                            <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                <input type="checkbox" name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.adhesive') }}</label>
                                                            <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                <input type="checkbox" name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.doctor_blade') }}</label>

                                                            <label class="checkbox checkbox-square col-lg-4 pt-3">
                                                                <input type="checkbox" name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.granules') }}</label>
                                                            <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                                <input type="checkbox" name="Checkboxes13_1">
                                                                <span></span>{{ __('settings.pouch') }}</label>
                                                            <label class="checkbox checkbox-square col-lg-3 pt-3">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label>{{ __('settings.allow_ip_tv_display') }} :</label>
                                                <input type="text" class="form-control" placeholder="">
                                            </div>
                                            <h6 class="text-danger" style="font-size: 12px;">
                                                {{ __('settings.allow_ip_tv_display_note') }}</h6>
                                        </div>

                                        <div class="tab-pane fade" id="contact-9" role="tabpanel"
                                            aria-labelledby="contact-tab-9">
                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label>{{ __('settings.tcs_tax') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="contact-10" role="tabpanel"
                                            aria-labelledby="contact-tab-10">
                                            <div class="form-group">
                                                <label>{{ __('settings.po_source') }} :<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" placeholder="">
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.form_submit_seconds') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" placeholder="IN SECONDS">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.past_entry_time') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" placeholder="IN HOURS">
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label>{{ __('settings.production_hours') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" placeholder="IN HOURS">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label>{{ __('settings.remaining_meter') }} :<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" placeholder="IN METER">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="contact-11" role="tabpanel"
                                            aria-labelledby="contact-tab-11">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>{{ __('settings.driver') }} :<i
                                                                class="text-danger">*</i></label>
                                                        <select class="form-control" id="driver" name="driver"
                                                            style="width: 100%;" data-placeholder="Select Driver">
                                                            <option></option>
                                                            <option value="AK">SMTP</option>
                                                            <option value="HI">Mailgun</option>
                                                            <option value="CA">Log</option>
                                                        </select>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-lg-7">
                                                            <label>{{ __('settings.host') }} :<i
                                                                    class="text-danger">*</i></label>
                                                            <input type="text" class="form-control" placeholder="">
                                                        </div>

                                                        <div class="form-group col-lg-5 pt-9">
                                                            <button type="button" class="btn btn-outline-primary"
                                                                data-toggle="modal"
                                                                data-target="#sendtestmail">{{ __('settings.send_test_mail') }}</button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-lg-6">
                                                            <label>{{ __('settings.encryption') }} :</label>
                                                            <select class="form-control" id="encryption"
                                                                name="encryption" style="width: 100%;"
                                                                data-placeholder="Select Encryption">
                                                                <option></option>
                                                                <option value="CA">None</option>
                                                                <option value="AK">TLS</option>
                                                                <option value="HI">SSL</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label>{{ __('settings.port') }} :<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>{{ __('settings.from_name') }} :<i
                                                                class="text-danger">*</i></label>
                                                        <input type="password" class="form-control" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>{{ __('settings.user_name') }} :<i
                                                                class="text-danger">*</i></label>
                                                        <input type="text" class="form-control" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>{{ __('settings.password') }} :<span
                                                                class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Accordion-->
                                    </div>
                                    <!--end::Tab Content-->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <a href="" class="mr-2">{{ __('common.cancel') }}</a>
                                    <button type="reset" class="btn btn-primary">{{ __('common.save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendtestmail" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('settings.test_mail') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('settings.to') }} :<i class="text-danger">*</i></label>
                        <input type="text" class="form-control" placeholder="">
                    </div>

                    <div class="form-group">
                        <label>{{ __('settings.subject') }} :<i class="text-danger">*</i></label>
                        <input type="text" class="form-control" placeholder="">
                    </div>

                    <div class="form-group">
                        <label>{{ __('settings.text') }} :<i class="text-danger">*</i></label>
                        <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary font-weight-bold">{{ __('common.save') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#bill_print').select2();
        $('#driver').select2();
        $('#encryption').select2();
    </script>
@endsection
