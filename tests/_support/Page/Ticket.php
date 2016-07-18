<?php

namespace Page;

class Ticket
{
    public static $URI = '/tickets';
    public static $title = 'Liste des tickets';
    public static $messageTicketCreatedSuccess = 'Ticket ajouté avec succès';
    public static $messageTicketUpdatedSuccess = 'Ticket modifié avec succès';
    public static $messageTicketDeletedSuccess = 'Ticket supprimé avec succès';
    public static $messageTicketDueDateError = 'L\'échéance de ce ticket est déjà passée';
}