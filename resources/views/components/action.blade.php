{{-- @if ($list_item)
    @php
        $user = request()->user()->hasRole();
        $filtered = collect([]) ;

        if(!$user) {
            $filtered = collect(collect($list_item)->toArray())->filter(function ($value, $key) {
                return $value['permission'] == true;
            });
        }
    @endphp
@endif --}}

<div class="text-center">
    <div class="dropdown dropdown-inline" title="" data-placement="left" data-original-title="Quick actions">
        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ki ki-bold-more-hor"></i>
        </a>
        @if(isset($list_item))

        <div class="dropdown-menu m-0 dropdown-menu-left">
            <ul class="navi navi-hover">
                @foreach ($list_item as $item)
                @if ($item->get('permission') != false)
                <li class="navi-item">
                    <a class="navi-link {{ $item->get('target',null) ? 'call-modal' : '' }} {{ $item->get('class',null) }}" @if($item->get('target' ,null))
                        data-target-modal="{{ $item->get('target') }}"
                        @endif
                        data-id={{ $item->get('id',null) }}
                        data-url="{{ $item->get('action' , 'javaqscrip:void(0)') }}"
                        data-table="{{ $item->get('table',null)}}"
                        target="{{ $item->get('targetblank',null)}}"
                        data-footer-hide="{{ $item->get('footer-hide', '')}}"
                        href="{{ $item->get('action' , 'javaqscrip:void(0)') }}">
                        <span class="navi-icon">
                            <i class="{{ $item->get('icon') }}"></i>
                        </span>
                        <span class="navi-text">{{ $item->get('text') }}</span>
                    </a>
                </li>
                @endif
                @endforeach

            </ul>
        </div>
        @endif
    </div>
</div>
