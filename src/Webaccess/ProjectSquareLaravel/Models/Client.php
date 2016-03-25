<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'name',
    ];

    public function projects()
    {
        return $this->hasMany('Webaccess\ProjectSquareLaravel\Models\Project');
    }

    public function users()
    {
        return $this->hasMany('Webaccess\ProjectSquareLaravel\Models\Client');
    }
}
