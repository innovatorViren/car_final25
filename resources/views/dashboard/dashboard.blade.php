{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('title',$title ?? '')
@section('content')

@php
$defaultYear = getDefaultYear();
$startOfMonth = now()->startOfMonth()->format('d-m-Y') ?? custom_date_format($defaultYear->from_date, 'd-m-Y');
$endOfMonth = now()->endOfMonth()->format('d-m-Y') ?? custom_date_format($defaultYear->to_date, 'd-m-Y');    
$date = $startOfMonth . ' | ' . $endOfMonth;
$yearFormDate = custom_date_format($defaultYear->from_date, 'd-m-Y');
$yearToDate = custom_date_format($defaultYear->to_date, 'd-m-Y');
$yearDate = $yearFormDate . ' | ' . $yearToDate;
$default_date = request()->get('date', $date);

@endphp
<div class="d-flex flex-column-fluid">
    @if($current_user->hasAnyAccess(['users.superadmin']))
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-sm-6 col-md-4 col-xl-3">
                <a href="" class="font-weight-bold text-dark-50 font-size-lg">  
                    <div class="card card-custom card-stretch gutter-b" style="background-color:#F1D6E2">
                        <div class="card-body">
                            <span class="card-title font-weight-bolder text-dark-75 font-size-h4 d-block m-0"></span>
                            {{ __('dashboard.lead') }}
                        </div>
                    </div>
                </a>   
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <a  href="" class="font-weight-bold text-dark-50 font-size-lg">   
                    <div class="card card-custom card-stretch gutter-b" style="background-color:#dbe6f0">
                        <div class="card-body pr-1">
                            <span class="card-title font-weight-bolder text-dark-75 font-size-h4 d-block m-0">
                                
                            </span>
                            {{ __('dashboard.total_customer') }}
                        </div>
                    </div>
                </a>   
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <a href="" class="font-weight-bold text-dark-50 font-size-lg">  
                    <div class="card card-custom card-stretch gutter-b" style="background-color:#D3F1E5">
                        <div class="card-body">
                            <span class="card-title font-weight-bolder text-dark-75 font-size-h4 d-block m-0"></span>
                            {{ __('common.raw_material') }}
                        </div>
                    </div>
                </a>   
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <a href="" class="font-weight-bold text-dark-50 font-size-lg">  
                    <div class="card card-custom card-stretch gutter-b" style="background-color:#f3FEEB">
                        <div class="card-body pr-1">
                            <span class="card-title font-weight-bolder text-dark-75 font-size-h4 d-block m-0"></span>
                            {{ __('common.product') }}
                        </div>
                    </div>
                </a>   
            </div> --}}
            
        </div>

    </div>
    @endif
</div>
@endsection

@section('scripts')

<script>
</script>
@endsection