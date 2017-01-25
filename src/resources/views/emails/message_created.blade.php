Un nouveau message vient d'être envoyé sur la plateforme :<br/>

<strong>{{ $m->conversation->title }}</strong><br/><br/>

@if (isset($m->conversation->project) && isset($m->conversation->project->client))<strong>Projet :</strong> [{{ $m->conversation->project->client->name }}] {{ $m->conversation->project->name }}@endif<br/><br/>
@if (isset($m->user))<strong>Auteur :</strong> {{ $m->user->complete_name }}<br/><br/>@endif
@if ($m->content)<strong>Contenu :</strong> {!! $m->content !!}<br/><br/>@endif
