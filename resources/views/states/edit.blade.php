{!! Form::model($state, ['route' => ['state.update', $state->id], 'id' => 'stateForm']) !!}
    @method('PUT')
    {!! Form::hidden('id', $state->id, ['id' => 'id']) !!}
    @include('states.form',[
        'state' => $state
    ])
{!! Form::close() !!}
