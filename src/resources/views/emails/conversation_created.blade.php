Une nouvelle conversation vient d'être créée sur la plateforme :<br/><br/>

<strong>{{ $conversation->title }}</strong><br/><br/>

@if (isset($conversation->project) && isset($conversation->project->client))<strong>Projet :</strong> [{{ $conversation->project->client->name }}] {{ $conversation->project->name }}@endif<br/><br/>
@if (isset($conversation->messages[0]->user))<strong>Auteur :</strong> {{ $conversation->messages[0]->user->complete_name }}<br/><br/>@endif
@if ($conversation->messages[0]->content)<strong>Contenu :</strong> {!! $conversation->messages[0]->content !!}<br/><br/>@endif

<a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">Cliquez ici pour voir la conversation</a>

<br/><br/>

L'équipe Projectsquare