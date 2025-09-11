@section('styles')
    <style type="text/css">
        .select2-container .select2-search--inline .select2-search__field {
            margin-bottom: 4px !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/custom/kendotree/kendo.common.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/custom/kendotree/kendo.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/custom/kendotree/kendo.default.mobile.min.css') }}" />
@endsection
<div class="row">
    <div class="form-group col-lg-4">
        {!! Form::label('emp_type', trans('users.form.user_type')) !!}
        @if (isset($users))
            ({{ $users->emp_type }})
        @endif
        @if (isset($users))
            @if ($users->emp_type == 'employee' || $users->emp_type == 'non-employee')
                <i class="text-danger">*</i>
            @endif
        @else
            <i class="text-danger">*</i>
        @endif
        <div class="radio-inline pt-4">
            <label class="radio radio-rounded">
                {{ Form::radio('emp_type', 'employee', isset($users) ? 'null' : 'true', [
                    'class' =>
                        'form-check-input emp_type' .
                        (isset($users) && $users->emp_type == 'employee' ? ' required' : '') .
                        (isset($users) && $users->emp_type == 'customer' ? ' ' : ' ') .
                        (isset($users) && $users->emp_type == 'non-employee' ? ' ' : ' '),
                    isset($users) ? 'disabled' : '',
                    'id' => 'employee',
                ]) }}
                <span></span>{{ __('users.form.employee') }}
            </label>
            <label class="radio radio-rounded">
                {{ Form::radio('emp_type', 'non-employee', null, [
                    'class' =>
                        'form-check-input emp_type' .
                        (isset($users) && $users->emp_type == 'employee' ? ' ' : '') .
                        (isset($users) && $users->emp_type == 'customer' ? ' ' : ' ') .
                        (isset($users) && $users->emp_type == 'non-employee' ? ' required' : ' '),
                    isset($users) ? 'disabled' : '',
                    'id' => 'non-employee',
                ]) }}
                <span></span>{{ __('users.form.non-employee') }}
            </label>
            <label class="radio radio-rounded">
                {{ Form::radio('emp_type', 'customer', null, [
                    'class' =>
                        'form-check-input emp_type' .
                        (isset($users) && $users->emp_type == 'employee' ? ' ' : '') .
                        (isset($users) && $users->emp_type == 'customer' ? 'required' : ' ') .
                        (isset($users) && $users->emp_type == 'non-employee' ? '' : ' '),
                    isset($users) ? 'disabled' : '',
                    'id' => 'customer',
                ]) }}
                <span></span>{{ __('users.form.customer') }}
            </label>
        </div>
    </div>
    <div class="form-group col-lg-8 employeeData"
        style="display:{{ (isset($users) && $users->emp_type == 'non-employee'
                ? 'none'
                : 'block' && isset($users) && $users->emp_type == 'customer')
            ? 'none'
            : 'block' }}">
        {!! Form::label('emp_id', trans('users.form.employee_name')) !!}
        <i class="text-danger">*</i>
        {{ Form::select('emp_id', ['' => 'Select Employee'] + $employees, null, [
            'class' => 'form-control text-dark',
            'id' => 'emp_id',
            isset($users) ? 'disabled' : '',
            'data-placeholder' => 'Select Employee',
        ]) }}
    </div>
</div>
@php
    $readonly1 = '';
    $form_control = '';
    if (isset($users) && $users->emp_type == 'employee') {
        $readonly1 = 'readonly';
        $form_control = 'form-control-solid';
    } else {
        $readonly1 = '';
        $form_control = 'readonly';
    }
@endphp
<div class="row">
    <div class="form-group col-lg-4">
        {!! Form::label('first_name', trans('users.form.first_name')) !!}<span class="text-danger">*</span>
        {!! Form::text('first_name', null, [
            'class' => 'form-control ' . $form_control . ' required',
            'id' => 'first_name',
            $readonly1,
        ]) !!}
    </div>
    <div class="form-group col-lg-4">
        {!! Form::label('middle_name', trans('users.form.middle_name')) !!}
        {!! Form::text('middle_name', null, [
            'class' => 'form-control ' . $form_control . '',
            'id' => 'middle_name',
            $readonly1,
        ]) !!}
    </div>
    <div class="form-group col-lg-4">
        {!! Form::label('last_name', trans('users.form.last_name')) !!}<span class="text-danger">*</span>
        {!! Form::text('last_name', null, [
            'class' => 'form-control ' . $form_control . ' required',
            'id' => 'last_name',
            $readonly1,
        ]) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-4">
        {!! Form::label('email', trans('users.form.email')) !!}<span class="text-danger">*</span>
        {!! Form::email('email', null, ['class' => 'form-control required email', 'id' => 'email']) !!}
    </div>
    <div class="form-group col-lg-4">
        {!! Form::label('password', trans('users.form.password')) !!}<span class="text-danger">*</span>
        {!! Form::password('password', [
            'class' => 'form-control',
            'autocomplete' => 'off',
            'id' => 'password',
            'minlength' => 8,
        ]) !!}
        <small>{{ __('users.form.password_note') }}</small>
    </div>
    <div class="form-group col-lg-4">
        {!! Form::label('password_confirmation', trans('users.form.confirm_password')) !!}<span class="text-danger">*</span>
        {!! Form::password('password_confirmation', [
            'class' => 'form-control',
            'autocomplete' => 'off',
            'equalto' => '#password',
            'minlength' => 8,
        ]) !!}
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="row">
            <div class="form-group col-lg-6">
                {!! Form::label('mobile', trans('users.form.mobile')) !!}<span class="text-danger">*</span>
                {!! Form::number('mobile', null, ['class' => 'form-control required', 'id' => 'mobile']) !!}
            </div>
            <div class="form-group col-lg-6">
                {!! Form::label('roles_id', trans('users.form.roles')) !!}<i class="text-danger">*</i>
                {{ Form::select('roles_id', ['' => 'select'] + $roles, null, ['class' => 'form-control required cls-role', 'data-placeholder' => 'Select Roles']) }}
            </div>
            @if ($current_user->hasAnyAccess(['users.superadmin']))
                <div class="form-group col-lg-6">
                    <div class="checkbox-inline pt-1">
                        <label class="checkbox checkbox-square">
                            {!! Form::checkbox('allow_multi_login', '1', null, [
                                'id' => 'allow_multi_login',
                                'class' => 'allow_multi_login',
                            ]) !!}
                            <span></span>{!! Form::label('allow_multi_login', trans('Allow Multi Login'), ['class' => 'mt-2']) !!}</label>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group col-lg-12">
            <div class="showTreeViewPermission">
                <h6>Permissions</h6>
                <div id="treeview" class="cls-treeview"></div>
            </div>
            {!! Form::hidden('user_permission', '', ['id' => 'user_permission']) !!}
        </div>
    </div>
</div>

<div class="card-footer">
    <div class="row">
        <div class="col-6 p-2">
            Alt+A = Add, Alt+S = Save, Alt+B = Back.
        </div>
        <div class="col-6 text-right">
            {!! link_to(URL::full(), __('common.cancel'), ['class' => 'mr-3']) !!}
             <button type="submit" class="btn btn-primary mr-2 saveBtn" name="saveBtn">{{ __('common.save') }}</button>
        </div>
    </div>
</div>
@section('scripts')
    <script src="{{ asset('plugins/custom/kendotree/kendo.all.min.js') }}"></script>
    <script type="text/javascript">
        function favicon(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#photo_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#photo").change(function() {
            favicon(this);
        });
    </script>
    @include('admin.users.script')
@endsection
