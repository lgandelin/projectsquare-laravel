<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TicketState extends Model
{
    protected $table = 'ticket_states';

    protected $fillable = [
        'priority',
        'due_date',
        'estimated_time',
        'comments',
    ];

    public function ticket()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Ticket');
    }

    public function author_user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }

    public function allocated_user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }

    public function status()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\TicketStatus');
    }

    public function getDueDateAttribute($value)
    {
        if ($value != '0000-00-00' && $value != null) {
            return Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
        }

        return '';
    }

    /*public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }*/
}
