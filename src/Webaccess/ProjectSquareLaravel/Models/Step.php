<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $table = 'steps';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'color',
    ];

    public function project()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Project');
    }
}