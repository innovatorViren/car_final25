
{!! Form::open(['route' => 'state.store','id' => 'stateForm']) !!}
@include('states.form',[
                    'states' => null
                ])
{!! Form::close() !!}