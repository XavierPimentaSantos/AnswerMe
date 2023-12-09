<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnswerCommentController extends Controller
{
    public function store(Request $request, $answer_id)
    {
        $answer_comment_body = $request->input('answer_comment_body');
        $answer = Answer::find($answer_id);

        $request->validate([
            'answer_comment_body' => 'required|max:100',
        ]);

        $answerComment = AnswerComment::create([
            'body' => $answer_comment_body,
            'answer_id' => $answer_id,
            'edited' => 0,
            'user_id' => Auth::user()->id,
        ]);

        $answerComment->save();

        return view('partials.answer', )
    }
}
