<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'poll_id',
        'option_text',
    ];


    public function votes() 
    {
        return $this->hasMany(Vote::class, 'option_id');
    }
}
