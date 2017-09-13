{{ trans('projectsquare::email.hello') }} {{ $first_name }},<br><br>

{{ trans('projectsquare::email.account_created') }}<br/><br/>
{{ trans('projectsquare::email.account_created_2') }}<br/><br>

<strong>URL :</strong> <a href="http://{{ $url }}">http://{{ $url }}</a><br/>
<strong>{{ trans('projectsquare::email.email') }}</strong> {{ $email }}<br/>
<strong>{{ trans('projectsquare::email.password') }}</strong> {{ $password }}<br/><br>

{{ trans('projectsquare::email.signature_1') }}<br/>
{{ trans('projectsquare::email.signature_2') }}<br/>