@if(Session::has('error'))
    @push('scripts')
        <script type="text/javascript">
            customAlertMsg('error','Error',"{!! Session::get('error') !!}");
        </script>
    @endpush
    @php session()->forget('error') @endphp
@endif

@if(Session::has('success'))
    @push('scripts')
        <script type="text/javascript">
            customAlertMsg('success','Success',"{!! Session::get('success') !!}");
        </script>
    @endpush
    @php session()->forget('success') @endphp
@endif

@if(Session::has('warning'))
    @push('scripts')
        <script type="text/javascript">
            customAlertMsg('warning','Warning',"{!! Session::get('warning') !!}");
        </script>
    @endpush
    @php session()->forget('warning') @endphp
@endif