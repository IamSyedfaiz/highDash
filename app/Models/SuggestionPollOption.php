<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionPollOption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function poll()
    {
        return $this->belongsTo(SuggestionPoll::class, 'suggestion_poll_id');
    }

    public function votes()
    {
        return $this->hasMany(SuggestionPollVote::class);
    }
}
