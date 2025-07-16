@if (Session::has('onzup.flash.message'))
	<div class="alert alert-{{ Session::get('onzup.flash.level') }}">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

		{{ Session::get('onzup.flash.message') }}
	</div>
@endif
