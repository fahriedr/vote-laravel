<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function form()
    {
        return view('similarity.form');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'input1' => 'required|string',
            'input2' => 'required|string',
            'type'   => 'required|in:sensitive,non-sensitive',
        ]);

        $input1 = $request->input('input1');
        $input2 = $request->input('input2');
        $type   = $request->input('type');

        // case insensitive
        if ($type === 'non-sensitive') {
            $input1 = mb_strtolower($input1);
            $input2 = mb_strtolower($input2);
        }

        $totalChars = strlen($input1);
        $matchCount = 0;

        for ($i = 0; $i < $totalChars; $i++) {
            $char = $input1[$i];
            if (str_contains($input2, $char)) {
                $matchCount++;
            }
        }

        $percentage = $totalChars > 0 ? ($matchCount / $totalChars) * 100 : 0;

        return back()->with('result', [
            'input1' => $request->input('input1'),
            'input2' => $request->input('input2'),
            'type'   => $type,
            'match'  => $matchCount,
            'total'  => $totalChars,
            'result' => round($percentage, 2) . '%'
        ]);
    }
}
