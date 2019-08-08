<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function reporter()
    {
        return $this->belongsTo(\App\User::class);
    }

}
