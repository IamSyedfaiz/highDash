<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionPollVote extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function option()
    {
        return $this->belongsTo(SuggestionPollOption::class, 'suggestion_poll_option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
