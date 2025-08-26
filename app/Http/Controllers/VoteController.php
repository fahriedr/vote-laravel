<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function show(Request $request, $pollUniqeId)
    {

        $poll = Poll::with(['options'])->where('unique_id', $pollUniqeId)->first();

        if(!$poll) {

            return redirect('/')->with('error', 'Poll not found!');
        }

        if($poll->status != 1) {
            return redirect('/')->with('error', 'Poll is not active!');
        }

        return view('vote', ['data' => $poll]);
    }

    public function create(Request $request, $pollId)
    {

        $request->validate([
            'option_id' => 'required|exists:options,id',
            'poll_id' => 'required|exists:polls,id',
        ]);

        $poll = Poll::where('id', $pollId)->first();

        if(!$poll) {
            return redirect()->back()->with('error', 'Poll not found!');
        }

        if($poll->status != 1) {
            return redirect()->back()->with('error', 'Poll is not active!');
        }

        // Check if user has already voted
        $hasVoted = Vote::where('poll_id', $pollId)
                    ->where('browser_fingerprint', $request->input('fingerprint'))
                    ->exists();

        if($hasVoted) {
            return redirect()->back()->with('error', 'You have already voted in this poll!');
        }

        // Record the vote
        Vote::create([
            'poll_id' => $pollId,
            'option_id' => $request->option_id,
            'browser_fingerprint' => $request->fingerprint,
        ]);

        return redirect('/poll/result/' . $poll->unique_id,)->with('success', 'Your vote has been recorded!');
    }
}
