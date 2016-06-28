@extends('projectsquare::default')

@section('content')
    <!-- <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">{{ trans('projectsquare::my.panel_title') }}</li>
    </ol>-->
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::my.panel_title') }}</h1>
            </div>

            @if (isset($error))
                <div class="info bg-danger">
                    {{ $error }}
                </div>
            @endif

            @if (isset($confirmation))
                <div class="info bg-success">
                    {{ $confirmation }}
                </div>
            @endif

            <div class="my-profile-template">
                <form action="{{ route('my_profile_update') }}" method="post">
                    <div class="form-group">
                        <label for="first_name">{{ trans('projectsquare::users.first_name') }}</label>
                        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.first_name') }}" name="first_name" @if (isset($user->first_name))value="{{ $user->first_name }}"@endif />
                    </div>

                    <div class="form-group">
                        <label for="last_name">{{ trans('projectsquare::users.last_name') }}</label>
                        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.last_name') }}" name="last_name" @if (isset($user->last_name))value="{{ $user->last_name }}"@endif />
                    </div>

                    <div class="form-group">
                        <label for="email">{{ trans('projectsquare::users.email') }}</label>
                        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.email') }}" name="email" @if (isset($user->email))value="{{ $user->email }}"@endif autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <label for="password">{{ trans('projectsquare::users.password') }}</label><br/>
                        <input class="form-control" type="password" placeholder="@if (isset($user->id)){{ trans('projectsquare::users.password_leave_empty') }}@else{{ trans('projectsquare::users.password') }}@endif" name="password" autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn valid">
                            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-default back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
                    </div>

                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label for="avatar">{{ trans('projectsquare::my.avatar') }}</label><br/>
                        @include('projectsquare::includes.avatar', [
                            'id' => $logged_in_user->id,
                            'name' => $logged_in_user->complete_name
                        ])
                    </div>
                </form>

                <form id="fileupload" action="{{ route('my_profile_upload_avatar') }}" method="POST" enctype="multipart/form-data">
                    <span class="btn valid fileinput-button back">
                        <i class="glyphicon glyphicon-picture"></i>
                        <span>Parcourir</span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input id="fileupload" type="file" name="files[]" data-url="{{ route('my_profile_upload_avatar') }}">
                    </span>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/vendor/jquery.fileupload/vendor/jquery.ui.widget.js') }}"></script>
    <script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.fileupload-process.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.fileupload-ui.js') }}"></script>
    <script>
        $(function () {
            $('#fileupload').fileupload({
                dataType: 'json',
                formData: {
                    _token: $('#csrf_token').val(),
                },
                add: function (e, data) {
                    data.submit();
                },
                done: function (e, data) {
                    window.location.reload();
                }
            });
        });
    </script>

@endsection