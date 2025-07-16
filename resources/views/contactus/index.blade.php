@include('partials._loginheader.login-header')
<style>
    .listing-header{
        font-weight: 300 !important;
        font-size: 32px !important;
        color: #FF2A00;
    }
</style>
<body style="background-image: url('{{ asset('media/bg/bg-3.jpg') }}');">
   <div class="container">
    <div class="row">
      <div class="col-sm-12" style="height: 100px;">
        <img src="{{ asset('media/mahalaxmi-logo.png') }}" width="250" height="100"/>
      </div>
    </div>
      <div class="row">
         <div class="col-md-6">
          <div style="height: 15px;"></div>         
            <h1 class="h2 m-0 text-capitalize text-danger font-weight-bold">Contact Us</h1>
           <div class="inews-item pt-4" style="margin-bottom: 0px;font-size: 16px !important;">
                <p>Thank you for reaching out to MAHALAXMI FOOD AND SPICES PVT.LTD.! Please fill the form right. Our team will contact you shortly.</p>
                <p><b>Address:</b></p>
                <p><address>MAHALAXMI FOOD AND SPICES PVT.LTD.,<br/>
                Gondal Road, N.H 8-B , Behind Galaxy Petrol Pump, Bhojapara,<br/>
                            Rajkot 360-311, Gujarat, India.<br/>
                            </address></p>
                <p><b>Email:</b></p>
                <p>admin@mahalaxmifoodandspices.com</p>
                <p><b>Contact Detail</b></p>
                <!-- <p>Name: Bhavin Domadiya </p> -->
                <p>Mo.: +91 70484 81070</p>
                </div></div>
                <div class="col-md-6">
                @if ($message = Session::get('error'))
                        <div role="alert" class="alert alert-danger">
                            <div class="alert-text">{{ Session::get('error') }}</div>
                        </div>
                    @endif

                    @if ($message = Session::get('success'))
                        <div role="alert" class="alert alert-success">
                            <div class="alert-text">{{ Session::get('success') }}</div>
                        </div>
                    @endif
                <div style="height: 15px;"></div>         
                <div class="d-flex flex-column-fluid">                    
                    {!! Form::open([
                        'route' => 'contact-mail',
                        'role' => 'form',
                        'id' => 'contact_form',
                        'class' => 'form',
                    ]) !!}

                    <div class="form-group text-left">
                        {!! Form::label('name','Name',['class'=>''])!!}<i class="text-danger">*</i>
                        {!! Form::text('name',null,['class'=>'form-control h-auto form-control-solid py-4 px-8','required'])!!}
                        {!! $errors->has('name') ? $errors->first('name', '<label class="text-danger">:message</label>') : '' !!}
                        <div class="fv-plugins-message-container"></div>
                    </div>
                    <div class="form-group text-left">
                        {!! Form::label('email', 'Email', ['class' => '']) !!}<i class="text-danger">*</i>
                        {!! Form::email('email', null, [
                            'class' => 'form-control h-auto form-control-solid py-4 px-8 required email',
                            'autocomplete' => 'off',
                            'required',
                        ]) !!}
                        {!! $errors->has('email') ? $errors->first('email', '<label class="text-danger">:message</label>') : '' !!}
                        <div class="fv-plugins-message-container"></div>
                    </div>
                    <div class="form-group text-left">
                        {!! Form::label('contact','Contact No',['class'=>''])!!}<i class="text-danger">*</i>
                        {!! Form::text('contact',null,['class'=>'form-control number h-auto form-control-solid py-4 px-8','required'])!!}
                        {!! $errors->has('contact') ? $errors->first('contact', '<label class="text-danger">:message</label>') : '' !!}
                        <div class="fv-plugins-message-container"></div>
                    </div>

                    <div class="form-group text-left">
                        {!! Form::label('message','Message',['class'=>''])!!}
                        {!! Form::textarea('message',null,['class'=>'form-control h-auto form-control-solid py-4 px-8','rows'=> 5])!!}                        
                        
                    </div>
                    <div class="text-center pt-2">
					    {!! Form::submit("Send", ['name' => 'btnsave','class' => 'btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3']) !!}
                    </div>
                    {{-- <button id="kt_login_signin_submit"
                        class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Sign In</button> --}}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>