<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'estimated_time_days',
        'estimated_time_hours',
        'status_id',
    ];

    public function project()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Project');
    }

    public function phase()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Phase');
    }

    public function author_user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }

    public function allocated_user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }
}
