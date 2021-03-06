@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates messages-template">
            <div class="page-header">
                <h1>{{ __('projectsquare::messages.messages') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => __('projectsquare::tooltips.messages')
                    ])
                </h1>
            </div>

            <form method="get">
                <div class="row">

                    <h2>{{ __('projectsquare::messages.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="">{{ __('projectsquare::tickets.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            <hr/>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('projectsquare::messages.title') }}</th>
                        <th>{{ __('projectsquare::messages.date') }}</th>
                        <th>{{ __('projectsquare::messages.client') }}</th>
                        <th>{{ __('projectsquare::messages.author') }}</th>
                        <th>{{ __('projectsquare::messages.action') }}</th>
                    </tr>
                    </thead>
                    @foreach ($conversations as $conversation)
                        <tr>
                            <td style="border-left: 10px solid {{ $conversation->project->color }}"></td>
                            <td>
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    <span class="text">{{ $conversation->title }}</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    {{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    <span class="text">@if (isset($conversation->project) && isset($conversation->project->client)){{ $conversation->project->client->name }}@endif</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    @include('projectsquare::includes.avatar', [
                                        'id' => $conversation->messages[sizeof($conversation->messages) - 1]->user->id,
                                        'name' => $conversation->messages[sizeof($conversation->messages) - 1]->user->complete_name
                                    ])
                                </a>
                            </td>
                            <td width="10%" class="action" align="right">
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    <i class="btn btn-primary see-more"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="text-center">
                {!! $conversations->appends([
                    'filter_project' => $filters['project'],
                ])->links() !!}
            </div>
        </div>
    </div>
    <script src="{{ asset('js/messages.js') }}"></script>
@endsection