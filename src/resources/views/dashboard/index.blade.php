@extends('gateway::app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('gateway::includes.top_bar')
        </div>
    </div>

	<div class="row">
        <div class="col-md-2">
            @include('gateway::includes.left_bar')
        </div>

        <div class="col-md-10">
            <h1>Tableau de bord</h1>
		</div>
	</div>
</div>
@endsection