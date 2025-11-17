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
@php
    $status_array = $orderItem->pluck('status')->toArray();
    $arrCount = array_count_values($status_array);
    $pendingCount = $arrCount['pending'] ?? 0;
    

    $final_status = $order->status ?? '';
    $order_items = $orderItem;
    $hasCompleted = in_array('completed',$order_items->pluck('status')->toArray());
    $hasInProgress = in_array('in_progress',$order_items->pluck('status')->toArray());
@endphp
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
                                                            
                                                            @php
                                                                $order_status = $final_status ?? '';
                                                                $item_status_Bg_order = '';
                                                                if (!empty($order_status)) {
                                                                    if ($order_status == 'Pending') {
                                                                        $item_status_Bg_order =
                                                                            'ribbon-target text-warning btn-light-warning';
                                                                    } elseif ($order_status == 'Partial') {
                                                                        $item_status_Bg_order = 'text-blue';
                                                                    } elseif ($order_status == 'Completed') {
                                                                        $item_status_Bg_order =
                                                                            'ribbon-target text-success btn-light-success';
                                                                    } elseif ($order_status == 'Cancelled') {
                                                                        $item_status_Bg_order =
                                                                            'ribbon-target text-danger btn-light-danger';
                                                                    }
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td width="30%" class="pr-0">
                                                                    <div class="text-right"><b>{{ __('Status') }}</b>
                                                                        &nbsp;
                                                                    </div>
                                                                </td>
                                                                <td width="25%">
                                                                    <div>
                                                                        :&nbsp;
                                                                        @if ($current_user->hasAnyAccess(['orders.change_status', 'users.superadmin']))
                                                                            @if ($final_status == 'Pending' || $final_status == 'Partial')
                                                                                <a type="button"
                                                                                    class="btn btn-light-success btn-sm font-weight-bold ribbon-target {{ $item_status_Bg_order }} call-modal"
                                                                                    data-toggle="modal"
                                                                                    data-target-modal="#commonModadStatus">
                                                                                    {{ $final_status ?? '' }}
                                                                                </a>
                                                                            @else
                                                                                <a type="button"
                                                                                    class="btn btn-light-success btn-sm font-weight-bold ribbon-target {{ $item_status_Bg_order }}">
                                                                                    {{ $final_status ?? '' }}
                                                                                </a>
                                                                            @endif
                                                                        @else
                                                                            @if ($final_status == 'Pending')
                                                                                <a type="button"
                                                                                    class="btn btn-light-success btn-sm font-weight-bold ribbon-target {{ $item_status_Bg_order }} call-modal"
                                                                                    data-toggle="modal"
                                                                                    data-target-modal="#commonModadStatus">
                                                                                    {{ $final_status ?? '' }}
                                                                                </a>
                                                                            @else
                                                                                <a type="button"
                                                                                    class="btn btn-light-success btn-sm font-weight-bold ribbon-target {{ $item_status_Bg_order }}">
                                                                                    {{ $final_status ?? '' }}
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <br><br>
                                                <div style="width: 100%; overflow-x: auto; white-space: nowrap;">
                                                    <table style="border-collapse: collapse; min-width: 1200px; width: max-content; text-align: center;">
                                                        <tr style="border-bottom: 1px solid ;border-top: 1px solid; padding: 8px;">
                                                            <th class="p-2 w-20px">{{ __('common.no') }}</th>
                                                            <th class="p-2 w-100px">{{ __('Date') }}</th>
                                                            <th class="p-2 w-200px">Employee</th>
                                                            <th class="p-2 w-100px">{{ __('Start Time') }}</th>
                                                            <th class="p-2 w-100px">{{ __('End Time') }}</th>
                                                            <th class="p-2 w-100px">{{ __('Start Wash Time') }}</th>
                                                            <th class="p-2 w-100px">{{ __('End Wash Time') }}</th>
                                                            <th class="p-2 w-100px">{{ __('Total Wash Time') }}</th>
                                                            <th class="p-2 w-100px">{{ __('Before Wash Photo') }}</th>
                                                            <th class="p-2 w-100px">{{ __('After Wash Photo') }}</th>
                                                            <th class="p-2 w-100px">Status</th>
                                                        </tr>
                                                        
                                                        @foreach ($orderItem as $key => $item)

                                                            <tr style="border-bottom: 1px dotted; padding: 8px;">
                                                                <td class="p-2">{{ $key + 1 }}</td>
                                                                <td class="p-2">
                                                                    {{ $item->scheduled_date ?? '' }}
                                                                </td>
                                                                <td class=" p-2">
                                                                    {{ $item->emp_first_name ?? '' }}
                                                                    -
                                                                    {{ $item->emp_last_name ?? '' }}
                                                                </td>
                                                                <td class="p-2">
                                                                    {{ $item->start_time ?? '' }}
                                                                </td>
                                                                <td class=" p-2">
                                                                    {{ $item->end_time ?? '' }}
                                                                </td>
                                                                <td class=" p-2">
                                                                    {{ $item->start_wash_time ?? '-' }}
                                                                </td>
                                                                <td class=" p-2">
                                                                    {{ $item->end_wash_time ?? '-' }}
                                                                </td>
                                                                <td class=" p-2">
                                                                    @if(!empty($item->start_wash_time) && !empty($item->end_wash_time))
                                                                        @php
                                                                            $start = \Carbon\Carbon::parse($item->start_wash_time);
                                                                            $end = \Carbon\Carbon::parse($item->end_wash_time);
                                                                            $diffInMinutes = $end->diffInMinutes($start);
                                                                            $hours = intdiv($diffInMinutes, 60);
                                                                            $minutes = $diffInMinutes % 60;
                                                                        @endphp

                                                                        {{ $hours > 0 ? $hours . ' hr ' : '' }}{{ $minutes }} min
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td class=" p-2">
                                                                     @if(!empty($item->before_wash_photo))
                                                                        <a href="{{ asset($item->before_wash_photo) }}" target="_blank">
                                                                            <img src="{{ asset($item->before_wash_photo) }}"
                                                                                 alt="Before Wash"
                                                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                                                        </a>
                                                                    @else
                                                                        <span class="text-muted">No Photo</span>
                                                                    @endif
                                                                </td>

                                                                <td class=" p-2">
                                                                     @if(!empty($item->after_wash_photo))
                                                                        <a href="{{ asset($item->after_wash_photo) }}" target="_blank">
                                                                            <img src="{{ asset($item->after_wash_photo) }}"
                                                                                 alt="After Wash"
                                                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                                                        </a>
                                                                    @else
                                                                        <span class="text-muted">No Photo</span>
                                                                    @endif
                                                                </td>
                                                                <td class=" p-2">
                                                                    {{ $item->status ? Str::of($item->status)->replace('_', ' ')->title() : '' }}
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
    </div>
    <div class="modal fade" id="commonModadStatus" tabindex="-1" role="dialog"
    aria-labelledby="commonModadStatusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commonModadStatusLabel">Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{ route('orderStatus', [$order->id]) }}" id="proposalForm">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            {!! Form::label('status', trans('orders.status')) !!}<i class="text-danger">*</i>
                            {!! Form::select(
                                'status',
                                ['Cancelled' => 'Cancelled'],
                                null,
                                [
                                    'class' => 'form-control jsStatus required',
                                    'data-placeholder' => 'Select Status',
                                ],
                            ) !!}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button type="submit" id="off" class="btn btn-primary" data-toggle="modal"
                            data-target="#offer">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div id="load-modal"></div>
    @include('info')
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.jsStatus').select2();
    });
</script>
@endpush