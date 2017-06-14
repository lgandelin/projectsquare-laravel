@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates messages-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::messages.messages') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.messages')
                    ])
                </h1>
            </div>

            <form method="get">
                <div class="row">

                    <h2>{{ trans('projectsquare::messages.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="">{{ trans('projectsquare::tickets.filters.by_project') }}</option>
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
                        <th>{{ trans('projectsquare::messages.title') }}</th>
                        <th>{{ trans('projectsquare::messages.date') }}</th>
                        <th>{{ trans('projectsquare::messages.client') }}</th>
                        <th>{{ trans('projectsquare::messages.author') }}</th>
                        <th>{{ trans('projectsquare::messages.action') }}</th>
                    </tr>
                    </thead>
                    @foreach ($conversations as $conversation)
                        <tr>
                            <td style="border-left: 10px solid {{ $conversation->project->color }}"></td>
                            <td class="entity_title"><a href="{{ route('conversations_view', ['id' => $conversation->id]) }}"><span class="text">{{ $conversation->title }}</span></a></td>
                            <td>{{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}</td>
                            <td><span class="text">@if (isset($conversation->project) && isset($conversation->project->client)){{ $conversation->project->client->name }}@endif</span></td>
                            <td>
                                @include('projectsquare::includes.avatar', [
                                    'id' => $conversation->messages[sizeof($conversation->messages) - 1]->user->id,
                                    'name' => $conversation->messages[sizeof($conversation->messages) - 1]->user->complete_name
                                ])
                            </td>
                            <td align="right">
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}" class="btn btn-primary see-more"></a>
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