@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::my.panel_title') }}</h1>
                <a href="{{ route('dashboard') }}" class=" back"></a>
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

            <div class="my-profile-template row">
                <div class="col-lg-8">
                    <h2>{{ __('projectsquare::my.personal_info') }}</h2>

                    <form action="{{ route('my_profile_update') }}" method="post">
                        <div class="form-group">
                            <label for="first_name">{{ __('projectsquare::users.first_name') }}</label>
                            <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.first_name') }}" name="first_name" @if (isset($user->first_name))value="{{ $user->first_name }}"@endif />
                        </div>

                        <div class="form-group">
                            <label for="last_name">{{ __('projectsquare::users.last_name') }}</label>
                            <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.last_name') }}" name="last_name" @if (isset($user->last_name))value="{{ $user->last_name }}"@endif />
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('projectsquare::users.email') }}</label>
                            <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.email') }}" name="email" @if (isset($user->email))value="{{ $user->email }}"@endif autocomplete="off" />
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('projectsquare::users.password') }}</label><br/>
                            <input class="form-control" type="password" placeholder="@if (isset($user->id)){{ __('projectsquare::users.password_leave_empty') }}@else{{ __('projectsquare::users.password') }}@endif" name="password" autocomplete="off" />
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('projectsquare::my.password_confirmation') }}</label><br/>
                            <input class="form-control" type="password" placeholder="@if (isset($user->id)){{ __('projectsquare::users.password_leave_empty') }}@else{{ __('projectsquare::my.password_confirmation') }}@endif" name="password_confirmation" autocomplete="off" />
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn valid">
                                <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
                            </button>
                           
                        </div>

                        {!! csrf_field() !!}
                    </form>
                </div>
                <div class="col-lg-offset-1 col-lg-3">
                    <div class="form-group">
                        <label for="avatar">{{ __('projectsquare::my.avatar') }}
                            @include('projectsquare::includes.tooltip', [
                                'text' => __('projectsquare::tooltips.avatar')
                            ])
                        </label><br/>
                        @include('projectsquare::includes.avatar', [
                            'id' => $logged_in_user->id,
                            'name' => $logged_in_user->complete_name
                        ])
                    </div>
                    <form id="fileupload" action="{{ route('my_profile_upload_avatar') }}" method="POST" enctype="multipart/form-data">
                        <span class="btn valid fileinput-button">
                            <i class="glyphicon glyphicon-picture"></i>
                            <span>{{ __('projectsquare::generic.browse') }}</span>
                            <!-- The file input field used as target for the file upload widget -->
                            <input id="fileupload" type="file" name="files[]" data-url="{{ route('my_profile_upload_avatar') }}">
                        </span>
                    </form>
                </div>

                <div class="col-lg-12" style="margin-top: 3rem;">
                    <h2>{{ __('projectsquare::my.email_notifications') }}</h2>

                    <form action="{{ route('my_profile_update_notifications') }}" method="post">
                        <div class="form-group col-lg-4">

                            <strong>{{ __('projectsquare::my.tasks') }}</strong><br/>

                            <label class="inline">
                                <input type="checkbox" name="email_notification_task_created" @if ($email_notification_task_created && $email_notification_task_created->value == "1")checked="checked"@endif /> {{ __('projectsquare::my.task_created') }}
                            </label>

                            <br/>

                            <label class="inline">
                                <input type="checkbox" name="email_notification_task_updated" @if ($email_notification_task_updated && $email_notification_task_updated->value == "1")checked="checked"@endif /> {{ __('projectsquare::my.task_updated') }}
                            </label>

                            <br/>

                            <label class="inline">
                                <input type="checkbox" name="email_notification_task_deleted" @if ($email_notification_task_deleted && $email_notification_task_deleted->value == "1")checked="checked"@endif /> {{ __('projectsquare::my.task_deleted') }}
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <strong>{{ __('projectsquare::my.tickets') }}</strong><br/>

                            <label class="inline">
                                <input type="checkbox" name="email_notification_ticket_created" @if ($email_notification_ticket_created && $email_notification_ticket_created->value == "1")checked="checked"@endif /> {{ __('projectsquare::my.ticket_created') }}
                            </label>

                            <br/>

                            <label class="inline">
                                <input type="checkbox" name="email_notification_ticket_updated" @if ($email_notification_ticket_updated && $email_notification_ticket_updated->value == "1")checked="checked"@endif /> {{ __('projectsquare::my.ticket_updated') }}
                            </label>

                            <br/>

                            <label class="inline">
                                <input type="checkbox" name="email_notification_ticket_deleted" @if ($email_notification_ticket_deleted && $email_notification_ticket_deleted->value == "1")checked="checked"@endif /> {{ __('projectsquare::my.ticket_deleted') }}
                            </label>

                            {!! csrf_field() !!}
                        </div>
                        <div class="form-group col-lg-4">
                            <strong>{{ __('projectsquare::my.messages') }}</strong><br/>

                            <label class="inline">
                                <input type="checkbox" name="email_notification_message_created" @if ($email_notification_message_created && $email_notification_message_created->value == "1")checked="checked"@endif /> {{ __('projectsquare::my.message_created') }}
                            </label>

                            {!! csrf_field() !!}
                        </div>

                        <div class="form-group" style="clear:both">
                            <button type="submit" class="btn valid">
                                <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
                            </button>
                        </div>
                    </form>
                </div>
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