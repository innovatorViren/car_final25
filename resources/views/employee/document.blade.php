<div class="row">
    <div class="col-lg-12">
        <div class="gutter-b">
            <br>
            <div class="font-weight-bold" style="color:#9d9595;">
                <h3 class="card-title font-weight-bolder">Aadhar Card&nbsp;&nbsp;&nbsp;
                    @if (isset($employee->aadharcard_img_path) && $employee->aadharcard_img_path != '')
                        <a href="{{ asset($employee->aadharcard_img_path) }}" download><i
                                class="fa fa-download"></i></a>
                    @endif
            </div>
            <h6>
                <div class="font-weight-bold mt-n6" style="color:#000000;">
                    {{ $employee->aadhar_card_no ?? '-' }}</div>
            </h6>
            <div class="" style="width: 7rem;">
                <a href="">
                    <img src="{{ isset($employee) && !empty($employee->aadharcard_img_path) ? asset($employee->aadharcard_img_path) : asset('default.jpg') }}"
                        class="img-preview img-rounded max-h-100px mt-3" alt="...">
                </a>
            </div>
        </div>
    </div>
    

</div>
