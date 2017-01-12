<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';

    public $incrementing = false;

    protected $fillable = [
        'title',
    ];

    public function project()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Project');
    }

    public function messages()
    {
        return $this->hasMany('Webaccess\ProjectSquareLaravel\Models\Message')->orderBy('created_at', 'ASC');
    }

    public function users()
    {
        return $this->belongsToMany('Webaccess\ProjectSquareLaravel\Models\User', 'conversation_users');
    }
}
