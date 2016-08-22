{{ trans('projectsquare::email.new_request') }}<br/><br/>

<strong>{{ trans('projectsquare::email.title') }}</strong> {{ $title }}<br/><br/>
<strong>{{ trans('projectsquare::email.message') }}</strong> {!! $content !!}<br/><br/>
<strong>{{ trans('projectsquare::email.user_id') }}</strong> {{ $user_id }}<br/><br/>

{{ trans('projectsquare::email.signature') }}<br/>