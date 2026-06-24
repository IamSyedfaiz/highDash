<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionPoll extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function options()
    {
        return $this->hasMany(SuggestionPollOption::class);
    }

    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class);
    }
}
