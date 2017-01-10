<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    public $incrementing = false;

    public $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'client_id',
        'remember_token',
    ];

    protected $hidden = [
        'password',
    ];

    public function projects()
    {
        return $this->belongsToMany('Webaccess\ProjectSquareLaravel\Models\Project', 'user_projects');
    }

    public function client()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Client');
    }

    public function getCompleteNameAttribute()
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }
}
