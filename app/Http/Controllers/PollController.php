<?php

namespace App\Http\Controllers;

use App\Helpers\IdGenerator;
use App\Models\Poll;
use Illuminate\Auth\Events\Validated;
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
            'unique_id' => IdGenerator::generate('POLL', 6)
        ]);

        return redirect()->back()->with('success', 'Poll created successfully!');
    }
}
