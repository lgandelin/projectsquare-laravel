<?php

return [
    'tasks' => "Les tâches représentent les différentes actions à effectuer pendant la réalisation d'un nouveau projet. Exemple : configurer Google Analytics.",
    'tickets' => "Les tickets représentes les éventuelles actions à effectuer après la livraison d'un projet, lors de sa phase de maintenance. Exemple : un bug rencontré, une évolution souhaitée, etc...",
    'clients_list' => "Les clients sont les entités (sociétés, instutions, associations, etc...) pour qui vous allez réaliser un projet.",
    'projects_list' => "Les projets sont liés à un client, et représentent la création d'un site web ou d'une application mobile par exemple.",
    'roles_list' => "Les profils représentent l'ensemble des métiers de votre équipe (ex : webdesigner, développeur...)",
    'tickets_statuses_list' => "Les tickets passent par différents statuts en fonction de leur avancement. Exemple : A faire / A valider / Corrigé etc...",
    'tickets_types_list' => "Les tickets peuvent être catégorisés selon leur type. Exemple : Bug / Evolution / Correction orthographe etc...",
    'messages' => "Les messages permettent au client d'échanger facilement avec l'équipe projet.",
    'planning' => "Le planning vous permet de visionner l'ensemble de vos tâches et tickets planifiés, par jour, semaine ou mois.",
    'contributors_list' => "Liste des différents membres de votre équipe.",
    'allocated_tickets_list' => "Tickets qui vous sont assignés",
    'non_allocated_tickets_list' => "Tickets assignés à aucun collaborateur",
    'allocated_tasks_list' => "Tâches qui vous sont assignées",
    'non_allocated_tasks_list' => "Tâches assignées à aucun collaborateur",
    'cms' => "Accédez à l'interface d'administration du CMS du projet.",
    "monitoring_title" => "Surveillez la disponiblité et le temps de réponse du serveur de votre projet.",
    'calendar_title' => "Le calendrier vous permet de visualiser les différentes étapes de votre projet. Exemple : Rédaction des contenus, Réalisation du webdesign, etc...",
    'seo_title' => "Visualisez les principales statistiques Google Analytics de votre projet.",
    'files' => "Ajoutez et téléchargez des documents relatifs à votre projet : images, .pdf, .docx, .psd etc...",

    'monitoring' => [
        'disponibility' => 'La disponibilité serveur représente le pourcentage du temps où le site est accessible (codes réponse HTTP de succès = 200, 201, etc...)',
        'average_loading_time' => "Moyenne des temps de réponse du serveur. Le temps de réponse serveur est calculé entre le moment où est faite la requête et le premier retour du serveur. Le temps de chargement de la page n'est pas pris en compte.",
    ],
    'reporting_title' => "Cette interface vous permet de visualiser l'ensemble des tâches et tickets de votre projet, ainsi que de voir sa rentabilité pour les phases de réalisation (tâches) et de maintenance (tickets)",
    'client_accounts' => "Les comptes utilisateurs permettent à vos clients de se connecter à cette plateforme, afin d'accéder à leur projet uniquement.",

    'project' => [
        'color' => 'Associez une couleur à ce projet pour identifier rapidement une tâche ou un ticket du projet en question.',
        'scheduled_time_reporting' => "Cette donnée est utilisée dans l'interface Reporting, notamment dans les calculs de rentabilité des projets.",
        'front_url' => "Cette URL est utilisée par l'interface de Monitoring pour calculer les temps de réponse du serveur.",
        'cms_url' => "Cette URL permet un accès direct à l'interface d'administration depuis l'onglet CMS. Il faut cependant que le site web en question accepte l'affichage venant de l'extérieur.",
        'project_resources' => "Liste des ressources assignées au projet. Une ressource doit d'abord être assignée au projet afin qu'elle puisse accéder à l'interface de celui-ci",
        'acceptable_loading_time' => 'Temps de réponse serveur que vous considérez comme normal, en dessous duquel vous ne souhaitez pas être averti.',
        'email_loading_time' => 'Adresse email à avertir en cas de temps de réponse serveur trop long, ou code de réponse HTTP erreur reçue.',
        'slack_channel' => 'Recevez les notifications liés à ce projet directement dans Slack en renseignant le channel associé.',
        'profile_google_analytics' => "Cet ID profil Google Analytics (différent de l'ID de suivi) se trouve dans l'URL de votre tableau de bord Google Analytics, et correspond au dernier nombre situé après la lettre p (9 chiffres).",
        'ressources' => 'Les ressources assignées à un projet pourront le voir dans la barre latérale des projets',
        'status' => 'Les projets en statut "En cours" apparaissent dans la barre latérale des projets',
    ],

    'avatar' => 'Uploadez votre avatar pour permettre aux autres personnes de vous identifier facilement ;)',

    'slack' => 'Pour connecter Projectsquare avec vos channels Slack, créez un webhook depuis votre interface Slack et copier l\'URL dans ce champ. Pour plus de détails, consulter la documentation.',

    'import_phases_and_tasks' => 'Importez rapidement vos phases et tâches à partir d\'une liste au format texte',
];