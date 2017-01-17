<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $table = 'ticket_statuses';

    protected $fillable = [
        'name',
        'include_in_planning'
    ];
}
