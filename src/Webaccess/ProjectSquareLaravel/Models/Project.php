<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'website_front_url',
        'website_back_url',
        'color',
        'status_id',
    ];

    public function client()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Client');
    }

    public function users()
    {
        return $this->belongsToMany('Webaccess\ProjectSquareLaravel\Models\User', 'user_projects', 'project_id', 'user_id')->withPivot('role_id');
    }

    public function requests()
    {
        return $this->belongsToMany('Webaccess\ProjectSquareLaravel\Models\Request');
    }
}
