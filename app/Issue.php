<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $hidden = [];
    protected $visible = [
        'id',
        'title',
        'description',
        'reporter',
        'responsible',
        'status',
        'created_at',
        'updated_at',
        'comments'
    ];
    public $comments = [];

    public function __construct(array $attributes = [])
    {
        $this->id = 0;
        parent::__construct($attributes);
    }

    public function reporter()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function responsible()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class)
            ->with('reporter')
            ->orderBy('created_at', 'ASC');
    }
}
