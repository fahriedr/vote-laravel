<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2|max:10',
            'options.*' => 'required|string|max:100',
            'end_date' => 'nullable|date|after:today',
        ]);

        $poll = Poll::create([
            'question' => $request->input('question'),
            'user_id' => auth()->id(),
            'status' => 1,
            'end_date' => $request->input('end_date'),
            'unique_id' => Helper::generateUniqueId('POLL', 6)
        ]);

        // options
        foreach ($request->input('options') as $optionText) {
            $poll->options()->create(['option_text' => $optionText]);
        }

        // return redirect()->back()->with('success', 'Poll created successfully!');
        return redirect('/vote/'.$poll->unique_id)->with('success', 'Poll created successfully!');
    }

    public function result(Request $request, $pollid)
    {

        $poll = Poll::with(['options' => function ($q) {
            $q->withCount(['votes']);
        }])
        ->withCount('votes')
        ->where('unique_id', $pollid)
        ->first();

        if (!$poll) {
            return redirect('/')->with('error', 'Poll not found');
        }



        return view('result', ['data' => $poll]);
    }
}
