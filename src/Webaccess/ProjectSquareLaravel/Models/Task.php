<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'to_do';

    protected $fillable = [
        'name', 'status',
    ];

    public function user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }
}
