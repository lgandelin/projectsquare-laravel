<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'content',
    ];

    public function user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }

    public function conversation()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Conversation');
    }
}
