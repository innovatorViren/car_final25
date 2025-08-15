<table class="table table-separate table-head-custom table-checkable">
    <thead>
        <tr>
            <td>Product</td>
            @foreach ($location as $key => $l)
                <td class="text-center">{{ $l->name }}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>

        @foreach ($product as $key1 => $p)
        {{-- @dd($p->name); --}}
            <tr>
                <td>{{ $p->name }}</td>
                @foreach ($location as $key => $loc)
                    <td>
                        {{-- <input type="text" class="form-control" name="tradebalance[$balancekey . '][amount_owed]" id="" required/> --}}
                        {!! Form::number('productRate[' . $p->id . ']['.$loc->id.']', null, ['class' => 'form-control']) !!}
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tr>
    </tbody>
</table>
