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
                                            <div class="col-xl-3">
                                                <h6 class="text-black">{{ __('shop_order.shop_address') }},</h6>
                                                <h6>{{ $shopOrder->shopdata->name ?? '' }}</h6>
                                                @if (!empty($shopOrder->shopdata->address))
                                                    {{ $shopOrder->shopdata->address ?? '' }}<br>
                                                    {{ $shopOrder->shopdata->city->name ?? '' }} -
                                                    {{ $shopOrder->shopdata->pincode ?? '' }}<br>
                                                    {{ $shopOrder->shopdata->state->name ?? '' }}<br>
                                                    {{ $shopOrder->shopdata->owner_name ?? ''}} - {{ $shopOrder->shopdata->phone_number ?? ''}}
                                                @endif
                                                
                                            </div>
                                            <div class="col-xl-3">
                                                <h6 class="text-black">{{ __('shop_order.customer_address') }},</h6>
                                                <h6>{{ $shopOrder->customerdata->company_name ?? '' }}</h6>
                                                @if (!empty($shopOrder->customerdata->customerAddress))
                                                    {{ $shopOrder->customerdata->customerAddress->address_line1 ?? '' }}<br>
                                                    {{ $shopOrder->customerdata->customerAddress->city->name ?? '' }} -
                                                    {{ $shopOrder->customerdata->customerAddress->pincode ?? '' }}<br>
                                                    {{ $shopOrder->customerdata->customerAddress->state->name ?? '' }}
                                                    -
                                                    {{ $shopOrder->customerdata->customerAddress->country->name ?? '' }}<br>
                                                    Phone : {{ $shopOrder->customerdata->mobile ?? '' }}<br>
                                                    Email : {{ $shopOrder->customerdata->email ?? '' }}
                                                @endif
                                            </div>
                                            <div class="col-xl-2"></div>
                                            <div class="col-xl-4  pl-5">
                                                <h3 class="pl-25 text-center">{{ $shopOrder->shop_order_no ?? '' }}</h3>
                                                <table style="width:100%">
                                                    <tr>
                                                        <td width="30%" class="pr-0">
                                                            <div class="text-right">{{ __('shop_order.order_date') }} &nbsp;</div>
                                                        </td>
                                                        <td width="25%">
                                                            <div>:&nbsp;
                                                                {{ custom_date_format($shopOrder->order_date, 'd-m-Y | h:i') }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%" class="pr-0">
                                                            <div class="text-right">{{ __('shop_order.route') }} &nbsp;</div>
                                                        </td>
                                                        <td width="25%">
                                                            <div>:&nbsp;
                                                                {{ $shopOrder->routes->name ?? '' }}
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
                                                <th class="p-2 w-300px">{{ __('shop_order.item_name') }}</th>
                                                <th class="p-2 w-100px text-right">{{ __('common.qty') }}</th>
                                                <th class="p-2 w-100px text-right">{{ __('shop_order.price') }}</th>
                                                <th class="p-2 w-100px text-right">{{ __('common.amount') }}</th>
                                            </tr>
                                            @php
                                                $total_qty = 0;
                                                $total_amount = 0;
                                            @endphp
                                            @foreach ($shopOrder->shopProduct as $key => $item)
                                                @php
                                                    $total_qty += $item->qty ?? 0;
                                                    $total_amount += $item->total_amount ?? 0;
                                                @endphp
                                                <tr style="border-bottom: 1px dotted; padding: 8px;">
                                                    <td class="p-2">{{ $key + 1 }}</td>
                                                    <td class="p-2">
                                                        {{ $item->product->product_name ?? '' }}
                                                        -
                                                        {{ $item->variant->name ?? '' }}
                                                    </td>
                                                    <td class="text-right p-2">
                                                        {{ format_amount($item->qty, 0) }}
                                                    </td>
                                                    <td class="text-right p-2">
                                                        {{ format_amount($item->rate, 2) }}
                                                    </td>
                                                    <td class="text-right p-2">
                                                        {{ format_amount($item->total_amount, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right p-2">
                                                    <strong>{{ format_amount($total_qty, 0) ?? '' }}</strong>
                                                </td>
                                                <td></td>
                                                <td class="text-right p-2">
                                                    <strong>{{ format_amount($total_amount, 2) }}</strong>
                                                </td>
                                            </tr>
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
    <div id="load-modal"></div>
    @include('info')
@endsection