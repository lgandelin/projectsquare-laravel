<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    public function user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }

    public function ticket()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Ticket');
    }

    public function task()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Task');
    }

    public function project()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Project');
    }
}
