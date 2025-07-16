@extends('app-modal')
@section('modal-title', __('customers.change_managed_by'))
@section('modal-content')

    {!! Form::open([
        'route' => 'customer-update-primary-managed',
        'role' => 'form',
        'id' => 'updatePrimaryManagedForm',
    ]) !!}
    {!! Form::hidden('id', $customer->id) !!}
    <div class="form-group">
        {!! Form::label('primary_managed_by', trans('customers.form.primary_managed_by')) !!}<i class="text-danger">*</i>
        {{ Form::select('primary_managed_by', ['' => 'select'] + $employees, $customer->managed_by ?? null, ['class' => 'form-control required jsPrimaryManagedBy', 'id' => 'primary_managed_by', 'data-placeholder' => 'Select Primary Managed By']) }}
    </div>

@section('modal-btn', __('common.save'))
{!! Form::close() !!}
<script type="text/javascript">
    $(document).ready(function() {
        initValidation();
        $('.jsPrimaryManagedBy').select2();
    });

    var initValidation = function() {
        $('#updatePrimaryManagedForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select,input")',
            rules: {},
            messages: {},
            errorPlacement: function(error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function(e) {
                $('#btn_loader').addClass('spinner spinner-white spinner-left');
                $('#btn_loader').prop('disabled', true);
                return true;
            }
        });
    };
    $(document).on('click', '#btn_loader', function() {
        $('#updatePrimaryManagedForm').submit();
    });
</script>
@endsection
