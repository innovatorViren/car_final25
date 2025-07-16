{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')

@component('partials._subheader.subheader-v6',$module_action)
@endcomponent
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-body">
				<div class="example example-basic">
					<div class="example-preview">
						<div class="timeline timeline-justified timeline-4">
							<div class="timeline-bar"></div>
							<div class="timeline-items">
								@forelse ($history as $hist)
								<div class="timeline-item">
										<div class="timeline-badge">
											<div class="bg-danger"></div>
										</div>
										<div class="timeline-label">
											<span class="text-primary font-weight-bold">{{ $hist->created_at->format('d-m-Y H:i:A') }}</span>
										</div>
										<div class="timeline-content">

											<li><b class="text-capitalize"> {{ $hist->userResponsible()->last_name }} {{ $hist->userResponsible()->first_name }}</b> changed <b>{{  str_replace("_",' ',$hist->fieldName()) }}
												</b>
												@if($hist->fieldName() == 'permissions')
													@php
														$old = str_replace('.',' - ',$hist->oldValue());
														$new = str_replace('.',' - ',$hist->newValue());
														$to = array_diff_assoc(json_decode($new,true),json_decode($old,true));
														$to = str_replace('}','',json_encode($to));
														$to = str_replace('_',' ',$to);
														$to = str_replace(',','</br>',$to);
														$to = str_replace('{','',$to);
														$to = str_replace('true','<b>Apply</b>',$to);
														$to = str_replace('false','<b>Remove</b>',$to);
														$to = str_replace(':',' - ',$to);
														$to = str_replace('"','',$to);
														$to = str_replace('"','',$to);
													@endphp
													<p>&nbsp;</p>
													<p class="text-capitalize">{!! $to !!}</p>
												@else
													 from {{ $hist->oldValue() }} to {{ $hist->newValue() }}
												@endif
											</li>

										</div>
									</div>
									@empty
									<div class="timeline-item">
										<div class="timeline-badge">
											<div class="bg-danger"></div>
										</div>
										<div class="timeline-label">
											<span class="text-primary font-weight-bold"></span>
										</div>
										<div class="timeline-content">
											<h1> History Not Found </h1>
										</div>
									</div>
									@endforelse
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
@endsection