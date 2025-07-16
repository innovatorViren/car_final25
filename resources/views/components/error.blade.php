@if(count($errors) > 0)
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
           <span aria-hidden="true">&times;</span>
        </button>
    
        @foreach ($errors->all() as $error)
        <p class="mb-1">{{ $error }}</p>
        @endforeach

    </div>
@endif