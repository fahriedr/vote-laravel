<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'poll_id',
        'option_id',
        'browser_fingerprint',
        'name'
    ];
}
