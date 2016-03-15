<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

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

    public function unread_messages()
    {
        return $this->belongsToMany('Webaccess\ProjectSquareLaravel\Models\Message', 'messages_read', 'user_id', 'message_id')->withPivot('read');
    }

    public function getCompleteNameAttribute()
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }
}
