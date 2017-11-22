{{ __('projectsquare::email.new_request') }}<br/><br/>

<strong>{{ __('projectsquare::email.title') }}</strong> {{ $title }}<br/><br/>
<strong>{{ __('projectsquare::email.message') }}</strong> {!! $content !!}<br/><br/>
<strong>Utilisateur :</strong> {{ $user_last_name }} {{ $user_first_name }}<br/><br/>
<strong>Plateforme :</strong> {{ $platform_url }}<br/><br/>

{{ __('projectsquare::email.signature_2') }}<br/>