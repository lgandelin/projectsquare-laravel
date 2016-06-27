@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'files'])
    <div class="content-page">
        <div class="col-lg-12 col-md-12 templates files-template">
            <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
                <h1 class="page-header">{{ trans('projectsquare::project.files') }}</h1>

            <table class="table table-striped">
                @foreach($files as $file)
                    <tr>
                        <td width="250">
                            <a href="{{ asset('uploads/projects' . $file->path) }}" title="{{ $file->name }}" target="_blank">
                                <img class="thumbnail" src="{{ asset('uploads/projects' . $file->thumbnail_path) }}" alt="{{ $file->name }}" width="135" height="80" />
                            </a>
                        </td>
                        <td><a href="{{ asset('uploads/projects' . $file->path) }}" title="{{ $file->name }}" target="_blank">{{ $file->name }}</a></td>
                        <td width="150">{{ \Webaccess\ProjectSquareLaravel\Tools\FileTool::convertFileSize($file->size) }}</td>
                        <td width="275">
                            <a href="{{ asset('uploads/projects' . $file->path) }}" class="btn button" download="{{ $file->name }}"><i class="glyphicon glyphicon-download"></i> {{ trans('projectsquare::generic.download') }}</a>
                            <a href="{{ route('tickets_edit_delete_file', ['id' => $file->id]) }}" class="btn btn-danger delete btn-delete"><i class="glyphicon glyphicon-remove"></i> <span>Supprimer</span></a>
                        </td>
                    </tr>
                @endforeach
            </table>
            <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>

            <div class="col-md-6" style="margin-top: 4rem">
                <h4>{{ trans('projectsquare::tickets.add_files') }}</h4>
                <div class="row fileupload-buttonbar">
                    <div class="col-lg-12 col-md-12 file">
                        <span class="btn fileinput-button add">
                            <input type="file" name="files[]" multiple>
                        </span>
                        <button type="submit" class="btn btn-primary button start">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>{{ trans('projectsquare::tickets.start_file_upload') }}</span>
                        </button>
                        <!--<button type="reset" class="btn delete">
                            <i class="glyphicon glyphicon-remove"></i>
                            <span>Supprimer</span>
                        </button>
                        <!--<button type="button" class="btn btn-danger delete">
                            <i class="glyphicon glyphicon-trash"></i>
                            <span>Supprimer</span>
                        </button>
                        <input type="checkbox" class="toggle">-->
                        <span class="fileupload-process"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-12 fileupload-progress fade" style="margin-top: 2rem;">
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                            </div>
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 2rem;">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            </form>
        </div>
    </div>
    @include('projectsquare::tickets.fileupload')

    <script src="{{ asset('js/vendor/jquery.fileupload/vendor/jquery.ui.widget.js') }}"></script>
    <script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.fileupload-process.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.fileupload-validate.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.fileupload/jquery.fileupload-ui.js') }}"></script>

    <script>
        /*jslint unparam: true, regexp: true */
        /*global window, $ */

        $(function () {
            'use strict';

            // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: "{{ route('projects_files_upload') }}",
                formData: {
                    _token: $('#csrf_token').val(),
                    project_id: $('input[name="project_id"]').val()
                }
            });

            // Enable iframe cross-domain access via redirect option:
            $('#fileupload').fileupload(
                'option',
                'redirect',
                window.location.href.replace(
                    /\/[^\/]*$/,
                    '/cors/result.html?%s'
                )
            );

            // Load existing files:
            $('#fileupload').addClass('fileupload-processing');
            $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: $('#fileupload').fileupload('option', 'url'),
                dataType: 'json',
                context: $('#fileupload')[0],
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
            });
        });
    </script>
@endsection