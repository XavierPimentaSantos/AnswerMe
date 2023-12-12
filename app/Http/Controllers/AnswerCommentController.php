<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Answer;
use App\Models\AnswerComment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        return view('partials.answer_comment_section', ['answer' => $answer])->render();
    }

    public function edit(Request $request, $answer_id, $comment_id)
    {
        $answerComment = AnswerComment::findOrFail($comment_id);

        if($answerComment->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'answer_comment_body' => 'required|max:100',
        ]);
        
        $answerComment->body = $request->input('answer_comment_body');
        $answerComment->edited = true;

        $answerComment->save();

        return view('partials.answer_comment', ['comment' => $answerComment])->render();
    }

    public function delete(Request $request, $answer_id, $comment_id)
    {
        $answerComment = AnswerComment::findOrFail($comment_id);

        if($answerComment->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        $answerComment->delete();
    }
}
