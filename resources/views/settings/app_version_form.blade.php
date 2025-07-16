{!! Form::open( ['route' => 'settings.store' ,'id' => 'settingForm']) !!}
        
{{Form::hidden('group','app_version',['class' => 'form-control' ,'required']);}}

<!--begin::Accordion-->

    <div class="row">
        <div class="form-group col-lg-4">
            {{Form::label('android_version', 'Android Version')}}<i class="text-danger">*</i>
            <div class="input-group">
                {{Form::text('android_version', $settings['android_version'] ?? '',['class' => 'form-control','required'])}}
            </div>
        </div>

        <div class="form-group col-lg-4">
            {{Form::label('ios_version', 'IOS Version')}}<i class="text-danger">*</i>
            <div class="input-group">
                {{Form::text('ios_version', $settings['ios_version'] ?? '',['class' => 'form-control','required'])}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-right">
            <a href="" class="mr-2">{{__('common.cancel')}}</a>
            <button type="submit" class="btn btn-primary">{{__('common.save')}}</button>
        </div>
    </div>


{!! Form::close() !!}