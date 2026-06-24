<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Models\SuggestionComment;
use App\Models\SuggestionPoll;
use App\Models\SuggestionPollOption;
use App\Models\SuggestionPollVote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Suggestion::with(['comments.user', 'comments.replies.user', 'poll.options.votes', 'targetUser'])
            ->latest();

        $tab = $request->query('tab', 'all');

        if ($tab === 'my_targeted') {
            $query->where('target_user_id', Auth::id());
        } elseif ($tab === 'polls') {
            $query->has('poll');
        } elseif ($tab === 'trending') {
            $query->withCount('comments')->orderByDesc('comments_count');
        }

        $suggestions = $query->paginate(15);
        $users = User::all();

        return view('suggestions.index', compact('suggestions', 'users', 'tab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'target_user_id' => 'nullable|exists:users,id',
            'has_poll' => 'nullable|boolean',
            'poll_question' => 'required_if:has_poll,1|string|max:255',
            'poll_options' => 'required_if:has_poll,1|array|min:2',
            'poll_options.*' => 'nullable|string|max:255'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('suggestions', 'public');
        }

        $suggestion = Suggestion::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'target_user_id' => $request->target_user_id,
        ]);

        if ($request->has_poll && $request->has_poll == 1) {
            $poll = SuggestionPoll::create([
                'suggestion_id' => $suggestion->id,
                'question' => $request->poll_question
            ]);

            foreach ($request->poll_options as $optionText) {
                if (!empty(trim($optionText))) {
                    SuggestionPollOption::create([
                        'suggestion_poll_id' => $poll->id,
                        'option_text' => trim($optionText)
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Suggestion posted successfully.');
    }

    public function storeComment(Request $request, Suggestion $suggestion)
    {
        $request->validate([
            'comment' => 'required|string',
            'parent_id' => 'nullable|exists:suggestion_comments,id'
        ]);

        SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', 'Comment added.');
    }

    public function votePoll(Request $request, SuggestionPoll $poll)
    {
        $request->validate([
            'option_id' => 'required|exists:suggestion_poll_options,id'
        ]);

        $existingVote = SuggestionPollVote::where('user_id', Auth::id())
            ->whereHas('option', function($q) use ($poll) {
                $q->where('suggestion_poll_id', $poll->id);
            })->first();

        if ($existingVote) {
            $existingVote->update(['suggestion_poll_option_id' => $request->option_id]);
        } else {
            SuggestionPollVote::create([
                'suggestion_poll_option_id' => $request->option_id,
                'user_id' => Auth::id()
            ]);
        }

        return redirect()->back()->with('success', 'Vote recorded.');
    }
}
