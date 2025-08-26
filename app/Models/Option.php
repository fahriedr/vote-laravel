<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    
    protected $fillable = [
        'poll_id',
        'option_text',
    ];


    public function votes() 
    {
        return $this->hasMany(Vote::class, 'option_id');
    }
}
