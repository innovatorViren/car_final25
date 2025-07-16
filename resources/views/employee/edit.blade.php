@extends($theme)
@section('content')
@section('title', $title)

@component('partials._subheader.subheader-v6', [
    'page_title' => __('employee.edit_employee'),
    'back_action' => route('employee.index'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        @if (isset($parentId))
            {!! Form::model($employee, [
                'route' => ['employee.store'],
                'id' => 'employeeForm',
                'enctype' => 'multipart/form-data',
            ]) !!}
            {!! Form::hidden('parentId', $parentId, ['id' => 'parentId']) !!}
        @else
            {!! Form::model($employee, [
                'route' => ['employee.update', $employee->id],
                'id' => 'employeeForm',
                'enctype' => 'multipart/form-data',
            ]) !!}
            @method('PUT')
            {!! Form::hidden('id', $employee->id, ['id' => 'id']) !!}
            {!! Form::hidden('parent_employee_id', $employee->parent_employee_id, ['id' => 'parent_employee_id']) !!}
        @endif
        @include('employee.form', [
            'employee' => $employee,
        ])
        {!! Form::close() !!}
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
    function favicon(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#emp_photo_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#emp_photo").change(function() {
        favicon(this);
    });
</script>
@endsection
