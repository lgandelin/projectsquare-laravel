<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'read',
        'entity_id',
    ];

    public function user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }
}