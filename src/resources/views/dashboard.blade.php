@extends('gateway::app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
                    <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>

                    <ul>
                        @foreach ($user->projects as $project)
                            <li>
                                {{ $project->client->name }} - {{ $project->name }}
                            </li>
                        @endforeach
                    </ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection