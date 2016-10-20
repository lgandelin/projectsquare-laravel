<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'todos';

    public $incrementing = false;

    protected $fillable = [
        'name', 'status',
    ];

    public function user()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\User');
    }
}
