{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('content')
@section('title', __('product_rate.product_rate'))

@component('partials._subheader.subheader-v6', [
    'page_title' => __('product_rate.product_rate'),
    'back_action' => url('product-rate'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card card-custom gutter-b">
                    <div class="v-application v-application--is-ltr theme--light" id="app">
                        <div class="card ">
                            <div class="card-header p-3">
                                <div class="row align-items-center">
                                    <div class="col-lg-4 pl-10">
                                        <h3><span> {{ $Category->name ?? '' }} |
                                                {{ $productRateFirst->subCategory->name ?? '' }}</span>
                                        </h3>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="card-toolbar ml-40 pl-40 text-right">
                                            <span class="svg-icon pt-4 pr-4" style="float:right;">
                                                @if ($current_user->hasAnyAccess(['product_rate.edit', 'users.superadmin']))
                                                    <a href="{{ route('product-rate.edit', $productRateFirst->id) }}"
                                                        class="btn btn-light-primary btn-sm font-weight-bold">
                                                        <i class="fas fa-pencil-alt fa-1x"></i> Edit
                                                    </a>
                                                @endif
                                                @if ($current_user->hasAnyAccess(['product_rate.delete', 'users.superadmin']))
                                                    <a href="{{ route('product-rate.destroy', $productRateFirst->id) }}"
                                                        data-redirect="{{ route('product-rate.index') }}"
                                                        class="btn btn-light-danger btn-sm font-weight-bold delete-confrim">
                                                        <i class="fas fa-trash-alt fa-1x"></i>
                                                        Delete
                                                    </a>
                                                @endif
                                                @if ($current_user->hasAnyAccess(['users.info', 'users.superadmin']))
                                                    <a href=""
                                                        class="btn btn-light-success btn-sm font-weight-bold show-info"
                                                        data-toggle="modal" data-target="#AddModelInfo"
                                                        data-table="{{ $table_name }}"
                                                        data-id="{{ $productRateFirst->id }}"
                                                        data-url="{{ route('get-info') }}">
                                                        <span class="navi-icon">
                                                            <i class="fas fa-info-circle fa-1x"></i>
                                                        </span>
                                                        <span class="navi-text">Info</span>
                                                    </a>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                @if ($productRate)
                                    {{-- @dd($productRate); --}}
                                    @foreach ($productRate as $rate)
                                        @php
                                            $data = json_decode($rate->product_rate, true);
                                        @endphp
                                        {{-- @dd($subCategory); --}}
                                        @foreach ($subCategory as $key => $sub)
                                            {{-- <div class="card"> --}}
                                            {{-- <div class="pl-12 pt-3 pr-12"> --}}
                                            <table class="table table-separate table-head-custom table-checkable">
                                                <thead>
                                                    <tr>
                                                        <td>Product</td>
                                                        @foreach ($location as $key => $l)
                                                            <td class="text-right">{{ $l->name }}</td>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($sub->product as $key => $p)
                                                        {{-- @php
                                                            dd();
                                                        @endphp --}}
                                                        <tr>
                                                            <td>{{ $p->name }}</td>
                                                            @foreach ($location as $key => $loc)
                                                                <td class="text-right">
                                                                    {{ format_amount($data[$p->id][$loc->id] ?? [], 2) }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                            {{-- </div> --}}
                                            {{-- </div> --}}
                                        @endforeach
                                    @endforeach

                                @endif

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('info')
@endsection
