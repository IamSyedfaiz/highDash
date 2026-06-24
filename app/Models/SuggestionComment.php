<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionComment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class);
    }

    public function replies()
    {
        return $this->hasMany(SuggestionComment::class, 'parent_id')->with('replies.user', 'user')->oldest();
    }
}
