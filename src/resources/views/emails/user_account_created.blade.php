{{ trans('projectsquare::email.account_created') }}<br/><br/>

<strong>{{ trans('projectsquare::email.email') }}</strong> {{ $email }}<br/>
<strong>{{ trans('projectsquare::email.password') }}</strong> {{ $password }}<br/>
<strong>{{ trans('projectsquare::email.first_name') }}</strong> {{ $first_name }}<br/>
<strong>{{ trans('projectsquare::email.name') }}</strong> {{ $last_name }}<br/><br/>

{{ trans('projectsquare::email.platform_access_1') }} <a href="http://{{ $url }}"><strong>{{ trans('projectsquare::email.platform_access_2') }}</strong></a>.<br/><br/>

{{ trans('projectsquare::email.signature_1') }}<br/>
{{ trans('projectsquare::email.signature_2') }}<br/>