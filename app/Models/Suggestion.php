<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function comments()
    {
        return $this->hasMany(SuggestionComment::class)->whereNull('parent_id')->with('replies.user', 'user')->latest();
    }

    public function poll()
    {
        return $this->hasOne(SuggestionPoll::class);
    }
}
