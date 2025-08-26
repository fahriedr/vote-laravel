<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{

    public function create(Request $request)
    {
        return view('create-poll');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2|max:5',
            'options.*' => 'required|string|max:100',
            'allow_multiple' => 'in:1,0',
            'require_voter_name' => 'in:1,0',
            'end_date' => 'nullable|date|after:today',
        ]);

        $poll = Poll::create([
            'question' => $request->input('question'),
            'user_id' => auth()->id(),
            'status' => 1,
            'allow_multiple' => (int)$request->input('allow_multiple'),
            'require_voter_name' => (int)$request->input('require_voter_name'),
            'end_date' => $request->input('end_date'),
            'unique_id' => Helper::generateUniqueId('POLL', 6)
        ]);

        // options
        foreach ($request->input('options') as $optionText) {
            $poll->options()->create(['option_text' => $optionText]);
        }

        return redirect('dashboard')->with('success', 'Poll created successfully!');
    }

    public function edit(Request $request, $pollid)
    {

        $user = Auth::user();

        $poll = Poll::with(['options' => function ($q) {
            $q->withCount(['votes']);
        }])
        ->withCount('votes')
        ->where('id', $pollid)
        ->first();

        if (!$poll) {
            return redirect('/')->with('error', 'Poll not found');
        }

        if($poll->user_id != $user->id) {
            return redirect('dashboard')->with('error', 'Access Denied');
        }

        return view('edit-poll', ['data' => $poll]);
    }

    public function update(Request $request, $pollid)
    {

        DB::beginTransaction();

        $user = Auth::user();
        try {

            $request->validate([
                'question' => 'string|max:255',
                'options' => 'required|array|min:2|max:5',
                'allow_multiple' => 'in:1,0',
                'require_voter_name' => 'in:1,0',
                'end_date' => 'nullable|date|after:today',
            ]);


            $poll = Poll::with(['options' => function ($q) {
                    $q->withCount(['votes']);
                }])
                ->withCount('votes')
                ->where('user_id', $user->id)
                ->where('id', $pollid)
                ->first();


            if (!$poll) {
                return redirect('dashboard')->with('error', 'Poll not found');
            }

            if($poll->user_id != $user->id) {
                return redirect('dashboard')->with('error', 'Access Denied');
            }


            $poll->question = $request->question;
            $poll->allow_multiple = $request->allow_multiple ? $request->allow_multiple : 0;
            $poll->require_voter_name = $request->require_voter_name ? $request->require_voter_name : 0;
            $poll->end_date = $request->end_date ?? null;
            
            $poll->save();

            $options = $request->input('options', []);


            foreach ($options as $opt) {
                if (isset($opt['id'])) {
                    // Update existing
                    $option = $poll->options()->find($opt['id']);
                    if ($option) {
                        $option->update(['option_text' => $opt['option_text']]);
                    }
                } else {
                    // Create new if no id
                    if (empty($opt['id'])) {
                        $poll->options()->create(['option_text' => $opt]);
                    }
                }
            }

            DB::commit();

            return redirect('dashboard')->with('success', 'Update success');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage()); 
        }
    }

    public function delete(Request $request, $pollid)
    {

        $user = Auth::user();

        $poll = Poll::where('user_id', $user->id)
            ->where('id', $pollid)
            ->first();

        if (!$poll) {
            return redirect('/')->with('error', 'Poll not found');
        }

        if($user->id != $poll->user_id) {
            return redirect('/')->with('error', 'Access denied');
        }

        $poll->delete();

        return redirect('dashboard')->with('success', "Delete Successfully");
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

    public function search(Request $request)
    {
        if ($request->has('q')) {
            session(['poll_search' => $request->q]);
            return redirect()->route('polls.search');
        }

        $query = session('poll_search');
        $polls = Poll::when($query, function ($qbuilder) use ($query) {
            $qbuilder->where('question', 'LIKE', "%{$query}%");
        })->paginate(10);

        return view('search', compact('polls', 'query'));
    }
}
