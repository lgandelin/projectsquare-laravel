<?php

namespace Webaccess\ProjectSquareLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'thumbnail_path',
        'mime_type',
        'size',
    ];

    public function ticket()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Ticket');
    }

    public function project()
    {
        return $this->belongsTo('Webaccess\ProjectSquareLaravel\Models\Project');
    }
}
