@if (isset($add_modal))
<a href="{{ $add_modal->get('action' , 'javaqscrip:void(0)') }}" data-toggle="modal" data-target-modal="{{ $add_modal->get('target') }}" data-url="{{ $add_modal->get('action' , 'javaqscrip:void(0)') }}" class="btn call-modal btn-primary btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2">
     {{ $add_modal->get('text' , 'javaqscrip:void(0)') }}
</a>
@endif