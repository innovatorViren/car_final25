{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('title', $title)

@section('content')
@component('partials._subheader.subheader-v6', [
    'page_title' => $title,
    'back_action' => route('orders.index'),
    'text' => __('common.back'),
    'permission' => true,
]),
@endcomponent
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-body pt-1">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="kt_tab_pane_4_1" role="tabpanel" aria-labelledby="kt_tab_pane_4_1">
                                    <div class="card-body pt-5">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="row">
                                                    <div class="col-xl-4">
                                                    </div>
                                                    <div class="col-xl-3">
                                                    </div>
                                                    <div class="col-xl-5  pl-5">
                                                        <div class="text-center">
                                                            <h3 class="pl-30">{{ $order->code ?? '' }}</h3>
                                                        </div>
                                                        <table style="width:100%">
                                                            <tr>
                                                                <td width="30%" class="pr-0">
                                                                    <div class="text-right"><b>{{ __('Order Date') }}</b>
                                                                        &nbsp;
                                                                    </div>
                                                                </td>
                                                                <td width="25%">
                                                                    <div>
                                                                        :&nbsp;
                                                                        {{ $order->date ?? '' }}
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td width="30%" class="pr-0">
                                                                    <div class="text-right"><b>{{ __('Customer Name') }}</b>
                                                                        &nbsp;
                                                                    </div>
                                                                </td>
                                                                <td width="25%">
                                                                    <div>
                                                                        :&nbsp;
                                                                       {{ $order->customer_first_name ?? '' }}
                                                                       {{ $order->customer_last_name ?? '' }}
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td width="30%" class="pr-0">
                                                                    <div class="text-right"><b>{{ __('Total Wash') }}</b>
                                                                        &nbsp;
                                                                    </div>
                                                                </td>
                                                                <td width="25%">
                                                                    <div>
                                                                        :&nbsp;
                                                                       {{ $order->total_washes ?? '' }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="30%" class="pr-0">
                                                                    <div class="text-right"><b>{{ __('Pay Ampount') }}</b>
                                                                        &nbsp;
                                                                    </div>
                                                                </td>
                                                                <td width="25%">
                                                                    <div>
                                                                        :&nbsp;
                                                                       {{ $order->pay_amount ?? '' }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td width="30%" class="pr-0">
                                                                    <div class="text-right"><b>{{ __('Status') }}</b>
                                                                        &nbsp;
                                                                    </div>
                                                                </td>
                                                                <td width="25%">
                                                                    <div>
                                                                        :&nbsp;
                                                                       {{ $order->status ?? '' }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <br><br>
                                                <table style="border-collapse: collapse; width: 100%;">
                                                    <tr style="border-bottom: 1px solid ;border-top: 1px solid; padding: 8px;">
                                                        <th class="p-2 w-20px">{{ __('common.no') }}</th>
                                                        <th class="p-2 w-100px">{{ __('Date') }}</th>
                                                        <th class="p-2 w-100px">{{ __('Start Time') }}</th>
                                                        <th class="p-2 w-100px">{{ __('End Time') }}</th>
                                                        <th class="p-2 w-200px">Employee</th>
                                                        <th class="p-2 w-100px">Status</th>
                                                    </tr>
                                                    
                                                    @foreach ($orderItem as $key => $item)

                                                        <tr style="border-bottom: 1px dotted; padding: 8px;">
                                                            <td class="p-2">{{ $key + 1 }}</td>
                                                            <td class="p-2">
                                                                {{ $item->scheduled_date ?? '' }}
                                                            </td>
                                                            <td class="p-2">
                                                                {{ $item->start_time ?? '' }}
                                                            </td>
                                                            <td class=" p-2">
                                                                {{ $item->end_time ?? '' }}
                                                            </td>
                                                            <td class=" p-2">
                                                                {{ $item->emp_first_name ?? '' }}
                                                                -
                                                                {{ $item->emp_first_name ?? '' }}
                                                            </td>
                                                            <td class=" p-2">
                                                                {{ $item->status ?? '' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach  
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="load-modal"></div>
    @include('info')
@endsection