{{ __('projectsquare::email.hello') }} {{ $first_name }},<br><br>

{{ __('projectsquare::email.account_created') }}<br/><br/>
{{ __('projectsquare::email.account_created_2') }}<br/><br>

<strong>URL :</strong> <a href="http://{{ $url }}">http://{{ $url }}</a><br/>
<strong>{{ __('projectsquare::email.email') }}</strong> {{ $email }}<br/>
<strong>{{ __('projectsquare::email.password') }}</strong> {{ $password }}<br/><br>

{{ __('projectsquare::email.signature_1') }}<br/>
{{ __('projectsquare::email.signature_2') }}<br/>