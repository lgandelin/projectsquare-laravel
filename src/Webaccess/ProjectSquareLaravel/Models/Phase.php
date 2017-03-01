<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $table = 'phases';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'order',
        'due_date',
    ];

    public function project()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Project');
    }
}
