<div class="modal fade" id="commonModalID" data-backdrop="static" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog @yield('modal-size')" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@yield('modal-title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>

            <div class="modal-body">
                @yield('modal-content')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">{{ __('common.close') }}</button>
                <button type="submit" class="btn btn-primary font-weight-bold btnSave"
                    id="btn_loader">@yield('modal-btn')</button>
                @yield('modal-btn1')
            </div>
        </div>
    </div>
</div>
@php
$defultYear = getDefaultYear();
$formDate = $toDate = '';
if ($defultYear) {
    $formDate = $defultYear->from_date;
    $toDate = $defultYear->to_date;
}
@endphp
<script>
    $(document).ready(function() {
        $('.defult-date').attr('min', '{{ $formDate }}');
        $('.defult-date').attr('max', '{{ $toDate }}');
    });
</script>
