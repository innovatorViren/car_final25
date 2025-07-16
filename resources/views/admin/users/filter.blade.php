<div id="userFilter" class="modal fixed-left fade pr-0" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('common.filter') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('userTypeFilter', trans('users.form.user_type')) !!}
                    {!! Form::select('userTypeFilter', ['' => 'Select'] + $user_type, null, [
                        'class' => 'form-control userTypeFilter jsUserTypeFilter',
                        'id' => 'userTypeFilter_id',
                        'data-placeholder' => 'User Type',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('rolefilter', trans('users.form.roles')) !!}
                    {!! Form::select('rolefilter', ['' => 'Select'] + $roles, null, [
                        'class' => 'form-control jsRolefilter',
                        'id' => 'role_id',
                        'data-placeholder' => 'Role',
                    ]) !!}
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-success mr-2 btn_search jsBtnSearch">{{ __('common.search') }}</button>
                <button type="button" class="btn btn-danger btn_reset">{{ __('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $('.jsRolefilter,.jsUserTypeFilter').select2({
            allowClear: true
        });
    </script>
@endpush
