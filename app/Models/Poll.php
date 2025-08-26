<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'question',
        'user_id',
        'status',
        'end_date',
        'allow_multiple',
        'require_voter_name'
    ];

    public function options()
    {
        return $this->hasMany(Option::class, 'poll_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'poll_id');
    }
}
