<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Project');
    }

    public function type()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\TicketType');
    }

    public function last_state()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\TicketState');
    }

    public function states()
    {
        return $this->hasMany('Webaccess\ProjectSquareLaravel\Models\TicketState')->orderBy('id', 'DESC');
    }

    public function files()
    {
        return $this->hasMany('Webaccess\ProjectSquareLaravel\Models\File');
    }
}
