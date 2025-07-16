<div class="row">
    <div class="col-lg-12">
        <br>
        <div class="font-weight-bold" style="color:#9d9595;">
            <h3 class="card-title font-weight-bolder">UAN &nbsp;&nbsp;&nbsp;
        </div>
        <h6>
            <div class="font-weight-bold mt-n6" style="color:#000000;">{{ $employee->employeeDocument->uan_no }}</div>
        </h6>
    </div>
    <div class="col-lg-6">
        <div class="gutter-b">
            <br>
            <div class="font-weight-bold" style="color:#9d9595;">
                <h3 class="card-title font-weight-bolder">Aadhar Card&nbsp;&nbsp;&nbsp;
                    @if (isset($employee->employeeDocument->aadharcard_img_path) && $employee->employeeDocument->aadharcard_img_path != '')
                        <a href="{{ asset($employee->employeeDocument->aadharcard_img_path) }}" download><i
                                class="fa fa-download"></i></a>
                    @endif
            </div>
            <h6>
                <div class="font-weight-bold mt-n6" style="color:#000000;">
                    {{ $employee->employeeDocument->aadhar_card_no }}</div>
            </h6>
            <div class="" style="width: 7rem;">
                <a href="">
                    <img src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->aadharcard_img_path) ? asset($employee->employeeDocument->aadharcard_img_path) : asset('default.jpg') }}"
                        class="img-preview img-rounded max-h-100px mt-3" alt="...">
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-custom card-stretch card-shadowless gutter-b">
            <br>
            <div class="font-weight-bold" style="color:#9d9595;">
                <h3 class="card-title font-weight-bolder">Driving Licence&nbsp;&nbsp;&nbsp;
                    @if (isset($employee->employeeDocument->drivinglicence_img_path) &&
                            $employee->employeeDocument->drivinglicence_img_path != '')
                        <a href="{{ asset($employee->employeeDocument->drivinglicence_img_path) }}" download><i
                                class="fa fa-download"></i></a>
                    @endif
            </div>
            <h6>
                <div class="font-weight-bold mt-n6" style="color:#000000;">
                    {{ $employee->employeeDocument->driving_licence_no }}</div>
            </h6>
            <div class="" style="width: 7rem;">
                <a href="">
                    <img src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->drivinglicence_img_path) ? asset($employee->employeeDocument->drivinglicence_img_path) : asset('default.jpg') }}"
                        class="img-preview img-rounded max-h-100px mt-3" alt="...">
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-custom card-stretch card-shadowless gutter-b">
            <div class="font-weight-bold" style="color:#9d9595;">
                <h3 class="card-title font-weight-bolder">PAN Card&nbsp;&nbsp;&nbsp;
                    @if (isset($employee->employeeDocument->pancard_img_path) && $employee->employeeDocument->pancard_img_path != '')
                        <a href="{{ asset($employee->employeeDocument->pancard_img_path) }}" download><i
                                class="fa fa-download"></i></a>
                    @endif
            </div>
            <h6>
                <div class="font-weight-bold mt-n6" style="color:#000000;">
                    {{ $employee->employeeDocument->pan_card_no }}</div>
            </h6>
            <div class="" style="width: 7rem;">
                <a href="">
                    <img src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->pancard_img_path) ? asset($employee->employeeDocument->pancard_img_path) : asset('default.jpg') }}"
                        class="img-preview img-rounded max-h-100px mt-3" alt="...">
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-custom card-stretch card-shadowless gutter-b">
            <div class="font-weight-bold" style="color:#9d9595;">
                <h3 class="card-title font-weight-bolder">Passport&nbsp;&nbsp;&nbsp;
                    @if (isset($employee->employeeDocument->passport_img_path) && $employee->employeeDocument->passport_img_path != '')
                        <a href="{{ asset($employee->employeeDocument->passport_img_path) }}" download><i
                                class="fa fa-download"></i></a>
                    @endif
            </div>
            <h6>
                <div class="font-weight-bold mt-n6" style="color:#000000;">
                    {{ $employee->employeeDocument->passport_no }}</div>
            </h6>
            <div class="" style="width: 7rem;">
                <a href="">
                    <img src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->passport_img_path) ? asset($employee->employeeDocument->passport_img_path) : asset('default.jpg') }}"
                        class="img-preview img-rounded max-h-100px mt-3" alt="...">
                </a>
            </div>
        </div>
    </div>
</div>
